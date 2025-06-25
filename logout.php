<?php

require_once 'auth.php';
session_unset();
session_destroy();

setcookie("user_id", "", time() - 3600, "/");
setcookie("user_name", "", time() - 3600, "/");

header("Location: login.php");
exit;
