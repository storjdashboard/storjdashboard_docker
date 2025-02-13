#!/bin/bash
mkdir -p www
wget -O ./www/index.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/index.php
wget -O ./www/daily.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/daily.php
wget -O ./www/pay.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/pay.php
wget -O ./www/audit.php https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/audit.php
