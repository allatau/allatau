# Installation

```
composer install
cp .env.example .env
php artisan migrate
php artisan serve
```

Затем открыть graphql playground: [localhost:8000/graphql-playground](http://localhost:8000/graphql-playground)

## Установка расширения для mysql

```
sudo apt install php8.0-mysql
sudo phpenmod -v 8.0 pdo_mysql
sudo apt install php-mysql
```

##  Problem 1 - phpunit/phpunit[9.6.0, ..., 9.6.x-dev] require ext-dom 
Problem 1 - phpunit/phpunit[9.6.0, ..., 9.6.x-dev] require ext-dom * -> it is missing from your system. Install or enable PHP's dom extension. - Root composer.json requires phpunit/phpunit ^9.6 -> satisfiable by phpunit/phpunit[9.6.0, ..., 9.6.x-dev].

sudo apt install php-xml
sudo apt-get install php8.1-xml

