#!/bin/bash

# Define GitHub file URLs
GITHUB_REPO="https://raw.githubusercontent.com/storjdashboard/storjdashboard_docker/refs/heads/main"
FILES=("docker-compose.yml" "Dockerfile" "init.sh" "nginx.conf")

# Download the latest files from GitHub
echo "🔄 Downloading latest files from GitHub..."
for file in "${FILES[@]}"; do
    echo "📥 Fetching $file..."
    curl -s -o "$file" "$GITHUB_REPO/$file"
    if [ $? -ne 0 ]; then
        echo "❌ Failed to download $file"
        exit 1
    fi
done
echo "✅ All files downloaded successfully."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "🔍 Docker Compose not found. Installing..."

    # Install Docker Compose
    sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    
    # Give execute permissions
    sudo chmod +x /usr/local/bin/docker-compose
    
    # Verify installation
    if ! command -v docker-compose &> /dev/null; then
        echo "❌ Docker Compose installation failed."
        exit 1
    fi
    echo "✅ Docker Compose installed successfully."
fi

# Prompt for user inputs
echo "------------------------------"
echo "Please enter your configuration details."

# Get the values for NGINX Hostname, Port, and Internal Storj Dashboard details
read -p "Enter External Hostname (e.g., storj.mydns.net): " hostname
read -p "Enter External Port (default: 28000): " hostname_port
hostname_port=${hostname_port:-28000}  # Default to 28000 if no port is provided
read -p "Enter Internal Storj Dashboard IP:PORT (e.g., 192.168.0.100:14002): " storj_dashboard_ip_port
read -p "Enter Auth Code (e.g., CODE): " auth_code

# Create the config.php file for internal Storj dashboard
config_file="/var/www/$hostname/config.php"
echo "<?php \$ip=\"$storj_dashboard_ip_port\"; \$auth=\"$auth_code\";?>" > $config_file
echo "✅ config.php file created at $config_file."

# Dynamically update the NGINX config
nginx_config="/etc/nginx/sites-available/$hostname"
echo "Updating NGINX configuration..."

# Create a new NGINX config file for external access
sudo bash -c "cat > $nginx_config" <<EOF
server {
    listen $hostname_port;
    server_name $hostname;
    root /var/www/$hostname;
    index index.php;
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF

# Enable the site
sudo ln -s /etc/nginx/sites-available/$hostname /etc/nginx/sites-enabled/

# Test and restart NGINX
sudo nginx -t
sudo systemctl restart nginx

# Build and run the container
echo "🚀 Starting Docker Compose..."
docker-compose up -d --build

# Show running containers
docker ps

# Show user prompt for further instructions
echo '------------------------------'
echo "Your NGINX is now configured with PHP $PHP_VERSION"
echo "Visit your site: http://$hostname:$hostname_port"
echo '------------------------------'
