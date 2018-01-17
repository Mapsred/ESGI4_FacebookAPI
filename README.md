MyPhotoBook
=========

MyPhotoBook is a project using Symfony 3.4 (LTS).

## How to install

### With Docker-Compose

copy ``docker-compose.yml.dist`` to ``docker-compose.yml``

Replace what you want to

Run ``docker-compose up`` to build the network

copy ``app/config/parameters.yml.dist`` to ``app/config/parameters.yml``

Replace the ``facebook_client_id`` and ``facebook_client_secret`` parameters.
If you did not modify the ``docker-compose.yml`` then you are ready to go

Then run 

* ``docker-compose exec php-fpm composer install`` To install composer dependencies
* ``docker-compose exec nodejs bower install --allow-root`` To install bower dependencies
* ``docker-compose exec php-fpm php bin/console assetic:dump`` To dump all css/js libraries


### Without Docker-Compose

copy ``app/config/parameters.yml.dist`` to ``app/config/parameters.yml``

Replace the ``facebook_client_id`` and ``facebook_client_secret`` parameters and all databases parameters.

Then run 

* ``composer install`` To install composer dependencies
* ``bower install`` To install bower dependencies
* ``php bin/console assetic:dump`` To dump all css/js libraries


### Finally

Open your host file (`C:\Windows\System32\driver\etc\hosts`) on Windows

Copy the following 2 lines and replace firstname-lastname with your facebook firstname and lastname:
```
	127.0.0.1		facebook-final.develop
	127.0.0.1		firstname-lastname.facebook-final.develop
```

Connect to ``http://facebook-final.develop/app_dev.php/login``

if you have the following error ``You are not allowed to access this file. Your ip is x.x.x.x``

Then add your ip (x.x.x.x here) to the app_dev file ip array (do not remove any existing)

Run ``docker-compose exec php-fpm php bin/console doctrine:schema:update --force`` to build the database schema

### FOSUser and Site Management 

To access to the Admin page you need to create a database user with the following command :
``php bin/console fos:user:create admin --super-admin`` (don't forget the `docker-compose exec php-fpm` if you use docker-compose)

Next, the ``facebook-final.develop/manage/login`` and ``facebook-final.develop/manage/edit`` pages are available to you 