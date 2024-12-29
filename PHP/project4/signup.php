<?php
    require_once('db_connection.php');
    require_once('navbar.php');

    // Initialize error message
    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize and validate inputs
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        $password = trim($_POST['password']);


        // Validate inputs
        if (empty($email) || empty($username) || empty($first_name) || empty($last_name) || empty($password)) {
            $error_message = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email format.';
        } else {
            // Encrypt password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL query
            $query = "INSERT INTO user (username, first_name, last_name, password_hash, email) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($dbc, $query);
            mysqli_stmt_bind_param($stmt, 'sssss', $username, $first_name, $last_name, $hashed_password, $email);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header('Location: index.php');
                exit;
            } else {
                // Log error for debugging
                error_log("Database error: " . mysqli_error($dbc));
                $error_message = 'Error registering user. Please try again later.';
            }

            mysqli_stmt_close($stmt);
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2 class="mt-5">Sign Up</h2>

    <?php
        // Display error message if there is one
        if ($error_message != '') {
            echo "<p class='alert alert-danger'>$error_message</p>";
        }
    ?>

    <form method="post" action="signup.php" class="mt-4">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="username">First Name:</label>
            <input type="text" name="first_name" id="first_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="username">Last Name:</label>
            <input type="text" name="last_name" id="last_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
    </form>

    <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
