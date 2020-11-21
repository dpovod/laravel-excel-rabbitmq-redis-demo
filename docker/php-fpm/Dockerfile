FROM php:7.3-fpm

# Install Dependences
RUN apt-get update \
    && apt-get upgrade -yq \
    && apt-get install -yq apt-utils \
    libmcrypt-dev libxml2-dev \
    librabbitmq4 librabbitmq-dev \
    libssl-dev

# Install Dependences
#RUN apt-get update \
#    && apt-get upgrade -yq \
#    && apt-get install -yq apt-utils \
#    libpq-dev libmcrypt-dev libreadline-dev libxslt-dev libxml2-dev libicu-dev zlib1g-dev libmemcached-dev curl \
#    librabbitmq4 librabbitmq-dev \
#    libssl-dev \
#    git \
#    libtidy-dev libtidy5deb1 libbz2-dev libfontconfig \
#    p7zip-full

RUN apt-get update \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install ctype \
    && docker-php-ext-install json \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pdo

RUN apt-get update \
    && pecl channel-update pecl.php.net \
    && pecl install amqp-1.10.2 \
    && docker-php-ext-enable amqp \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer --version=2.0.0 \
    && php -r "unlink('composer-setup.php');"

USER www-data

COPY entrypoint.sh /entrypoint.sh

ENTRYPOINT /bin/bash -c "/bin/bash /entrypoint.sh"
