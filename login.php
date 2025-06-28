<?php
session_start();

// Array of valid users and passwords
$users = [
    'admin' => 'password123',
    'john' => 'john123',
    'jane' => 'secret456',
    'demo' => 'demo123'
];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        header('Location: index.php?error=' . urlencode('Please fill in all fields'));
        exit();
    }
    
    // Check credentials
    if (isset($users[$username]) && $users[$username] === $password) {
        // Login successful
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php');
        exit();
    } else {
        // Login failed
        header('Location: index.php?error=' . urlencode('Invalid username or password'));
        exit();
    }
} else {
    // If accessed directly, redirect to index
    header('Location: index.php');
    exit();
}
?>