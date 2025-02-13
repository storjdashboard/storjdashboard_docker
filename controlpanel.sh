#!/bin/bash

docker-compose -f "/controlpanel_docker/controlpanel/docker-compose.yml" down
docker-compose -f "/controlpanel_docker/controlpanel/docker-compose.yml" up -d
