FROM php:8.1-fpm

ARG UID
ARG GID
ARG APPLICATION_NAME

RUN apt-get update && apt-get install -y --no-install-recommends \
  zlib1g-dev libzip-dev zip unzip  \
# Packages for mongodb
  libcurl4-openssl-dev pkg-config libssl-dev \
  && rm -r /var/lib/apt/lists/* \
# Create user & group \
  && useradd --uid $UID --user-group paybybank \
  && chown -R $GID:$GID /var/www/html && chmod -R 775 /var/www/html \
  && docker-php-ext-install zip \
# Xdebug installation
  && pecl install xdebug && docker-php-ext-enable xdebug \
# Mongodb installation
  && pecl install mongodb && echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongodb.ini \

# Copy xdebug configuration
COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/

# Composer installation
RUN curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer

WORKDIR /var/www/html

USER paybybank