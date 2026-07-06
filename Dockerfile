FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip sqlite3 libsqlite3-dev poppler-utils tesseract-ocr tesseract-ocr-pol \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && cp .env.example .env \
    && mkdir -p database storage/app/public/invoices storage/framework/cache storage/framework/sessions storage/framework/views \
    && touch database/database.sqlite \
    && php artisan key:generate \
    && php artisan migrate --seed

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
