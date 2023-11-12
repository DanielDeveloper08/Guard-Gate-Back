FROM php:8.1-apache
RUN echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'upload_max_filesize = 40M' >> /usr/local/etc/php/conf.d/docker-php-uploads.ini;
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN a2enmod rewrite
WORKDIR /var/www
COPY ./ /var/www
RUN rm -r /var/www/html \
    && mv /var/www/public /var/www/html \
    && chmod -R 0777 /var/www/storage/
RUN composer install --ignore-platform-reqs
