FROM debian:latest

RUN apt-get update && apt-get install -y apache2
COPY ./virtual-host.conf /etc/apache2/sites-available/000-default.conf
RUN mkdir -p /etc/ssl/certs/ /etc/ssl/private/
COPY ./server.crt /etc/ssl/certs/server.crt
COPY ./server.key /etc/ssl/private/server.key
# COPY ./php.ini /etc/php/8.2/apache2/php.ini
RUN a2enmod ssl
RUN service apache2 restart
RUN echo "Hello World" > /var/www/html/index.html
RUN apt install php libapache2-mod-php php-cli php-cgi php-pgsql -y
RUN service apache2 stop; service apache2 start
COPY ./sources/ /var/www/html/

ENTRYPOINT [ "sleep", "infinity" ]