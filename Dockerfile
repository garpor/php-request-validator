FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG UID=1000
ARG GID=1000

RUN addgroup --gid ${GID} appgroup && \
    adduser --disabled-password --gecos "" --uid ${UID} --gid ${GID} appuser

WORKDIR /app
RUN chown -R appuser:appgroup /app

COPY --chown=appuser:appgroup composer.json composer.lock ./

RUN composer install --no-interaction --prefer-dist

COPY --chown=appuser:appgroup . .

ENV PATH="/app/vendor/bin:${PATH}"

USER appuser

CMD ["tail", "-f", "/dev/null"]