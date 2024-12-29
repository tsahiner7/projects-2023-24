<?php
define('DB_HOST', 'localhost');      
define('DB_USER', 'student');        
define('DB_PASSWORD', 'student');    
define('DB_NAME', 'project_3');       

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Error connecting to MySQL server.');

