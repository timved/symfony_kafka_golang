FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libonig-dev zlib1g-dev \
    autoconf make gcc g++ pkg-config bash curl \
    && docker-php-ext-install pdo pdo_mysql zip

# Ставим Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Клонируем Xdebug с GitHub и собираем его вручную (вариант для свежего PHP)
RUN git clone https://github.com/xdebug/xdebug.git /tmp/xdebug \
    && cd /tmp/xdebug \
    && phpize \
    && ./configure \
    && make && make install \
    && cp modules/xdebug.so /usr/local/lib/php/extensions/$(php -r 'echo php_ini_loaded_file();' | xargs -I{} dirname {} | xargs -I{} basename {})/xdebug.so \
    && echo "zend_extension=/usr/local/lib/php/extensions/$(php -r 'echo php_ini_loaded_file();' | xargs -I{} dirname {} | xargs -I{} basename {})/xdebug.so" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && cd / && rm -rf /tmp/xdebug

# Конфигурируем Xdebug (настройки под докер + популярные IDE)
RUN echo "xdebug.mode=debug\n\
xdebug.start_with_request = yes\n\
xdebug.client_host = host.docker.internal\n\
xdebug.client_port = 9003\n\
" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www/html