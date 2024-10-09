openssl req -x509 -nodes \
			-out /etc/ssl/certs/server.crt \
			-keyout /etc/ssl/private/server.key \
			-subj "/C=SW/ST=VD/L=Lausanne/O=42/CN=camagru"

service apache2 restart
rm -rf /var/www/html/index.html
chmod -R 777 /var/www/html
tail -f /dev/null