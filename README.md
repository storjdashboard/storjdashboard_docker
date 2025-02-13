# StorjDashboard Docker Control Panel

## Overview

`storjdashboard_docker` is a Docker container designed to simplify the management of multiple Storj nodes. It provides a user-friendly Bootstrap 5 UI that enables easy setup and configuration for integration with the StorjDashboard portal.

## Features

- Simple UI to manage multiple nodes
- Automatic configuration updates
- One-click regeneration of `nginx.conf` and `docker-compose.yml`
- Easy-to-use control panel running on port 80

## One line install

   ```bash
curl -sL $(curl -s https://api.github.com/repos/storjdashboard/storjdashboard_docker/releases/latest | grep "browser_download_url.*zip" | cut -d '"' -f 4) -o storjdashboard_docker.zip && unzip storjdashboard_docker.zip && cd storjdashboard_docker && bash controlpanel.sh
   ```

## Installation

1. **Download the latest release**

   - [Latest Release]([https://github.com/storjdashboard/storjdashboard_docker/releases/latest]) 

2. **Launch the control panel**

   ```bash
   bash controlpanel.sh
   ```

3. The control panel will be accessible via port `80` on your server.

## Usage

1. **Add a server**

   - Click the "Add Server" button in the UI.
   - Fill in the required details based on your StorjDashboard portal configuration.

2. **Update Files**

   - Ensure the necessary files are downloaded to the specified location.
   - Update the configuration with relevant details.

3. **Regenerate Configuration**

   - Click the **Regenerate Nginx** and **Regenerate Docker-Compose** buttons.

4. **Launch the Servers**

   ```bash
   bash servers.sh
   ```

## Security Considerations

- **Do not expose port 80** to the public internet.
- Ensure that only the selected ports for your servers are accessible externally to integrate with the StorjDashboard portal.

## Support

For issues and feature requests, please visit the [StorjDashboard GitHub Repository]([https://github.com/storjdashboard/storjdashboard_docker/issues])

---

© 2025 StorjDashboard. All rights reserved.

