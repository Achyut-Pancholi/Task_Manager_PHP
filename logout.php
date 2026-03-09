<?php
session_start();
session_unset();
session_destroy(); // Destroy all session data
header("Location: login.php");
exit();
?>