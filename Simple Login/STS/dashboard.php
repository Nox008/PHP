<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php?error=' . urlencode('Please log in first'));
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form dashboard">
            <h2>Welcome!</h2>
            <div class="welcome-message">
                <p>Hello <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
                <p>You have successfully logged in.</p>
            </div>
            
            <div class="dashboard-actions">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>