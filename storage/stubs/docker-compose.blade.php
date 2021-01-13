# Версия docker-compose
version: '2'
# Список наших сервисов (контейнеров)
services:
   nginx:
      # используем последний стабильный образ nginx
      image: nginx:latest
      container_name: 'nginx'
      # маршрутизируем порты
      ports:
         - '80:80'
      # монтируем директории, слева директории на основной машине, справа - куда они монтируются в контейнере
      volumes:
         - ./hosts:/etc/nginx/conf.d
         - ./domains:/var/www
         - ./logs:/var/log/nginx
      # nginx должен общаться с php контейнером
      links:
         - php74
         - php73
         - php56
         - php54
   php74:
      # у нас свой образ для PHP, указываем путь к нему и говорим что его надо собрать
      build: ./images/php74
      container_name: 'php74'
      # этот образ будет общаться с mysql
      links:
         - mariadb
      # монтируем директорию с проектами
      volumes:
         - ./domains:/var/www
   php73:
      # у нас свой образ для PHP, указываем путь к нему и говорим что его надо собрать
      build: ./images/php73
      container_name: 'php73'
      # этот образ будет общаться с mysql
      links:
         - mariadb
      # монтируем директорию с проектами
      volumes:
         - ./domains:/var/www
   php56:
      # у нас свой образ для PHP, указываем путь к нему и говорим что его надо собрать
      build: ./images/php56
      container_name: 'php56'
      # этот образ будет общаться с mysql
      links:
         - mariadb
      # монтируем директорию с проектами
      volumes:
         - ./domains:/var/www
   php54:
      # у нас свой образ для PHP, указываем путь к нему и говорим что его надо собрать
      build: ./images/php54
      container_name: 'php54'
      # этот образ будет общаться с mysql
      links:
         - mariadb
      # монтируем директорию с проектами
      volumes:
         - ./domains:/var/www
   mariadb:
      image: mariadb:latest
      container_name: 'mariadb'
      ports:
         - '3306:3306'
      volumes:
         - ./mysql:/var/lib/mysql
      # задаем пароль для root пользователя
      environment:
         MYSQL_ROOT_PASSWORD: root
   phpmyadmin:
      image: phpmyadmin:latest
      container_name: 'pma'
      ports:
         - '8183:80'
      environment:
         - PMA_HOST=mariadb
         - UPLOAD_LIMIT=40M