<!--Logs the user out by destroying the SESSION variables and redirecting them to main.php-->
<?php
session_start();
session_destroy();
header("Location: main.php");
?>