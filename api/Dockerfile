FROM php:8.1-fpm

WORKDIR /var/www/html
ENV APP_ENVIRONMENT=production

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

USER root

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt update -y && apt upgrade -y && \
    apt install -y libmagickwand-dev --no-install-recommends && \
    install-php-extensions intl zip bcmath pdo_pgsql pcntl \
    gmp opcache uuid amqp json_post imagick \
    igbinary ast redis mongodb apcu \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

COPY ./docker/config/www.conf /usr/local/etc/php-fpm.d/www.conf


RUN composer install --optimize-autoloader --apcu-autoloader --ignore-platform-reqs

ARG WWWGROUP=1000
ARG WWWUSER=1000
ARG NODE_VERSION=18
ARG POSTGRES_VERSION=14

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev dnsutils \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && apt-get -y autoremove \
    && apt-get clean \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && groupadd --force -g $WWWGROUP root \
    && useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER root

COPY ./docker/config/x-debug.ini /usr/local/etc/php/conf.d/xdebug.ini

EXPOSE 9000

CMD ["php-fpm"]