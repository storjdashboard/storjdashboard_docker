<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("includes/code.php");

function updateFiles($volume) {
    global $errorLogFile;

    // Check if the directory is writable
    $serverDir = "servers/" . $volume;
    if (!is_writable($serverDir)) {
        logAction("Error: Directory $serverDir is not writable");
        echo "Error: The directory $serverDir is not writable. Please check permissions.";
        return;
    }

    $files = [
        "index.php" => "https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/index.php",
        "daily.php" => "https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/daily.php",
        "pay.php" => "https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/pay.php",
        "audit.php" => "https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/audit.php",
        "pay_history.php" => "https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/pay_history.php"
    ];

    // Get the directory from the POST variable
    if (isset($_POST['directory']) && !empty($_POST['directory'])) {
        $directory = "servers/" . $_POST['directory'];
        $directory = rtrim(realpath($directory), '/'); // Ensure it's an absolute path
        
        if (!$directory || !is_dir($directory)) {
            logAction("Error: Invalid directory $directory");
            echo "Error: Invalid directory.";
            return;
        }
    } else {
        logAction("Error: Directory not specified");
        echo "Error: Directory not specified.";
        return;
    }

    // Download each file using cURL instead of wget
    foreach ($files as $file => $url) {
        $savePath = "$directory/$file";

        // Initialize cURL
        $ch = curl_init($url);
        if (!$ch) {
            file_put_contents($errorLogFile, "Error: Failed to initialize cURL for $url\n", FILE_APPEND);
            continue;
        }

        // Open file for writing
        $fp = fopen($savePath, 'w');
        if (!$fp) {
            file_put_contents($errorLogFile, "Error: Failed to open file for writing: $savePath\n", FILE_APPEND);
            curl_close($ch);
            continue;
        }

        // Set cURL options
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout after 30 seconds
        curl_setopt($ch, CURLOPT_FAILONERROR, true); // Fail on HTTP errors

        // Execute download
        $success = curl_exec($ch);
        if (!$success) {
            $error = curl_error($ch);
            file_put_contents($errorLogFile, "Error: Failed to download $url - $error\n", FILE_APPEND);
        }

        // Cleanup
        curl_close($ch);
        fclose($fp);
    }

    logAction("Updated files for directory: $directory");
    header("Location: ./");
    exit;
}

// Call the function if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateFiles($_POST['directory']);
} else {
    echo "No POST request received.";
    exit;
}
?>
