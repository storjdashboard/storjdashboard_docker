#!/bin/bash

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

# Build and run the container
echo "🚀 Starting Docker Compose..."
docker-compose up -d --build

# Show running containers
docker ps
