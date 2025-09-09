FROM php:7.4.19-apache
WORKDIR /var/www/html
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY ./ /var/www/html
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|g' /etc/apache2/apache2.conf
