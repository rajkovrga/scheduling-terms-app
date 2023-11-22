FROM php:8.2-fpm as base

WORKDIR /var/www/html
ENV APP_ENVIRONMENT=production

ARG NODE_VERSION=18
ARG POSTGRES_VERSION=14

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /var/www/html


ARG UID=1000
ARG GID=1000

RUN groupadd -g $GID -o test
RUN useradd -m -u $UID -g $GID -o -s /bin/bash test

RUN chown -R test:test /var/www

RUN usermod -u 1000 test
RUN groupmod -g 1000 test

COPY --chown=root:root . .

RUN chmod +x run.sh
RUN chmod 777 /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY ./docker/config/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN apt update -y && apt upgrade -y \
    && install-php-extensions intl zip bcmath pgsql pdo_pgsql pcntl gmp opcache uuid amqp json_post igbinary ast redis apcu \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN pecl install xdebug \
        && docker-php-ext-enable xdebug

COPY docker/config/www.conf /usr/local/etc/php/conf.d/xdebug.ini

EXPOSE 9000

CMD ["php-fpm", "-R"]

FROM base as development

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin redis libpng-dev dnsutils \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && apt-get -y autoremove \
    && apt-get clean

ENV APP_ENVIRONMENT=development