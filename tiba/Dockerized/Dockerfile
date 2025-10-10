FROM php:7.3-apache
#FROM php:8.0-apache
#Install git and MySQL extensions for PHP

RUN apt-get update && apt-get install -y git && apt-get install sudo && apt-get install nano
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite


COPY . /var/www/html/
#RUN sudo chmod -R ugo+rw tiba/storage
EXPOSE 80/tcp
EXPOSE 443/tcp

