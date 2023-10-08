FROM php:8.2-fpm as base

WORKDIR /var/www/html
ENV APP_ENVIRONMENT=production

ARG NODE_VERSION=18
ARG POSTGRES_VERSION=14

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

USER root

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt update -y && apt upgrade -y \
    && install-php-extensions intl zip bcmath pdo_pgsql pcntl gmp opcache uuid amqp json_post igbinary ast redis apcu \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

COPY docker/config/www.conf /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000

CMD ["php-fpm", "-R"]

FROM base as development

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin redis libpng-dev dnsutils \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && apt-get -y autoremove \
    && apt-get clean \

ENV APP_ENVIRONMENT=development