<?php
    require_once('authorizeaccess.php'); 
    require_once('db_connection.php');
    require_once('navbar.php');

    // Retrieve user_id from session for dynamic user access
    $user_id = $_SESSION['user_id'];

    // Establish database connection
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die('Error connecting to MySQL server.');

    // Retrieve the current user's data from the database
    $query = "SELECT * FROM user WHERE user_id = ?";
    if ($stmt = mysqli_prepare($dbc, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($stmt);
        } else {
            echo "No user found!";
            exit();
        }
    }

    // Handle form submission for updating user profile
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize and validate input
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        $email = mysqli_real_escape_string($dbc, $_POST['email']);
        $password = $_POST['password'];

        // Hash the password if it's being updated
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT); // Secure password hash
        } else {
            // If no new password is provided, retain the existing one
            $password = isset($row['password_hash']) ? $row['password_hash'] : '';
        }

        // Prepare and execute the update query
        $query = "UPDATE user SET 
                    username = ?,  
                    first_name = ?,  
                    last_name = ?,  
                    email = ?, 
                    password_hash = ?
                  WHERE user_id = ?";
            
        // Prepare the statement
        if ($stmt = mysqli_prepare($dbc, $query)) {
            // Bind parameters and execute
            mysqli_stmt_bind_param($stmt, 'sssssi', $username, $first_name, $last_name, $email, $password, $user_id);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                // Set a session message for confirmation
                $_SESSION['message'] = 'Profile updated successfully!';
                // Redirect to view_profile.php
                header('Location: view_profile.php');
                exit();
            } else {
                echo "<p class='alert alert-danger'>Error updating profile. Please try again.</p>";
            }
            // Close the statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close the database connection
    mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
          crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Edit Profile</h1>
        <form method="post" action="" class="mt-4">
            <div class="form-group">
                <label for="username">User Name:</label>
                <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?= htmlspecialchars($row['first_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?= htmlspecialchars($row['last_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save Profile</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFfRlMFy6i5SRaxvfBQwFzFuBd8rO1G9L9r3q4y6Y5Ip5r8U7jpv0wJ0dHz1u7"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQnzXb0j3KjF5Xg4lPxlG5dNmJcGrt4fC5vjUq"
            crossorigin="anonymous"></script>
</body>
</html>
