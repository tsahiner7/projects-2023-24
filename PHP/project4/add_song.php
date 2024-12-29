<?php
// Start session and include necessary files
require_once('authorizeaccess.php');
require_once('adminaccess.php');
require_once('db_connection.php');
require_once('navbar.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize input
    $song_name = mysqli_real_escape_string($dbc, $_POST['song_name']);
    $artist_name = mysqli_real_escape_string($dbc, $_POST['artist_name']);
    $song_chords = mysqli_real_escape_string($dbc, $_POST['song_chords']);
    $song_notes = mysqli_real_escape_string($dbc, $_POST['song_notes']);
    $song_url = mysqli_real_escape_string($dbc, $_POST['song_url']);
    $user_id = $_SESSION['user_id'];

    // Insert the song data into the database
    $query = "INSERT INTO song (song_name, artist_name, song_chords, song_notes, song_url) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'sssss', $song_name, $artist_name, $song_chords, $song_notes, $song_url);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect to index.php after successful insertion
        header("Location: index.php");
        exit(); // Make sure no further code is executed
    } else {
        echo "<div class='alert alert-danger text-center'>Error adding song: " . mysqli_error($dbc) . "</div>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Add Song</title>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Add Song</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" class="p-3">
                            <div class="form-floating mb-3">
                                <input type="text" name="song_name" id="song_name" class="form-control" required>
                                <label for="song_name">Song Name:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="artist_name" id="artist_name" class="form-control" required>
                                <label for="artist_name">Artist Full Name:</label>
                            </div>
                            <div class="mb-3">
                                <label for="song_chords">Song Chords:</label>
                                <textarea name="song_chords" id="song_chords" class="form-control" rows="6" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="song_notes">Song Notes:</label>
                                <input type="text" name="song_notes" id="song_notes" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="song_url">Song URL (YouTube):</label>
                                <input type="url" name="song_url" id="song_url" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Log Song</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
