FROM php:8.0-apache as php-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN apt-get update -y
RUN apt-get install libyaml-dev -y
RUN pecl install yaml && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini && docker-php-ext-enable yaml

COPY . /var/www/html/