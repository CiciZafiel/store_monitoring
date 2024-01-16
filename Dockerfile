FROM node:18.16.0 as node
FROM php:8.2.4-apache

USER root

WORKDIR /var/www/store_monitoring
# COPY . /var/www/store_monitoring

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

RUN apt-get update

RUN apt-get -y install --fix-missing \
    apt-utils \
    build-essential \
    git \
    curl \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    libbz2-dev \
    locales \
    libmcrypt-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
    jpegoptim optipng pngquant gifsicle \
    unzip 

RUN docker-php-ext-install \
    exif \
    pcntl \
    bcmath \
    ctype \
    curl \
    iconv \
    xml \
    soap \
    pcntl \
    mbstring \
    bz2 \
    zip \
    intl \
    gd \
    pdo \
    pdo_mysql \
    mysqli \
    opcache


RUN docker-php-ext-configure gd --with-freetype --with-jpeg

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./security.conf /etc/apache2/conf-enabled/security.conf
COPY ./bypass.ini /usr/local/etc/php/conf.d/custom_php.ini
COPY ./cache.ini /usr/local/etc/php/conf.d/cache.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.5.5

RUN apt-get install -y gnupg2
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list
RUN apt-get update
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql18
RUN apt-get install -y libgssapi-krb5-2
RUN apt-get install -y --allow-downgrades odbcinst=2.3.7 odbcinst1debian2=2.3.7 unixodbc=2.3.7 unixodbc-dev=2.3.7
RUN pecl install sqlsrv pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv

RUN apt-get update && apt-get -y install cron
COPY crontab /etc/cron.d/vin-cron
RUN chmod 0644 /etc/cron.d/vin-cron
RUN crontab /etc/cron.d/vin-cron
RUN touch /var/log/cron.log

RUN apt-get update && apt-get install -y iputils-ping
RUN a2enmod rewrite


