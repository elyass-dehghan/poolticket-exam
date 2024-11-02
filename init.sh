#!/bin/bash

chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
