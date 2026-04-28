FROM php:8.5-apache

# 1. Installation des dépendances système nécessaires à la compilation
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Installation de PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# 3. Installation du driver MongoDB 
RUN pecl install mongodb && docker-php-ext-enable mongodb

# 4. Configuration Apache
RUN a2dismod mpm_event || true
RUN a2enmod mpm_prefork
RUN a2enmod rewrite

# 5. Copie du code
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# 6. Port dynamique Heroku (CMD en format JSON pour éviter le warning)
CMD ["sh", "-c", "sed -i \"s/80/$PORT/g\" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && apache2-foreground"]