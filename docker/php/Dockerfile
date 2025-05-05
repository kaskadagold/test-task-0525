FROM php:8.2-fpm

# Установка основных пакетов
RUN apt-get update && apt-get install -y \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip unzip \
        libjpeg62-turbo-dev \
        libfreetype-dev \
        nano

# Установка PHP расширений
RUN docker-php-ext-configure gd \
    && docker-php-ext-install gd
RUN apt-get install -y \
        libzip-dev \
    && docker-php-ext-install zip
RUN pecl install apcu \
    && docker-php-ext-enable apcu
RUN docker-php-ext-install intl opcache bcmath mbstring pcntl
# MySQL
RUN docker-php-ext-install pdo_mysql

# Добавление Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Создание non-root пользователя
ARG USERNAME=www-data
ARG PUID
ARG PGID
RUN groupmod -o -g ${PGID} ${USERNAME} \
    && usermod -o -u ${PUID} -g ${USERNAME} ${USERNAME}

WORKDIR /var/www/html

# Laravel
RUN mkdir -p /var/www/.config/psysh
RUN chmod a+w /var/www/.config/psysh

# Очистка кэша
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

USER ${USERNAME}
