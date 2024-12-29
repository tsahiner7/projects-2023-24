<?php
    require_once('authorizeaccess.php');  // Make sure the user is logged in (authorization check)
    require_once('navbar.php');  // Include your navigation bar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Fitness Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
          crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Welcome to Your Fitness Tracker</h1>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- If user is logged in, display their info -->
            <div class="alert alert-info">
                <p>Hello, <strong><?= htmlspecialchars($_SESSION['first_name']) ?></strong>! Welcome back to your fitness tracker.</p>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <a href="view_profile.php" class="btn btn-primary btn-block">View Profile</a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="edit_profile.php" class="btn btn-warning btn-block">Edit Profile</a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="log_exercise.php" class="btn btn-success btn-block">Log Exercise</a>
                </div>
            </div>

        <?php else: ?>
            <!-- If user is not logged in, show a message and a login link -->
            <div class="alert alert-danger">
                <p>You are not logged in. Please <a href="login.php">log in</a> to continue.</p>
            </div>
        <?php endif; ?>

    </div>

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
