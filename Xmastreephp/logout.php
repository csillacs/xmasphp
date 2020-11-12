<?php

$conn = mysqli_connect("127.0.0.1", "root", "", "neilikka");
if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
// echo "Success: A proper connection to MySQL was made! ";
// echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL . "<br>";


// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: login.php");
exit;
