FROM php:8.4-fpm

ARG user
ARG uid

# Instala dependências
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    zip unzip git curl supervisor nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cria o usuário com mesmo UID do host
RUN useradd -G www-data,root -u ${uid} -d /home/${user} ${user} && \
    mkdir -p /home/${user} && chown -R ${user}:${user} /home/${user}

# Prepara diretórios de log e body temporário com permissões corretas
RUN mkdir -p /var/lib/nginx/body /var/lib/nginx/proxy /tmp/nginx_body /tmp/nginx-logs && \
    touch /tmp/nginx.pid && \
    chown -R ${user}:${user} /var/lib/nginx /tmp/nginx_body /tmp/nginx-logs /tmp/nginx.pid


WORKDIR /var/www

# Copia config do Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Define usuário final
USER ${user}

CMD php-fpm -D && nginx -g "daemon off;"
EXPOSE 80 443
