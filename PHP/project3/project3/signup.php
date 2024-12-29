<?php
    require_once('dbconnection.php');

    // Initialize error messages
    $error_message = '';

    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize and validate inputs
        $username = mysqli_real_escape_string($dbc, $_POST['username']);
        $first_name = mysqli_real_escape_string($dbc, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($dbc, $_POST['last_name']);
        $password = mysqli_real_escape_string($dbc, $_POST['password']);
        $gender = mysqli_real_escape_string($dbc, $_POST['gender']);
        $weight = (int)$_POST['weight'];  // Weight in pounds
        $birthdate = mysqli_real_escape_string($dbc, $_POST['birthdate']);

        // Check if inputs are valid
        if (empty($username) || empty($first_name) || empty($last_name) || 
            empty($password) || empty($gender) || empty($weight) || empty($birthdate)
        ) {
            $error_message = 'Please fill in all fields.';
        } elseif ($weight <= 0) {
            $error_message = 'Weight must be a positive number.';
        } else {
            // Encrypt password (you can use more advanced hashing methods if needed)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and execute the query to insert user data
            $query = "INSERT INTO user_exercise (username, first_name, last_name, password_hash, gender, weight, birthdate) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";

                    //   echo $query;
            $stmt = mysqli_prepare($dbc, $query);
            mysqli_stmt_bind_param($stmt, 'sssssis', $username, $first_name, 
                                   $last_name, $hashed_password, $gender, $weight, $birthdate);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login or homepage after successful signup
                header('Location: login.php');
                exit;
            } else {
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
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="weight">Weight (lbs):</label>
            <input type="number" name="weight" id="weight" class="form-control" required min="1">
        </div>
        <div class="form-group">
            <label for="birthdate">Birthdate:</label>
            <input type="date" name="birthdate" id="birthdate" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
    </form>

    <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
