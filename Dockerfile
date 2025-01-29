# Use Ubuntu as the base image
FROM ubuntu:latest

# Install dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    php-fpm \
    wget \
    git \
    && apt-get clean

# Ensure startup script is copied into the container
COPY init.sh /init.sh
RUN chmod +x /init.sh

# Copy the Nginx configuration
COPY nginx.conf /etc/nginx/sites-enabled/default

# Expose necessary ports
EXPOSE 80

# Run the startup script
CMD ["/init.sh"]
