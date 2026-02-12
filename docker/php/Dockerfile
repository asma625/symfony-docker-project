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
    # on definit le dossier home de l'utilisateur sur le container php pour eviter des problemes de droits, le fait de rajouter
    # le user dans le docker-compose a soulever des problemes de droits d'ecriture dans le dossier home par defaut /root
RUN mkdir -p /home/app \
 && chown -R 1000:1000 /home/app
ENV HOME=/home/app

WORKDIR /var/www