<?php
session_start();
session_destroy();

// Clear cookies
setcookie('sessionId', '', time() - 3600, '/');
setcookie('username', '', time() - 3600, '/');

header("Location: login.php");
exit();
?>