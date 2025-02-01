#!/bin/bash

# Prompt to ask if the user wants to create a config.php file
read -p "Do you wish to create a config.php page? (yes/no): " create_config

if [[ "$create_config" == "yes" ]]; then
    # Ask for Storj Dashboard IP and Port
    read -p "Enter the Storj Dashboard IP and Port (e.g., 192.168.1.100:80): " storj_ip_port
    
    # Ask for the Auth Code
    read -p "Enter the Auth Code: " auth_code
    
    # Create the config.php file in the 'www' directory
    mkdir -p www
    echo "<?php \$ip=\"$storj_ip_port\"; \$code=\"$auth_code\"; ?>" > ./www/config.php
    echo "config.php has been created in the 'www' directory."
fi

# Create a script to download the required files to the 'www' directory
echo '#!/bin/bash
mkdir -p www
wget -O ./www/index.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/index.php
wget -O ./www/daily.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/daily.php
wget -O ./www/pay.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/pay.php
wget -O ./www/audit.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/audit.php
' > ./update_files.sh

# Make the script executable
chmod +x ./update_files.sh

echo "update_files.sh has been created. Run it to download the files into the 'www' directory."
