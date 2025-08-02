FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libonig-dev zlib1g-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Устанавливаем Xdebug 3
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Настройки Xdebug (для развития можно вынести в отдельный xdebug.ini)
RUN echo "zend_extension=xdebug\n\
xdebug.mode=debug\n\
xdebug.start_with_request=yes\n\
xdebug.client_host=host.docker.internal\n\
xdebug.client_port=9003\n\
" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html