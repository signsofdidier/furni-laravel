#!/bin/sh
set -e

cd /var/www/html

# Aiven vereist SSL: het CA-certificaat komt als tekst uit de env var DB_CA_CERT
if [ -n "$DB_CA_CERT" ]; then
    printf '%s\n' "$DB_CA_CERT" > /etc/ssl/certs/db-ca.pem
    export MYSQL_ATTR_SSL_CA=/etc/ssl/certs/db-ca.pem
fi

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

php artisan storage:link --force || true

php artisan migrate --force
php artisan app:seed-if-empty

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Artisan draait als root; Apache (www-data) moet kunnen schrijven in storage en cache
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
