FROM php:8.1-fpm

# set your user name, ex: user=ourname
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd sockets

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable mysqli \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#RUN docker-php-ext-install opcache && \
#    echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
#    echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
#    echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini && \
#    echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini && \
#    echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini && \
#    echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Install redis
#RUN pecl install -o -f redis \
#    &&  rm -rf /tmp/pear \
#    &&  docker-php-ext-enable redis

# Set working directory
WORKDIR /var/www

# Copy custom configurations PHP
COPY /docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

USER $user

CMD ["php-fpm"]