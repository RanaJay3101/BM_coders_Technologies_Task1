<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Set a logout message
$_SESSION["message"] = "You have been logged out successfully.";

// Redirect the user after a short delay
header("refresh:2;url=index.html");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <p>Logging out...</p>
    <p>You will be redirected to the home page shortly.</p>
</body>
</html>
