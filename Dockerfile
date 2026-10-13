FROM php:8.3-apache as php-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN apt-get update && \
    apt-get install -y \
        gettext \
        locales \
        netcat-openbsd \
        locales-all && \
    rm -rf /var/lib/apt/lists/*

COPY src/ /var/www/html/
COPY src/images/favicon /var/www/html/
COPY apache2.conf /etc/apache2/apache2.conf

# Copy sitemap generator and entrypoint
COPY generate-sitemap.php /
COPY docker-entrypoint.sh /

RUN chmod +x /docker-entrypoint.sh

# Generate initial sitemap with static pages only during build
RUN php /generate-sitemap.php --static-only

RUN a2enmod rewrite

ENTRYPOINT ["/docker-entrypoint.sh"]