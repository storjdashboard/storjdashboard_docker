FROM php:fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y wget cron

# Start cron and PHP-FPM
CMD cron && php-fpm
