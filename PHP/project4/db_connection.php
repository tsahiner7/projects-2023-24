<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'student');
define('DB_PASSWORD', 'student');
define('DB_NAME', 'project_4');

// Establish a connection to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if the connection was successful
if (!$dbc) {
    die('Error connecting to MySQL server: ' . mysqli_connect_error());
}
?>
