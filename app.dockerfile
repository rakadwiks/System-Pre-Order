FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    # <-- ADD THIS LINE for intl
    libicu-dev \ 
    default-mysql-client \
    # <-- ADD THIS LINE to configure intl
    && docker-php-ext-configure intl \ 
    # <-- ADD intl HERE
    && docker-php-ext-install pdo pdo_mysql zip bcmath intl \
    && rm -rf /var/lib/apt/lists/* # Clean up apt cache to keep image size down

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# You might want to add permissions for the www-data user if your app writes to certain directories
# RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
# RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache
