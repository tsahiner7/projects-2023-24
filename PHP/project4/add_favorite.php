<?php
// Start session and include the database connection
session_start();
require_once('db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the song ID from the URL
if (isset($_GET['song_id']) && is_numeric($_GET['song_id'])) {
    $song_id = intval($_GET['song_id']);
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Check if the song is already a favorite
    $check_query = "SELECT * FROM user_favorite_songs WHERE user_id = ? AND song_id = ?";
    $stmt = mysqli_prepare($dbc, $check_query);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $song_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Song is already a favorite
        header("Location: index.php?msg=already_favorite");
    } else {
        // Add the song to the favorite list
        $insert_query = "INSERT INTO user_favorite_songs (user_id, song_id) VALUES (?, ?)";
        echo $insert_query;
        $stmt = mysqli_prepare($dbc, $insert_query);
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $song_id);

        if (mysqli_stmt_execute($stmt)) {
            // Successfully added
            header("Location: index.php?msg=favorite_added");
        } else {
            // Error occurred
            header("Location: index.php?msg=error");
        }
    }
} else {
    // Invalid song ID
    header("Location: index.php?msg=invalid");
}
?>
