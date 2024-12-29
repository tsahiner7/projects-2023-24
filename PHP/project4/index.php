<?php
// Start session to track logged-in user
session_start();
require_once('db_connection.php');
require_once('navbar.php');

// Fetch all songs from the database
$query = "SELECT * FROM song ORDER BY artist_name";
$result = mysqli_query($dbc, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Library</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5 text-center">
        Welcome to the Music Library
        <?php
        if (isset($_SESSION['first_name'])) {
            echo ", " . htmlspecialchars($_SESSION['first_name']);
        }
        ?>!
    </h2>

    <?php
    // Display any system messages
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] === 'deleted') {
            echo '<div class="alert alert-success text-center">Song deleted successfully!</div>';
        } elseif ($_GET['msg'] === 'error') {
            echo '<div class="alert alert-danger text-center">An error occurred. Please try again.</div>';
        } elseif ($_GET['msg'] === 'invalid') {
            echo '<div class="alert alert-warning text-center">Invalid song ID provided.</div>';
        } elseif ($_GET['msg'] === 'favorite_added') {
            echo '<div class="alert alert-success text-center">Song added to your favorites!</div>';
        } elseif ($_GET['msg'] === 'already_favorite') {
            echo '<div class="alert alert-info text-center">Song is already in your favorites.</div>';
        }
    }
    ?>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <h3>Songs by Artist</h3>
        <?php
        // Display Add Song button for admins
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            echo '<a href="add_song.php" class="btn btn-primary mb-2">Add Song</a>';
        }
        ?>
    </div>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        echo '<ul class="list-group">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo '<a href="song.php?id=' . $row['song_id'] . '" class="text-decoration-none">' . htmlspecialchars($row['song_name']) . ' by ' . htmlspecialchars($row['artist_name']) . '</a>';

            // Icon container for alignment
            echo '<div class="d-flex align-items-center">';

            // Add Star for favorites (available to all users)
            echo '<a href="add_favorite.php?song_id=' . $row['song_id'] . '" class="text-warning mx-3" title="Add to Favorites"><i class="fas fa-star"></i></a>';

            // Add Trash can for delete (available to admins)
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                echo '<a href="delete_song.php?song_id=' . $row['song_id'] . '" class="text-danger ml-3" title="Delete Song"><i class="fas fa-trash-alt"></i></a>';
            }

            echo '</div>'; // Close the icon container
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo "<p class='text-center'>No songs available</p>";
    }
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
