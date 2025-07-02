<?php
// Start PHP session for traditional PHP session management
session_start();

// Check if user is logged in via our Node.js backend
$loggedIn = false;
$username = '';

if (isset($_COOKIE['sessionId'])) {
    // In a real app, you'd verify this with your Node.js backend
    // For this example, we'll just check if the cookie exists
    $loggedIn = true;
    // Get username from localStorage (set by our frontend)
    $username = isset($_COOKIE['username']) ? $_COOKIE['username'] : 'User';
}

if (!$loggedIn) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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
        #welcomeMessage {
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        #logoutBtn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        #logoutBtn:hover {
            background-color: #c0392b;
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
        <h2>Welcome</h2>
        <p id="welcomeMessage"></p>
        <button id="logoutBtn">Logout</button>
        
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
        </div>
    </div>

    <script>
        // Check session with backend
        async function checkSession() {
            try {
                const response = await fetch('http://localhost:3000/check-session', {
                    method: 'GET',
                    credentials: 'include'
                });
                
                const data = await response.json();
                
                if (!data.loggedIn) {
                    localStorage.removeItem('username');
                    window.location = 'login.php';
                    return;
                }
                
                // Update UI with username
                const username = localStorage.getItem('username') || data.username || 'User';
                document.getElementById('welcomeMessage').textContent = `Welcome back, ${username}!`;
                
            } catch (error) {
                console.error('Session check failed:', error);
                window.location = 'login.php';
            }
        }
        
        // Initial check
        checkSession();
        
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            try {
                await fetch('http://localhost:3000/logout', {
                    method: 'POST',
                    credentials: 'include'
                });
                localStorage.removeItem('username');
                window.location = 'login.php';
            } catch (error) {
                console.error('Logout failed:', error);
            }
        });
    </script>
</body>
</html>