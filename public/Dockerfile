# Usa una imagen oficial de PHP con soporte para FPM (FastCGI Process Manager)
FROM php:8.1-cli

# Establece el directorio de trabajo dentro del contenedor
WORKDIR /app

# Copia todos los archivos de tu proyecto al contenedor
COPY . /app

# Instala dependencias adicionales si las necesitas (por ejemplo, extensiones de PHP)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Expone el puerto 8000 (el que usas en php -S)
EXPOSE 8000

# Comando para iniciar el servidor integrado de PHP
CMD ["php", "-S", "0.0.0.0:8000"]