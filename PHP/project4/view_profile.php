<?php
// Include the necessary files for access control and database connection
require_once('authorizeaccess.php');
require_once('db_connection.php');
require_once('navbar.php');

// Check if there's a session message to display
if (isset($_SESSION['message'])) {
    echo "<p class='alert alert-success'>" . $_SESSION['message'] . "</p>";
    // Unset the session message after displaying it
    unset($_SESSION['message']);
}

// Fetch the logged-in user's ID from the session
$user_id = $_SESSION['user_id'] ?? null; // Ensure $user_id is set

// Handle the case where the user is not logged in
if (!$user_id) {
    echo '<p class="text-danger">User not logged in. Please log in to view your profile.</p>';
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Query to update the user profile
    $update_query = "UPDATE user SET username = ?, first_name = ?, last_name = ?, email = ?, password = ? WHERE user_id = ?";
    $update_stmt = mysqli_prepare($dbc, $update_query);
    mysqli_stmt_bind_param($update_stmt, 'sssssi', $username, $first_name, $last_name, $email, $hashed_password, $user_id);
    mysqli_stmt_execute($update_stmt);

    // Check if the update was successful
    if (mysqli_stmt_affected_rows($update_stmt) > 0) {
        echo '<p class="text-success">Profile updated successfully!</p>';
    } else {
        echo '<p class="text-danger">Unable to update your profile. Please try again.</p>';
    }
}

// Query to get user information
$user_query = "SELECT * FROM user WHERE user_id = ?";
$stmt = mysqli_prepare($dbc, $user_query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$user_result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($user_result);

// Handle errors if the user data cannot be fetched
if ($user === false) {
    echo '<p class="text-danger">Unable to fetch your profile data. Please try again later.</p>';
    exit();
}

// Check if the remove song form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_song_id'])) {
    $remove_song_id = intval($_POST['remove_song_id']);

    // Query to delete the song from user's favorite list
    $remove_query = "DELETE FROM user_favorite_songs WHERE user_id = ? AND song_id = ?";
    $remove_stmt = mysqli_prepare($dbc, $remove_query);
    mysqli_stmt_bind_param($remove_stmt, 'ii', $user_id, $remove_song_id);
    mysqli_stmt_execute($remove_stmt);

    if (mysqli_stmt_affected_rows($remove_stmt) > 0) {
        $_SESSION['message'] = 'Song removed from your favorites.';
    } else {
        $_SESSION['message'] = 'Failed to remove the song. Please try again.';
    }

    // Redirect to avoid re-submission on page refresh
    header("Location: view_profile.php");
    exit();
}


// Query to fetch the user's favorite songs
$fav_songs_query = "
    SELECT song.song_id, song.song_name AS title, song.artist_name AS artist
    FROM user_favorite_songs
    INNER JOIN song ON user_favorite_songs.song_id = song.song_id
    WHERE user_favorite_songs.user_id = ?";
$fav_stmt = mysqli_prepare($dbc, $fav_songs_query);
mysqli_stmt_bind_param($fav_stmt, 'i', $user_id);
mysqli_stmt_execute($fav_stmt);
$fav_songs_result = mysqli_stmt_get_result($fav_stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
          crossorigin="anonymous">
    <style>
        .edit-btn {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">View Profile</h1>

        <!-- User Information Card -->
        <div class="card mb-4">
            <div class="card-body position-relative">
                <h3>User Information</h3>
                <p><strong>User Name:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>First Name:</strong> <?= htmlspecialchars($user['first_name']) ?></p>
                <p><strong>Last Name:</strong> <?= htmlspecialchars($user['last_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

                <!-- Edit Profile Button -->
                <a href="edit_profile.php" class="btn btn-warning btn-sm edit-btn">Edit Profile</a>
            </div>
        </div>

        <!-- Favorite Songs Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h3>Your Favorite Songs</h3>
                <?php if (mysqli_num_rows($fav_songs_result) > 0): ?>
                    <ul class="list-group mb-4">
                        <?php while ($fav_song = mysqli_fetch_assoc($fav_songs_result)): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="song.php?id=<?= $fav_song['song_id'] ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($fav_song['title']) ?> - <?= htmlspecialchars($fav_song['artist']) ?>
                                </a>
                                <form method="POST" style="margin: 0;">
                                    <input type="hidden" name="remove_song_id" value="<?= $fav_song['song_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        <p>No favorite songs found.</p>
                        <a href="index.php" class="btn btn-primary">Do you want to add some?</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts for Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
            integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
            crossorigin="anonymous"></script>
</body>
</html>
