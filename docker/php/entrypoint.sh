#!/bin/sh
chmod -R 777 /var/www/storage /var/www/bootstrap/cache
exec "$@"