FROM php:8.0-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer (si aún no lo tienes)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar PHPUnit a través de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer global require phpunit/phpunit

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código de la aplicación (si existe)
COPY . /var/www/html