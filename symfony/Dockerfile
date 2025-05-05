FROM php:8.2-cli

# Install required dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev libpq-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Install Symfony CLI (updated path)
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Set working directory
WORKDIR /var/www/symfony

# Expose port
EXPOSE 8000

# Start Symfony server
#CMD ["symfony", "server:start", "--no-tls", "--dir=/var/www/symfony"]
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
#CMD ["symfony", "server:start", "0.0.0.0:8000"]


RUN docker-php-ext-install pdo pdo_pgsql

