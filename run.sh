#!/bin/bash

mkdir -p php
cd php

# Define GitHub file URLs
GITHUB_REPO="https://raw.githubusercontent.com/storjdashboard/storjdashboard_docker/refs/heads/main/php"
FILES=("Dockerfile")

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

cd ..

# Define GitHub file URLs
GITHUB_REPO="https://raw.githubusercontent.com/storjdashboard/storjdashboard_docker/refs/heads/main"
FILES=("docker-compose.yml" "nginx.conf" "file_setup.sh" "run.sh")

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

chmod 775 *

# Show user prompt for further instructions
echo '------------------------------'
echo "✅ Setup Complete"
echo ""
echo "✅ Visit your local server IP in a browser to see the control panel."
echo '------------------------------'
