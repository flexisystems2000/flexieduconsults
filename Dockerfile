FROM php:8.2-apache

# Enable common Apache bits (optional but useful)
RUN a2enmod rewrite headers

# Copy the whole site into the web root
COPY . /var/www/html/

# Make sure Apache can read everything
RUN chown -R www-data:www-data /var/www/html

# Prefer index.php over index.html
RUN echo 'DirectoryIndex index.php index.html' > /etc/apache2/conf-available/directoryindex.conf \
    && a2enconf directoryindex

EXPOSE 80
