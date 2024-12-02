# Utilisation de l'image PHP 7.4 FPM
FROM php:7.4-fpm

# Mise à jour des paquets et installation des dépendances
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev

# Configuration et installation de l'extension GD pour PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_pgsql

# Copie de l'ensemble des fichiers du contexte de construction dans le conteneur
COPY . .

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installation des dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader

# Définition du répertoire de travail dans le conteneur
WORKDIR /var/www/html

RUN npm install
# Commande pour générer la clé de l'application Laravel
RUN php artisan key:generate

# Commande par défaut pour lancer le serveur artisan
CMD php artisan serve --host=0.0.0.0 --port=8000
