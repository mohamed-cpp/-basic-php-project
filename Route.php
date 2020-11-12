<?php

class Route{

    private static $routes = Array();
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;
    private static $url = null;

    public static function add($expression, $call, $method = 'get'){
        $parameter = null;
        $fun = self::getParameter($expression);

        if ($fun){
            $parameter = $fun['parameter'];
            $expression = $fun['expression'];
        }

        array_push(self::$routes,Array(
            'expression' => $expression,
            'call' => $call,
            'method' => $method,
            'parameter' => $parameter,
        ));
    }

    public static function pathNotFound($function){
        self::$pathNotFound = $function;
    }

    public static function methodNotAllowed($function){
        self::$methodNotAllowed = $function;
    }

    public static function run($basepath = '/'){

        // Parse current url
        $parsed_url = parse_url(self::$url ? self::$url : $_SERVER['REQUEST_URI']);//Parse Uri

        if(isset($parsed_url['path'])){
            $path = $parsed_url['path'];
        }else{
            $path = '/';
        }

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

//        print_r($parsed_url) ;

        $path_match_found = false;

        $route_match_found = false;

        foreach(self::$routes as $route){


            // If the method matches check the path

            // Add basepath to matching string
            if($basepath!=''&&$basepath!='/'){
                $route['expression'] = '('.$basepath.')'.$route['expression'];
            }
//            echo $route['expression'];
//            echo "<br>";

            // Add 'find string start' automatically
            $route['expression'] = '^'.$route['expression'];

            // Add 'find string end' automatically
            $route['expression'] = $route['expression'].'$';

//             echo $route['expression'].'<br/>';

            // Check path match
            if(preg_match('#'.$route['expression'].'#',$path,$matches)){

                $path_match_found = true;

                // Check method match
                if(strtolower($method) == strtolower($route['method'])){

                    array_shift($matches);// Always remove first element. This contains the whole string

                    if($basepath!=''&&$basepath!='/'){
                        array_shift($matches);// Remove basepath
                    }

                    require_once $route['call']['path'];
                    (new $route['call']['class']())
                        ->{$route['call']['fun']}($route['parameter']);

//                    call_user_func_array($route['function'], $matches);

                    $route_match_found = true;

                    // Do not check other routes
                    break;
                }
            }
        }

        // No matching route was found
        if(!$route_match_found){

            // But a matching path exists
            if($path_match_found){
                header("HTTP/1.0 405 Method Not Allowed");
                if(self::$methodNotAllowed){
                    call_user_func_array(self::$methodNotAllowed, Array($path,$method));
                }
            }else{
                header("HTTP/1.0 404 Not Found");
                if(self::$pathNotFound){
                    call_user_func_array(self::$pathNotFound, Array($path));
                }
            }

        }

    }
    private static function getParameter($expression){
        if(preg_match('/{(.*?)}/', $expression, $output_array)){
            $url_without_par = preg_replace('/{(.*?)}/', '', $expression);

            $parameter = str_replace( $url_without_par, '', $_SERVER['REQUEST_URI']) ;
            $cleaned_url = substr_replace($_SERVER['REQUEST_URI'],"",
                similar_text($url_without_par, $_SERVER['REQUEST_URI']));
            if($url_without_par=== $cleaned_url){
                self::$url = $cleaned_url;
                return [
                    "parameter"=>$parameter,
                    "expression"=>$url_without_par
                ];
            }
        }
        return false;
    }

}