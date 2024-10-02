FROM debian:latest

RUN apt-get update && apt-get install -y apache2
COPY ./virtual-host.conf /etc/apache2/sites-available/000-default.conf
RUN mkdir -p /etc/ssl/certs/ /etc/ssl/private/
COPY ./server.crt /etc/ssl/certs/server.crt
COPY ./server.key /etc/ssl/private/server.key
RUN a2enmod ssl && a2enmod rewrite
RUN service apache2 restart
RUN apt install php libapache2-mod-php php-cli php-cgi php-pgsql php-gd -y
RUN service apache2 stop; service apache2 start
COPY ./configure.sh /configure.sh
COPY ./sources /var/www/html
COPY ./sources/.env /var/www/html/.env

ENTRYPOINT [ "sh", "/configure.sh" ]
