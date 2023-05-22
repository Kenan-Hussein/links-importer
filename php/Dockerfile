FROM php:8.2.0-fpm

RUN apt-get update && apt-get install -y zlib1g-dev git libicu-dev libzip-dev zip librabbitmq-dev libxslt1-dev libssh-dev\
    && docker-php-ext-install intl opcache pdo pdo_mysql xsl\
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && pecl install amqp \
    && docker-php-ext-enable amqp\
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && apt-get remove -y libxslt1-dev icu-devtools libicu-dev libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/tokyo/backend

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown -R www-data:www-data /var && chmod -R g+rw /var