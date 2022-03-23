FROM php:8.1-apache

ARG UID
ARG GID
ARG APPLICATION_NAME

RUN apt-get update && apt-get install -y --no-install-recommends \
  zlib1g-dev libzip-dev zip unzip  \
# Packages for mongodb
  libcurl4-openssl-dev pkg-config libssl-dev \
  && rm -r /var/lib/apt/lists/* \
# Create user & group
  && chown -R $GID:$GID /var/www/html && chmod -R 775 /var/www/html \
  && docker-php-ext-install zip \
# Xdebug installation
  && pecl install xdebug && docker-php-ext-enable xdebug \
  && a2enmod rewrite \
# Mongodb installation
  && pecl install mongodb && echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongodb.ini

RUN usermod -u $UID www-data
RUN groupmod -g $GID www-data

# Copy xdebug configuration
COPY ./ini/xdebug.ini /usr/local/etc/php/conf.d/

# Composer installation
RUN curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer

RUN echo "ServerName $APPLICATION_NAME" >> /etc/apache2/apache2.conf
RUN mkdir /var/www/log && chown www-data:www-data /var/www/log
COPY VirtualHost.conf /etc/apache2/sites-available

# Disable default virtual host
RUN a2dissite 000-default.conf && a2ensite VirtualHost.conf

WORKDIR /var/www/html

USER www-data