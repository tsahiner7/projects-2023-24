<?php
require_once('administrativeaccess.php');
require_once('dbconnection.php');

if (isset($_POST['id_to_delete'])) {
    $id_to_delete = $_POST['id_to_delete'];
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

    // Delete the comment
    $query = "DELETE FROM blog WHERE id = $id_to_delete";
    mysqli_query($dbc, $query) or trigger_error('Error deleting comment', E_USER_ERROR);

    // Redirect back to authorizedindex.php
    header('Location: authorizedindex.php');
    exit();
} else {
    // Redirect if no ID is set
    header('Location: authorizedindex.php');
    exit();
}
