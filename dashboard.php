<?php


require_once 'auth.php';

echo "<h2>Welcome, " . htmlspecialchars($_SESSION['user_name']) . "!</h2>";
echo "<a href='logout.php'>Logout</a>";
