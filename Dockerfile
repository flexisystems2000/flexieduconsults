FROM php:8.2-apache

RUN a2enmod rewrite headers

# Allow .htaccess (needed for clean /news/... URLs)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf || true

RUN printf '%s\n' \
    '<Directory /var/www/html>' \
    '    Options Indexes FollowSymLinks' \
    '    AllowOverride All' \
    '    Require all granted' \
    '</Directory>' \
    > /etc/apache2/conf-available/allow-override.conf \
    && a2enconf allow-override

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

RUN echo 'DirectoryIndex index.php index.html' > /etc/apache2/conf-available/directoryindex.conf \
    && a2enconf directoryindex

EXPOSE 80
