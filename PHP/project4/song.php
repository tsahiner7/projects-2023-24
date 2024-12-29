<?php
// Start session to track logged-in user
session_start();
require_once('db_connection.php');
require_once('navbar.php');

// Fetch all songs from the database
$query = "SELECT * FROM song ORDER BY song_name";
$result = mysqli_query($dbc, $query);

// Fetch song details (assuming this comes from song.php)
if (isset($_GET['id'])) {
    $song_id = $_GET['id'];
    $song_query = "SELECT * FROM song WHERE song_id = '$song_id'";
    $song_result = mysqli_query($dbc, $song_query);
    $song = mysqli_fetch_assoc($song_result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($song['song_name']) . ' by ' . htmlspecialchars($song['artist_name']); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<!-- Song details section -->
<div class="container mt-5 bg-light p-4 rounded shadow-sm position-relative">
    <?php
    // Display Edit button for admins
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        echo '<a href="edit_song.php?id=' . $song['song_id'] . '" class="btn btn-primary btn-sm position-absolute mt-2">Edit</a>';
    }
    ?>
    <h2 class="text-center"><?php echo htmlspecialchars($song['song_name']) . ' by ' . htmlspecialchars($song['artist_name']); ?></h2>

    <div class="mt-4">
        <h4>Chords</h4>
        <pre class="bg-light p-3 rounded"><?php echo htmlspecialchars($song['song_chords']); ?></pre>

        <h4>Notes</h4>
        <p><?php echo nl2br(htmlspecialchars($song['song_notes'])); ?></p>

        <h4>Play on YouTube</h4>
        <a href="<?php echo htmlspecialchars($song['song_url']); ?>" target="_blank" class="btn btn-outline-primary">Watch on YouTube</a>
    </div>
</div>

<!-- Include Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($dbc);
?>
