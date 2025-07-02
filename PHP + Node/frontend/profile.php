<?php
session_start();
if (!isset($_COOKIE['sessionId'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }
        .profile-info {
            margin-bottom: 2rem;
            text-align: left;
        }
        .profile-info p {
            margin: 0.5rem 0;
        }
        .nav-links {
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-around;
        }
        .nav-links a {
            color: #3498db;
            text-decoration: none;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Profile</h2>
        
        <div class="profile-info">
            <p><strong>Username:</strong> <span id="username"></span></p>
            <p><strong>Member since:</strong> Today</p>
        </div>
        
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <script>
        // Display username from localStorage
        document.getElementById('username').textContent = localStorage.getItem('username') || 'User';
    </script>
</body>
</html>