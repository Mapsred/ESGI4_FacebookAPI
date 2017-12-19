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
* ``bower install``
* ``docker-compose exec php-fpm bin/console assetic:dump`` To dump all css/js libraries


### Without Docker-Compose

copy ``app/config/parameters.yml.dist`` to ``app/config/parameters.yml``

Replace the ``facebook_client_id`` and ``facebook_client_secret`` parameters and all databases parameters.

Then run 

* ``composer install`` To install composer dependencies
* ``bower install``
* ``php bin/console assetic:dump`` To dump all css/js libraries
