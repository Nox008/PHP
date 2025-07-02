<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        #loginMessage {
            margin-top: 1rem;
            text-align: center;
        }
        .nav-links {
            margin-top: 1.5rem;
            text-align: center;
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
        <h2>Login</h2>
        <form id="loginForm">
            <input name="username" placeholder="Username" required>
            <input name="password" placeholder="Password" type="password" required>
            <button type="submit">Login</button>
        </form>
        <p id="loginMessage"></p>
        
        <div class="nav-links">
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = e.target.username.value.trim();
            const password = e.target.password.value;
            const message = document.getElementById('loginMessage');
            
            // Basic client-side validation
            if (username.length < 3) {
                message.style.color = 'red';
                message.textContent = 'Username must be at least 3 characters';
                return;
            }
            
            if (password.length < 6) {
                message.style.color = 'red';
                message.textContent = 'Password must be at least 6 characters';
                return;
            }
            
            try {
                const res = await fetch('http://localhost:3000/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify({ username, password }),
                });
                
                const data = await res.json();
                
                if (res.ok) {
                    localStorage.setItem('username', username);
                    message.style.color = 'green';
                    message.textContent = 'Login successful! Redirecting...';
                    setTimeout(() => window.location = 'index.php', 1000);
                } else {
                    message.style.color = 'red';
                    message.textContent = data.error || 'Login failed';
                }
            } catch (error) {
                message.style.color = 'red';
                message.textContent = 'Network error. Please try again.';
                console.error('Login error:', error);
            }
        });
    </script>
</body>
</html>