#!/bin/bash

# Ensure necessary directories exist
mkdir -p /var/www/html

# Download latest files
wget -O /var/www/html/index.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/index.php
wget -O /var/www/html/daily.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/daily.php
wget -O /var/www/html/pay.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/pay.php
wget -O /var/www/html/audit.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/audit.php

# Ensure correct permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# Start Nginx and PHP-FPM
service nginx start
service php-fpm start

# Keep the container running
tail -f /dev/null
