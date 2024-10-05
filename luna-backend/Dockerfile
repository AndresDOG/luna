FROM php:8.3-fpm

# Copiamos los archivos package.json composer.json y composer-lock.json a /var/www/
COPY composer*.json /var/www/html

# Nos movemos a /var/www/
WORKDIR /var/www/html

# Instalar dependencias de sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libfreetype6-dev \
    libssl-dev \
    locales \
    zip \
    vim \
    git \
    curl

# Instalar extensión para SQL Server
RUN docker-php-ext-install zip exif pcntl
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql 
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalamos dependendencias de composer
RUN composer install --no-ansi --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Copia el archivo de configuración de OpCache
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copiar código fuente
COPY . /var/www/html

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 9000 para PHP-FPM
EXPOSE 9000

# Iniciar PHP-FPM
CMD ["php-fpm"]

# Instalamos el proyecto con sus dependicias
COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
