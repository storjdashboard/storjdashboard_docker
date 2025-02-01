FROM php:fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y wget cron

# Create a script to update files
RUN echo '#!/bin/bash\n'\
    'wget -O /var/www/html/index.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/index.php\n'\
    'wget -O /var/www/html/daily.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/daily.php\n'\
    'wget -O /var/www/html/pay.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/pay.php\n'\
    'wget -O /var/www/html/audit.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/audit.php\n'\
    'echo \"<?php \\$ip=\\\"${STORJ_DASHBOARD_IP_PORT}\\\"; \\$code=\\\"${AUTH_CODE}\\\"; ?>\" > /var/www/html/config.php'\
    > /update_files.sh && chmod +x /update_files.sh

# Schedule cron job
RUN echo \"0 */6 * * * root /update_files.sh\" > /etc/cron.d/storj_update \n\
    && chmod 0644 /etc/cron.d/storj_update \n\
    && crontab /etc/cron.d/storj_update

# Start cron and PHP-FPM
CMD cron && php-fpm
