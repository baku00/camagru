FROM debian:latest

RUN apt-get update && apt-get install -y apache2
COPY ./virtual-host.conf /etc/apache2/sites-available/000-default.conf
RUN mkdir -p /etc/ssl/certs/ /etc/ssl/private/

RUN a2enmod ssl && a2enmod rewrite
RUN apt install php libapache2-mod-php php-cli php-cgi php-pgsql php-gd -y
COPY ./configure.sh /configure.sh

RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -y openssl
RUN apt install -y dos2unix

# COPY ./sources /var/www/html
# COPY ./sources/.env /var/www/html/.env

ENTRYPOINT [ "sh", "/configure.sh" ]
