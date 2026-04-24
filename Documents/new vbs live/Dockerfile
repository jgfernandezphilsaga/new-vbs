# Start with a base PHP image with Apache
FROM php:8.1-apache

# Add Bullseye repository and update OpenSSL to version 1.1.x
# RUN echo "deb http://deb.debian.org/debian bullseye main" >> /etc/apt/sources.list \
#     && apt-get update \
#     && apt-get install -y openssl=1.1.1n-1+deb11u4 \
#     && apt-mark hold openssl \
#     && apt-get clean

# Install remaining dependencies and Microsoft ODBC Driver dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libmcrypt-dev \
    unzip \
    libxslt-dev \
    libpq-dev \
    unixodbc-dev \
    netcat-openbsd \
    gnupg2 \
    iputils-ping \
    telnet \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions needed for Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql zip xsl intl bcmath opcache \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install the sqlsrv and pdo_sqlsrv extensions for SQL Server support
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm (for Laravel Mix)
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean

# Install the Laravel Debugbar package
RUN composer require barryvdh/laravel-debugbar --dev

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Set the working directory and copy the Laravel app into the container
WORKDIR /var/www/html
COPY . .

# Set permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 to the host
EXPOSE 80

# Configure the Apache DocumentRoot to point to the Laravel public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Start Apache (final CMD)
CMD ["apache2-foreground"]
