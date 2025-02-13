<?php
// Configuration
$serversFile = 'servers.json';
$dockerComposeFile = '../../d-config/docker-compose.yml';
$nginxConfigFile = '../../d-config/nginx.conf';
$logFile = 'includes/logs/portal.log';
$errorLogFile = 'includes/logs/error.log';
$dir = dirname(__FILE__);

// Load existing servers
$servers = file_exists($serversFile) ? json_decode(file_get_contents($serversFile), true) : [];

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_server') {
        $name = $_POST['name'] ?? '';
        $port = $_POST['port'] ?? '';
        $volume = $_POST['volume'] ?? '';
        if ($name && $port && $volume) {
            $servers[$name] = ['port' => $port, 'volume' => $volume];
            file_put_contents($serversFile, json_encode($servers, JSON_PRETTY_PRINT));
			$targetDir = realpath("$dir/../servers/$volume");
			echo $targetDir;
			createWritableDirectory("$targetDir");
            logAction("Added server: $name (Port: $port, Volume: $volume)");
        }
    } elseif ($action === 'remove_server') {
        $name = $_POST['name'] ?? '';
        if (isset($servers[$name])) {
            unset($servers[$name]);
            file_put_contents($serversFile, json_encode($servers, JSON_PRETTY_PRINT));
            logAction("Removed server: $name");
        }
    } elseif ($action === 'regenerate_docker') {
        regenerateDockerCompose($servers);
    } elseif ($action === 'regenerate_nginx') {
        regenerateNginxConfig($servers);
    }
//    } elseif ($action === 'start_docker') {
//       shell_exec("docker-compose up -d 2>&1 | tee -a $errorLogFile");
//        logAction("Started Docker containers");
//    } elseif ($action === 'stop_docker') {
//        shell_exec("docker-compose down 2>&1 | tee -a $errorLogFile");
//        logAction("Stopped Docker containers");
//    } elseif ($action === 'restart_docker') {
//        shell_exec("docker-compose down && docker-compose up -d 2>&1 | tee -a $errorLogFile");
//        logAction("Restarted Docker containers");
//    }
}

// Functions
function createWritableDirectory($directory) {
    // Check if the directory already exists
    if (!file_exists($directory)) {
        if (!mkdir($directory, 0777, true)) {
            $error = error_get_last();
            die("Error creating directory '$directory': " . $error['message'] . "\n");
        } else {
           // echo "Directory '$directory' created successfully.\n";
        }
    } else {
        //echo "Directory '$directory' already exists.\n";
    }

    // Attempt to set writable permissions
    if (!chmod($directory, 0777)) {
        $error = error_get_last();
        die("Error setting permissions on '$directory': " . $error['message'] . "\n");
    } else {
       //echo "Permissions set to 777 for '$directory'.\n";
    }
}

function regenerateDockerCompose($servers) {
    global $dockerComposeFile, $errorLogFile, $dir;
$content = "version: '3.5'\n\nservices:\n  nginx:\n    image: nginx:latest\n    container_name: storjdashboard_nginx\n    ports:";
foreach ($servers as $name => $server) {
    $content .= "\n      - \"{$server['port']}:{$server['port']}\"";
}
$content .= "\n    volumes:\n      - ./nginx.conf:/etc/nginx/nginx.conf:ro\n      - ./:/var/www/html\n    restart: always\n\n  php:\n    image: php:fpm\n    container_name: storjdashboard_php\n    volumes:\n      - ./:/var/www/html\n    restart: always\n    entrypoint: [\"/bin/bash\", \"-c\", \"cron && php-fpm\"]";
file_put_contents($dockerComposeFile, $content);
//    shell_exec("docker-compose down 2>&1 | tee -a $errorLogFile");
//    shell_exec("docker-compose up -d 2>&1 | tee -a $errorLogFile");
    logAction("Regenerated docker-compose.yml");
}

function regenerateNginxConfig($servers) {
    global $nginxConfigFile, $errorLogFile, $dir;
    $content = "events {}\nhttp {\n";
    foreach ($servers as $name => $server) {
        $content .= "    server {\n        listen {$server['port']};\n#        server_name $name;\n        root /var/www/html/servers/{$server['volume']};\n        index index.php index.html;\n        location / {\n            try_files \$uri \$uri/ /index.php?\$query_string;\n        }\n        location ~ \\\.php$ {\n            include fastcgi_params;\n            fastcgi_pass php:9000;\n            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;\n        }\n    }\n";
    }
    $content .= "}\n";
    file_put_contents($nginxConfigFile, $content);
//    shell_exec("systemctl restart nginx 2>&1 | tee -a $errorLogFile");
    logAction("Regenerated nginx.conf");
}

function logAction($message) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}
?>
