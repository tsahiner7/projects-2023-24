<?php
    require_once('authorizeaccess.php');
    require_once('dbconnection.php');
    
    // Ensure the ID is passed in the URL
    if (isset($_GET['id'])) {
        $exercise_id = $_GET['id'];

        // Prepare and execute the DELETE query
        $delete_query = "DELETE FROM exercise_log WHERE id = ?";
        $stmt = mysqli_prepare($dbc, $delete_query);
        mysqli_stmt_bind_param($stmt, 'i', $exercise_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Redirect back to the view profile page with a success message
            header("Location: view_profile.php?msg=deleted");
        } else {
            // Redirect with an error message
            header("Location: view_profile.php?msg=error");
        }
    } else {
        // Redirect if no ID is provided
        header("Location: view_profile.php");
    }
?>
