<?php
require_once('authorizeaccess.php'); 
require_once('adminaccess.php');    
require_once('db_connection.php');  

if (isset($_GET['song_id']) && is_numeric($_GET['song_id'])) {
    $song_id = intval($_GET['song_id']); 
    // Check for confirmation
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        $delete_query = "DELETE FROM song WHERE song_id = ?";
        $stmt = mysqli_prepare($dbc, $delete_query);
        mysqli_stmt_bind_param($stmt, 'i', $song_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=deleted");
            exit();
        } else {
            error_log("Error deleting song ID $song_id: " . mysqli_stmt_error($stmt));
            header("Location: index.php?msg=error");
            exit();
        }
    }
} else {
    header("Location: index.php?msg=invalid");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Deletion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-warning text-center">
        <h3>Are you sure you want to delete this song?</h3>
        <p>This action cannot be undone.</p>
        <a href="delete_song.php?song_id=<?php echo $song_id; ?>&confirm=yes" class="btn btn-danger">Yes, Delete</a>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
</div>
</body>
</html>

