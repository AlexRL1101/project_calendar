FROM php:8.0-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libpng-dev \
    sendmail \
    zlib1g-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql gd \
    && docker-php-ext-enable mysqli pdo pdo_mysql

# Copiar el código fuente
COPY src/ /var/www/html/
WORKDIR /var/www/html/

# Cambiar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Exponer puerto 80
EXPOSE 80

# Usar Apache por defecto
CMD ["apache2-foreground"]
