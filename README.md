# basic-php-project

## The small API website created with pure PHP beside HTML,Bootstrap and Jquery, created with MVC design
### Before try it please open config\Database.php and edit if needed and import 'names_db.sql'.

--------
#### For Apache Server
``` 
// At Config file //
// apache2/conf/httpd.conf //
...
<VirtualHost *:80>

    ServerName example.test

    DocumentRoot /project/path

</VirtualHost>
```
##### Reload apache server
```
// open hosts file //

...
example.test 127.0.0.1
...

``` 
#### For Nginx
## Note: NOT TESTED
```
server {

	listen 80;

    server_name example.test;
 
	root /project/path;

	index index.html index.php;

  	location /public {
    	alias /project/path/public;
    	index index.html index.htm;
  	}

	location / {
		# First attempt to serve request as file, then
		# as directory, then fall back to displaying a 404.
		try_files $uri $uri/ /index.php?$query_string;
	}

	location ~ \.php$ {

        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.3-fpm.sock; // php-fpm path
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
	}

	# deny access to .htaccess files, if Apache's document root
	location ~ /\.ht {
		deny all;
	}


}
```