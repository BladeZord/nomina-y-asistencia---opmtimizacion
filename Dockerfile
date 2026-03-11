FROM php:8.2-apache

# Instalar extensiones necesarias (mysqli para MySQL)
RUN apt-get update && apt-get install -y default-mysql-client \
    && docker-php-ext-install mysqli \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*


# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el proyecto al contenedor
COPY . /var/www/html

# Dar permisos razonables al directorio (ajusta si lo necesitas más estricto)
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto HTTP
EXPOSE 80

