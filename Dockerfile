FROM php:8.2-apache

# Instalar dependencias del sistema, git y extensión mysqli
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    && docker-php-ext-install mysqli \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Directorio de trabajo
WORKDIR /var/www/html

# Clonar el proyecto
RUN git clone https://github.com/BladeZord/nomina-y-asistencia---opmtimizacion.git /var/www/html

# Permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto HTTP
EXPOSE 80