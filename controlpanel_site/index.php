<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php include("includes/code.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docker Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Smooth transition for the add server section */
        #add-server-form {
            display: none;
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-dark text-light">

<!-- 🔹 Navbar with Functional Buttons -->
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary px-3">
    <span class="navbar-brand">StorjDashboard Agent Manager</span>
    <div class="ms-auto">
        <button class="btn btn-primary me-2" onclick="toggleAddServer()">+ Add Server</button>
        <form method="POST" class="d-inline">
            <input type="hidden" name="action" value="regenerate_docker">
            <button type="submit" class="btn btn-warning">Regenerate Docker-Compose</button>
        </form>
        <form method="POST" class="d-inline">
            <input type="hidden" name="action" value="regenerate_nginx">
            <button type="submit" class="btn btn-warning">Regenerate Nginx Config</button>
        </form>
    </div>
</nav>

<!-- 🔹 Add Server Form (Initially Hidden) -->
<div class="container mt-3">
    <div id="add-server-form" class="card bg-secondary text-light p-3">
        <h2>Add Server</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add_server">
            <div class="mb-2">
                <label>Name:</label>
                <input type="text" name="name" required class="form-control">
            </div>
            <div class="mb-2">
                <label>Port:</label>
                <input type="number" name="port" required class="form-control">
            </div>
            <div class="mb-2">
                <label>Volume Directory:</label>
                <input type="text" name="volume" required class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Save Server</button>
        </form>
    </div>
</div>

<!-- 🔹 Servers List -->
<div class="container mt-4">
    <h2>Servers</h2>
    <div class="row">
        <?php foreach ($servers as $name => $server): ?>
            <div class="col-md-6 mb-3">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="http://<?= htmlspecialchars($_SERVER['HTTP_HOST']) ?>:<?= $server['port'] ?>" 
                               target="_blank" class="text-warning text-decoration-none">
                                <?= htmlspecialchars($name) ?>
                            </a>
                        </h5>
                        <p class="card-text">
                            <strong>Port:</strong> <?= $server['port'] ?> <br>
                            <strong>Volume:</strong> <?= htmlspecialchars($server['volume']) ?>
                        </p>
                        
                        <div class="d-flex gap-2">
                            <form method="POST" action="update_files.php">
                                <input type="hidden" name="directory" value="<?= htmlspecialchars($server['volume']) ?>">
                                <button type="submit" class="btn btn-info btn-sm">Update Files</button>
                            </form>

                            <a href="config_editor.php?server=<?= urlencode($name) ?>&volume=<?= urlencode($server['volume']) ?>&port=<?= urlencode($server['port']) ?>" 
                               class="btn btn-secondary btn-sm">Config</a>

                            <form method="POST">
                                <input type="hidden" name="action" value="remove_server">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($name) ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- 🔹 JavaScript for Add Server Form Toggle -->
<script>
function toggleAddServer() {
    let form = document.getElementById('add-server-form');

    // Check computed style instead of inline style
    if (window.getComputedStyle(form).display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

</script>

</body>
</html>
