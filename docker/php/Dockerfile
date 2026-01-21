FROM php:8.3-fpm-alpine

# Install dependencies
#RUN apk --no-cache add curl git wget bash dpkg equivalents

RUN apk --no-cache add \
    curl \
    git \
    wget \
    bash \
    dpkg

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
#exécute la commande suivante UNIQUEMENT si la précédente a réussi  ce que veut dire &&
#le install-php-extensions permet d'installer des extensions PHP facilement est un script bash
#graphics draw gd est une extension PHP pour le traitement d’images.
RUN chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions \
        opcache \
        iconv \
        soap \
        zip \
        intl \
        fileinfo \
        pdo \
        redis \
        mysqli \
        pdo_mysql \
        gd
# Install Composer and Symfony CLI
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www