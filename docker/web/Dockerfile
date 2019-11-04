FROM php:7.1.8-apache
COPY /src /srv.app/ 

RUN a2enmod headers \
    && a2enmod expires \
    && service apache2 restart
