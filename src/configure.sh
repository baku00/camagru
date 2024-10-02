service apache2 restart
rm -rf /var/www/html/index.html
chmod -R 777 /var/www/html
tail -f /dev/null