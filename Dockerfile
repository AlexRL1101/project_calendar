FROM php:8.0-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql
RUN apt-get update && apt-get upgrade -y

RUN apt-get update -y && apt-get install -y sendmail libpng-dev

RUN apt-get update && \
    apt-get install -y \
    zlib1g-dev 

RUN docker-php-ext-install gd