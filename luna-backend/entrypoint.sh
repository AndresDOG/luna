#!/bin/bash

# Comandos a ejecutar
echo "Instalando Dependencias de Proyecto: API"

composer install
composer dump-autoload

#php artisan migrate:fresh --seed

# Ejecutar el comando principal
exec "$@"
