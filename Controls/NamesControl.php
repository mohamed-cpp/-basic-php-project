<?php

include_once "Models/Names.php";


class NamesControl
{

    public function index(){
        $name = new Names();
        $all = $name->getAll();
        if ($all){
            echo json_encode($all);
            die();
        }
        echo json_encode("No Data Found");
    }
    public function create(){

        $data = json_decode(file_get_contents("php://input"),true);
        $name = new Names();
        $create = $name->create($data);

        if($create) {
            echo json_encode(['message' => 'Created']);
            die();
        }
        echo json_encode(['message' => 'Not Created']);
    }
    public function delete($id){
        $name = new Names();
        $name = $name->destroy($id);
        if ($name){
            echo json_encode($name);
            die();
        }
        echo json_encode(['message'=> "Unsuccessfully" ]);
    }

}