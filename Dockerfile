FROM php:8.0-apache

# Variables de MySQL
ENV MYSQL_ROOT_PASSWORD=SuperSecretoRoot
ENV MYSQL_DATABASE=vesselcalendario
ENV MYSQL_USER=vc_user
ENV MYSQL_PASSWORD=ClaveSegura123

RUN apt-get update && apt-get install -y \
    default-mysql-server \
    default-mysql-client \
    libpng-dev \
    sendmail \
    && docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

COPY src/ /var/www/html/
WORKDIR /var/www/html/

COPY database/vesselcalendario.sql /docker-entrypoint-initdb.d/

RUN chown -R www-data:www-data /var/www/html \
    && chmod 755 /var/www/html \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80

RUN apt-get update && apt-get upgrade -y

RUN apt-get update -y && apt-get install -y sendmail libpng-dev

RUN apt-get update && \
    apt-get install -y \
    zlib1g-dev 

RUN docker-php-ext-install gd

CMD ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
