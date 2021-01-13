server {
	index index.php index.html;
	server_name {{ $name }}.test;
	error_log  /var/log/nginx/error_{{ $name }}.log;
	access_log /var/log/nginx/access_{{ $name }}.log;
	root /var/www/{{ $name }}.test;

	location ~ \.php$ {
		try_files $uri =404;
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass php{{ str_replace('.', '', $version) }}:9000;
		fastcgi_index index.php;
		include fastcgi_params;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		fastcgi_param PATH_INFO $fastcgi_path_info;
	}

	location / {
		if (!-e $request_filename) {
			rewrite ^/(.*)$ /index.php?q=$1 last;
		}
	}

	location ~ /\.(?!well-known).* {
		deny all;
	}
}
