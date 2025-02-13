<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("includes/code.php");
?>
<?php

function updateFiles($volume) {
    global $errorLogFile;
    if (!is_writable("servers/".$volume)) {
        logAction("Error: Directory servers/$volume is not writable");
        echo "Error: The directory servers/$volume is not writable. Please check permissions.";
        return;
    }

    $files = [
        "index.php" => "https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/index.php",
        "daily.php" => "https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/daily.php",
        "pay.php" => "https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/pay.php",
        "audit.php" => "https://raw.githubusercontent.com/storjdashboard/storjdashboard/main/public/audit.php"
    ];

    // Get the directory from the POST variable
    if (isset($_POST['directory']) && !empty($_POST['directory'])) {
        $directory = "servers/".$_POST['directory'];
        // Sanitize and validate the directory path
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

    // Download each file
    foreach ($files as $file => $url) {
        $output = shell_exec("wget -O $directory/$file $url 2>&1");
        file_put_contents($errorLogFile, $output, FILE_APPEND);
    }
    logAction("Updated files for directory: $directory");
	header("location: ./");
}

// Call the function with the directory passed as $_POST['directory']
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateFiles($_POST['directory']);
}else{
	echo "No POST"; exit;
}
?>
