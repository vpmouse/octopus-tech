FROM phpdockerio/php:8.3-fpm
WORKDIR "/var/www/html"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
    git \
    libpq-dev \
    php8.3-memcached \
    php8.3-memcache \
    php8.3-mbstring \
    php8.3-common\
    php8.3-mysql \
    php8.3-pgsql \
    php8.3-intl \
    php8.3-xdebug \
    php8.3-interbase \
    php8.3-soap \
    php8.3-gd \
    php8.3-imagick \
    php8.3-redis \
    php8.3-opcache \
    php8.3-zip \
    php8.3-dom \
    php8.3-xml \
    php-pear php-dev libmcrypt-dev gcc make autoconf libc-dev pkg-config \
    && apt-get install libsodium-dev libsodium23 \
    && pecl install libsodium \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

