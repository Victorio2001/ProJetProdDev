FROM alpine:latest
LABEL authors="B. GOURLEZ + E. LAVAZAIS"
LABEL description="Dockerfile pour le projet bibliolen (Ngix + PHP-FPM)"

ENV TZ=Europe/Paris
ENV PHP_INI_DIR /etc/php83

# REGION Installation des mises à jour et paquets nécessaires

RUN apk update && apk upgrade

RUN apk add --no-cache \
  curl \
  vim \
  nginx \
  php83 \
  php83-ctype \
  php83-curl \
  php83-dom \
  php83-fileinfo \
  php83-fpm \
  php83-gd \
  php83-intl \
  php83-mbstring \
  php83-mysqli \
  php83-opcache \
  php83-openssl \
  php83-phar \
  php83-pdo \
  php83-pdo_pgsql \
  php83-pgsql \
  php83-session \
  php83-tokenizer \
  php83-xml \
  php83-xmlreader \
  php83-xmlwriter \
  php83-zip \
  php83-zlib \
  supervisor

# ENDREGION

WORKDIR /var/www/html

# REGION configureation serveur Web + PHP

COPY .docker/nginx.conf /etc/nginx/nginx.conf

RUN if [ ! -d /etc/nginx/conf.d ]; then mkdir /etc/nginx/conf.d; fi
COPY .docker/nginx-default.conf /etc/nginx/conf.d/default.conf

COPY .docker/php-fpm.conf ${PHP_INI_DIR}/php-fpm.d/www.conf
COPY .docker/php.ini ${PHP_INI_DIR}/conf.d/custom.ini

RUN ln -s /usr/bin/php83 /usr/bin/php

# Supervisord pour gérer les services
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ENDREGION

# REGION Configuration de l'envoi de mail

RUN chmod 777 /usr/sbin/sendmail

#RUN echo "FromLineOverride=YES" >> /etc/ssmtp/ssmtp.conf
#RUN echo 'sendmail_path = "/usr/sbin/ssmtp -t"' > ${PHP_INI_DIR}/conf.d/sendmail.ini

# ENDREGION

# setup composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# REGION Configuration des droits
RUN chown -R nobody.nobody /var/www/html /run /var/lib/nginx /var/log/nginx

USER nobody

# ENDREGION

# REGION Configuration de l'application
COPY --chown=nobody . /var/www/html

RUN composer update
RUN composer install --no-dev --optimize-autoloader

# ENDREGION

# REGION Nettoyage

RUN if [ -d .docker ]; then rm -rf .docker; fi
RUN if [ -d .git ]; then rm -rf .git; fi
RUN if [ -f .gitignore ]; then rm .gitignore; fi
RUN if [ -f .gitattributes ]; then rm .gitattributes; fi
RUN if [ -f .editorconfig ]; then rm .editorconfig; fi
RUN if [ -f .env.example ]; then rm .env.example; fi

# ENDREGION

# Expose the port nginx is reachable on
EXPOSE 8080

# start nginx and php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

HEALTHCHECK --interval=5s --timeout=15s \
  CMD curl --silent --fail http://localhost:8080/healthcheck
