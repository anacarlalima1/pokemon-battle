FROM php:8.3-cli-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    zip \
    icu-dev \
    oniguruma-dev \
    libzip-dev

RUN docker-php-ext-install \
    pdo \
    mbstring \
    intl \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/entrypoint.sh"]