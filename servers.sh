#!/bin/bash

docker-compose -f "controlpanel_docker/servers/docker-compose.yml" down
docker-compose -f "controlpanel_docker/servers/docker-compose.yml" up -d
