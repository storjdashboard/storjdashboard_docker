<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get parameters from URL
$server = isset($_GET['server']) ? $_GET['server'] : '';
$volume = isset($_GET['volume']) ? $_GET['volume'] : '';
$port = isset($_GET['port']) ? $_GET['port'] : '';

if (!$server || !$volume || !$port) {
    die("Invalid parameters.");
}

// Ensure volume directory exists
if (!is_dir($volume)) {
    mkdir($volume, 0777, true);
}

// Config file path inside volume
$configFile = "$volume/config.php";

// Default values
$ip = "192.168.250.1:14002";
$auth = "abcDEfg123456789";

// Attempt to read config if file exists
if (file_exists($configFile) && is_readable($configFile)) {
    include($configFile);
}

// Check if writable (or can be created)
$fileExists = file_exists($configFile);
$fileWritable = is_writable($configFile) || !$fileExists;

// Handle "Fix" action: Attempt to create or set writable
if (isset($_GET['fix']) && !$fileWritable) {
    // Try creating or updating permissions
    if (!$fileExists) {
        $defaultConfig = "<?php\n\$ip=\"{$ip}\";\n\$auth=\"{$auth}\";\n?>";
        file_put_contents($configFile, $defaultConfig);
    }
    chmod($configFile, 0666); // Ensure writable permissions

    // Redirect back to clear GET parameters
    header("Location: config_editor.php?server=" . urlencode($server) . "&volume=" . urlencode($volume) . "&port=" . urlencode($port));
    exit;
}

// Handle form submission to save config
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $fileWritable) {
    $ip = $_POST['ip'];
    $auth = $_POST['auth'];

    // Generate config.php content with proper closing tag
    $configContent = "<?php\n\$ip=\"{$ip}\";\n\$auth=\"{$auth}\";\n?>";

    // Save the file
    file_put_contents($configFile, $configContent);

    // Redirect back to homepage after saving
    header("Location: ./");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Config - <?= htmlspecialchars($server) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between">
            <h1>Edit Config - <?= htmlspecialchars($server) ?></h1>
            <a href="./" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <?php if (!$fileWritable): ?>
            <div class="alert alert-danger mt-3">
                Warning: The config file is not writable! Changes cannot be saved.
                <a href="?server=<?= urlencode($server) ?>&volume=<?= urlencode($volume) ?>&port=<?= urlencode($port) ?>&fix=1" class="btn btn-warning btn-sm">Fix</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Server IP:Port</label>
                <input type="text" name="ip" value="<?= htmlspecialchars($ip) ?>" class="form-control" <?= !$fileWritable ? 'disabled' : '' ?>>
            </div>
            <div class="mb-3">
                <label>Authentication Code</label>
                <input type="text" name="auth" value="<?= htmlspecialchars($auth) ?>" class="form-control" <?= !$fileWritable ? 'disabled' : '' ?>>
            </div>
            <button type="submit" class="btn btn-primary" <?= !$fileWritable ? 'disabled' : '' ?>>Save Config</button>
            <a href="./" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
