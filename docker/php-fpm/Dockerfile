FROM php:7.3-fpm

RUN apt-get update \
    && apt-get upgrade -yq \
    && apt-get install -yq apt-utils \
    libmcrypt-dev libxml2-dev \
    librabbitmq4 librabbitmq-dev \
    libssl-dev libzip-dev zip unzip \
    && apt-get install -yq cron supervisor

RUN apt-get update \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install ctype \
    && docker-php-ext-install json \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pdo \
    && docker-php-ext-install sockets \
    && docker-php-ext-install zip

RUN apt-get update \
    && pecl channel-update pecl.php.net \
    && pecl install amqp-1.10.2 \
    && docker-php-ext-enable amqp \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && pecl install xdebug-2.9.8 \
    && docker-php-ext-enable xdebug \
    && pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer --version=2.0.0 \
    && php -r "unlink('composer-setup.php');"

#RUN apt-get update && apt install -y nodejs npm
RUN curl -sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh \
    && rm -rf /var/lib/apt/lists/* \
    && bash nodesource_setup.sh \
    && apt install -y nodejs \
    && rm nodesource_setup.sh

RUN chmod 0644 -R /etc/cron.d

COPY config/ /

COPY entrypoint.sh /entrypoint.sh

ENTRYPOINT /bin/bash -c "/bin/bash /entrypoint.sh"
