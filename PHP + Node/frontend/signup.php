<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #27ae60;
        }
        #signupMessage {
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
        <h2>Sign Up</h2>
        <form id="signupForm">
            <input name="username" placeholder="Username" required>
            <input name="password" placeholder="Password" type="password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p id="signupMessage"></p>
        
        <div class="nav-links">
            <p>Already have an account? <a href="login.php">Log in</a></p>
        </div>
    </div>

    <script>
        document.getElementById('signupForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = e.target.username.value.trim();
            const password = e.target.password.value;
            const message = document.getElementById('signupMessage');
            
            // Client-side validation
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
                const res = await fetch('http://localhost:3000/signup', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password }),
                });
                
                const data = await res.json();
                
                if (res.ok) {
                    message.style.color = 'green';
                    message.textContent = 'Signup successful! Redirecting to login...';
                    setTimeout(() => window.location = 'login.php', 1500);
                } else {
                    message.style.color = 'red';
                    message.textContent = data.error || 'Signup failed';
                }
            } catch (error) {
                message.style.color = 'red';
                message.textContent = 'Network error. Please try again.';
                console.error('Signup error:', error);
            }
        });
    </script>
</body>
</html>