<?php
    session_start(); // Start the session to use session variables

    require_once('dbconnection.php'); // Database connection file

    // Initialize an error message variable
    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect and sanitize login credentials
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $password = trim($_POST['password']);

        // Validate input fields
        if (!empty($username) && !empty($password)) {
            // Query the database for a user with the matching username
            $query = "SELECT user_id, username, password_hash FROM user_exercise WHERE username = '$username' LIMIT 1";
            $result = mysqli_query($dbc, $query);

            if ($result && mysqli_num_rows($result) === 1) {
                // Fetch user details from the database
                $user = mysqli_fetch_assoc($result);

                // Verify the entered password with the stored password hash
                if (password_verify($password, $user['password_hash'])) {
                    // Password is correct, start session and save user info
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['first_name'] = $user['first_name'];

                    // Regenerate session ID for added security
                    session_regenerate_id(true);

                    // Red•••••••irect to the homepage (index.php)
                    // echo "Login";
                    header("Location: index.php");
                    exit();
                } else {
                    // Invalid password
                    $error_message = "Invalid username or password.";
                }
            } else {
                // User not found
                $error_message = "Invalid username or password.";
            }
        } else {
            // Missing credentials
            $error_message = "Please fill in all fields.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fitness Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZQFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
          crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>Login to Your Account</h3>
                    </div>
                    <div class="card-body">
                        <!-- Display error messages, if any -->
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger text-center">
                                <?= htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="mt-3">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" 
                                       class="form-control" 
                                       value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>" 
                                       placeholder="Enter your username" 
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" 
                                       class="form-control" 
                                       placeholder="Enter your password" 
                                       required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-3">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">Don't have an account? <a href="signup.php" class="text-primary">Register here</a>.</p>
                    </div>
                </div>
            </div>
        </div>
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
