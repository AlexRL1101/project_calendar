#!/bin/bash

# Inicia el servicio de MySQL
service mysql start

# Espera a que MySQL esté listo
until mysqladmin ping --silent; do
  echo "Esperando a MySQL..."
  sleep 2
done

# Verifica si la base de datos ya existe
DB_EXISTS=$(mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "SHOW DATABASES LIKE '$MYSQL_DATABASE';" | grep "$MYSQL_DATABASE" > /dev/null; echo "$?")

if [ "$DB_EXISTS" -ne 0 ]; then
  echo "Importando base de datos..."
  mysql -u root -p"$MYSQL_ROOT_PASSWORD" < /docker-entrypoint-initdb.d/vesselcalendario.sql
else
  echo "La base de datos ya existe, no se importa."
fi

# Inicia Apache en primer plano
apache2-foreground
