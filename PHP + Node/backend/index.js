const http = require("http");
const url = require("url");
const crypto = require("crypto");

const PORT = 3000;
let users = []; // { username: string, passwordHash: string, salt: string }
let sessions = {}; // sessionId -> username

// Helper: Parse POST data
function parseBody(req) {
  return new Promise((resolve) => {
    let body = "";
    req.on("data", (chunk) => (body += chunk));
    req.on("end", () => {
      try {
        resolve(JSON.parse(body));
      } catch (e) {
        resolve({});
      }
    });
  });
}

// Helper: Generate random session ID
function generateSessionId() {
  return crypto.randomBytes(16).toString('hex');
}

// Password hashing
function hashPassword(password, salt) {
  return crypto.pbkdf2Sync(password, salt, 1000, 64, 'sha512').toString('hex');
}

const server = http.createServer(async (req, res) => {
  const parsed = url.parse(req.url, true);

  // CORS Configuration
  const allowedOrigin = "http://localhost:8000"; // Match your PHP server port
  res.setHeader("Access-Control-Allow-Origin", allowedOrigin);
  res.setHeader("Access-Control-Allow-Credentials", "true");
  res.setHeader("Access-Control-Allow-Headers", "Content-Type, Authorization");
  res.setHeader("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
  res.setHeader("Content-Type", "application/json");

  // Handle preflight requests
  if (req.method === "OPTIONS") {
    res.writeHead(204);
    return res.end();
  }

  // SIGNUP
  if (req.method === "POST" && parsed.pathname === "/signup") {
    try {
      const { username, password } = await parseBody(req);
      
      // Input validation
      if (!username || !password || username.length < 3 || password.length < 6) {
        res.writeHead(400);
        return res.end(JSON.stringify({ error: "Username must be at least 3 chars and password at least 6 chars" }));
      }
      
      const exists = users.find((u) => u.username === username);
      if (exists) {
        res.writeHead(409);
        return res.end(JSON.stringify({ error: "User already exists" }));
      }
      
      const salt = crypto.randomBytes(16).toString('hex');
      const passwordHash = hashPassword(password, salt);
      
      users.push({ username, passwordHash, salt });
      res.writeHead(201);
      return res.end(JSON.stringify({ message: "Signup successful" }));
    } catch (error) {
      res.writeHead(500);
      return res.end(JSON.stringify({ error: "Internal server error" }));
    }
  }

  // LOGIN
  if (req.method === "POST" && parsed.pathname === "/login") {
    try {
      const { username, password } = await parseBody(req);
      const user = users.find((u) => u.username === username);
      
      if (!user) {
        res.writeHead(401);
        return res.end(JSON.stringify({ error: "Invalid credentials" }));
      }
      
      const hash = hashPassword(password, user.salt);
      if (hash !== user.passwordHash) {
        res.writeHead(401);
        return res.end(JSON.stringify({ error: "Invalid credentials" }));
      }
      
      const sessionId = generateSessionId();
      sessions[sessionId] = username;

      res.writeHead(200, {
        "Set-Cookie": `sessionId=${sessionId}; HttpOnly; Secure; SameSite=None; Path=/; Max-Age=${60 * 60 * 24}`,
      });
      return res.end(JSON.stringify({ message: "Login successful" }));
    } catch (error) {
      res.writeHead(500);
      return res.end(JSON.stringify({ error: "Internal server error" }));
    }
  }

  // LOGOUT
  if (req.method === "POST" && parsed.pathname === "/logout") {
    try {
      const cookie = req.headers.cookie || "";
      const sessionId = cookie.split('sessionId=')[1]?.split(';')[0];
      
      if (sessionId && sessions[sessionId]) {
        delete sessions[sessionId];
      }
      
      res.writeHead(200, {
        "Set-Cookie": `sessionId=; HttpOnly; Secure; SameSite=None; Path=/; Expires=Thu, 01 Jan 1970 00:00:00 GMT`,
      });
      return res.end(JSON.stringify({ message: "Logged out" }));
    } catch (error) {
      res.writeHead(500);
      return res.end(JSON.stringify({ error: "Internal server error" }));
    }
  }

  // CHECK SESSION (for frontend to validate)
  if (req.method === "GET" && parsed.pathname === "/check-session") {
    try {
      const cookie = req.headers.cookie || "";
      const sessionId = cookie.split('sessionId=')[1]?.split(';')[0];
      const username = sessionId ? sessions[sessionId] : null;
      
      if (username) {
        return res.end(JSON.stringify({ loggedIn: true, username }));
      } else {
        res.writeHead(401);
        return res.end(JSON.stringify({ loggedIn: false }));
      }
    } catch (error) {
      res.writeHead(500);
      return res.end(JSON.stringify({ error: "Internal server error" }));
    }
  }

  // DEFAULT
  res.writeHead(404);
  res.end(JSON.stringify({ error: "Not Found" }));
});

server.listen(PORT, () => {
  console.log(`Node.js backend running at http://localhost:${PORT}`);
});