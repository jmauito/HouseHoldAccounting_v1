FROM php:7.4.19-apache
WORKDIR /var/www/html
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY ./ /var/www/html
