FROM tutum/apache-php
RUN a2enmod headers \
    && a2enmod expires \
    && service apache2 restart
#EXPOSE 80