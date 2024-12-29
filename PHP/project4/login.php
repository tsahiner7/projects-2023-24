<?php
session_start(); // Start the session

require_once('db_connection.php'); 
require_once('navbar.php');

// Initialize error message
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize login credentials
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Use prepared statement to query user
        $query = "SELECT user_id, username, first_name, role, password_hash FROM user WHERE username = ?";
        $stmt = mysqli_prepare($dbc, $query);

        // Bind the username parameter to the query
        mysqli_stmt_bind_param($stmt, 's', $username);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Bind the result variables
        mysqli_stmt_bind_result($stmt, $user_id, $db_username, $first_name, $role, $password_hash);

        // Fetch the result
        if (mysqli_stmt_fetch($stmt)) {
            // Check if the password is correct
            if (password_verify($password, $password_hash)) {
                // Valid user, set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['role'] = $role; // Set the user's role

                // Regenerate session ID for security
                session_regenerate_id(true);

                // Redirect to homepage
                header("Location: index.php");
                exit();
            } else {
                // Invalid credentials
                $error_message = "Invalid username or password.";
            }
        } else {
            // No user found with the given username
            $error_message = "Invalid username or password.";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        $error_message = "Please fill in all fields.";
    }
}

mysqli_close($dbc); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Song App</title>
    <!-- Bootstrap CSS -->
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
                        <!-- Display error messages -->
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger text-center">
                                <?= htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="mt-3">
                            <div class="form-group mb-3">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" 
                                       class="form-control" 
                                       value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>" 
                                       placeholder="Enter your username" 
                                       required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" 
                                       class="form-control" 
                                       placeholder="Enter your password" 
                                       required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">Don't have an account? <a href="signup.php" class="text-primary">Register here</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoGV6ZtZMbw5Pjtvw3iE9Tu5z6c1F4EZXFuDghHzngh1zFy" crossorigin="anonymous"></script>
</body>
</html>
