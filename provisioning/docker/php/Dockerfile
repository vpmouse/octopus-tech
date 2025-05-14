FROM php:8.3-fpm
WORKDIR "/var/www/html"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        git \
        libpq-dev \
        libmemcached-dev \
        libz-dev \
        libzip-dev \
        libxml2-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libmagickwand-dev \
        libonig-dev \
        libsodium-dev \
        libssl-dev \
        libmcrypt-dev \
        libicu-dev \
        gcc \
        make \
        autoconf \
        libc-dev \
        pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        intl \
        mbstring \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        soap \
        xml \
        zip \
        sockets \
    && pecl install \
        memcached \
        redis \
        imagick \
        xdebug \
    && docker-php-ext-enable \
        memcached \
        redis \
        imagick \
        xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Добавление пользовательских конфигураций PHP
COPY ./php.ini /usr/local/etc/php/conf.d/90-php.ini
COPY ./php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini

# Настройка пользователя www-data
RUN usermod -u 1000 www-data