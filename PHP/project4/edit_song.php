<?php
// Start session and include necessary files
session_start();
require_once('authorizeaccess.php'); 
require_once('adminaccess.php'); 
require_once('db_connection.php');
require_once('navbar.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['first_name']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php'); // Redirect if not logged in or not admin
    exit();
}

// Fetch song details for editing (assumes `id` is passed in the URL)
if (isset($_GET['id'])) {
    $song_id = $_GET['id'];
    $song_query = "SELECT * FROM song WHERE song_id = '$song_id'";
    $song_result = mysqli_query($dbc, $song_query);
    $song = mysqli_fetch_assoc($song_result);

    if (!$song) {
        echo "<div class='alert alert-danger text-center'>Song not found.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger text-center'>Invalid song ID.</div>";
    exit();
}

// Handle form submission to update song details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get the form data
    $song_name = mysqli_real_escape_string($dbc, $_POST['song_name']);
    $artist_name = mysqli_real_escape_string($dbc, $_POST['artist_name']);
    $song_chords = mysqli_real_escape_string($dbc, $_POST['song_chords']);
    $song_notes = mysqli_real_escape_string($dbc, $_POST['song_notes']);
    $song_url = mysqli_real_escape_string($dbc, $_POST['song_url']);

    // Update the song in the database
    $update_query = "UPDATE song SET song_name = ?, artist_name = ?, song_chords = ?, song_notes = ?, song_url = ? WHERE song_id = ?";
    $stmt = mysqli_prepare($dbc, $update_query);
    mysqli_stmt_bind_param($stmt, 'sssssi', $song_name, $artist_name, $song_chords, $song_notes, $song_url, $song_id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect to song page after update
        header("Location: song.php?id=$song_id");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error updating song: " . mysqli_error($dbc) . "</div>";
    }
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Song - <?php echo htmlspecialchars($song['song_name']) . ' by ' . htmlspecialchars($song['artist_name']); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Music Library</a>
        <div class="ml-auto">
            <?php
            if (isset($_SESSION['username'])) {
                echo "<span class='navbar-text mr-2'>Hello, " . htmlspecialchars($_SESSION['username']) . "!</span>";
                echo "<a href='logout.php' class='btn btn-outline-danger'>Log out</a>";
            } else {
                echo "<a href='signup.php' class='btn btn-outline-primary'>Sign Up</a>";
                echo "<a href='login.php' class='btn btn-outline-primary ml-2'>Login</a>";
            }
            ?>
        </div>
    </div>
</nav>

<!-- Song edit form -->
<div class="container mt-5 bg-light p-4 rounded shadow-sm">
    <h2 class="text-center">Edit Song: <?php echo htmlspecialchars($song['song_name']) . ' by ' . htmlspecialchars($song['artist_name']); ?></h2>

    <form method="post" action="edit_song.php?id=<?php echo $song['song_id']; ?>" class="mt-4">
        <div class="form-floating mb-3">
            <input type="text" name="song_name" id="song_name" class="form-control" value="<?php echo htmlspecialchars($song['song_name']); ?>" required>
            <label for="song_name">Song Name:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" name="artist_name" id="artist_name" class="form-control" value="<?php echo htmlspecialchars($song['artist_name']); ?>" required>
            <label for="artist_name">Artist Full Name:</label>
        </div>

        <div class="mb-3">
            <label for="song_chords">Song Chords:</label>
            <textarea name="song_chords" id="song_chords" class="form-control" rows="6" required><?php echo htmlspecialchars($song['song_chords']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="song_notes">Song Notes:</label>
            <textarea name="song_notes" id="song_notes" class="form-control" rows="3" required><?php echo htmlspecialchars($song['song_notes']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="song_url">Song URL (YouTube):</label>
            <input type="url" name="song_url" id="song_url" class="form-control" value="<?php echo htmlspecialchars($song['song_url']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Song</button>
    </form>
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
