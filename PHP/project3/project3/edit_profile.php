<?php
    require_once('authorizeaccess.php'); // Ensure user is authorized
    require_once('dbconnection.php');
    require_once('navbar.php');

    // Retrieve user_id from session for dynamic user access
    $user_id = $_SESSION['user_id'];

    // Establish database connection
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die('Error connecting to MySQL server.');

    // Handle form submission for updating user profile
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize and validate input
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        $gender = mysqli_real_escape_string($dbc, $_POST['gender']);
        $birthdate = mysqli_real_escape_string($dbc, $_POST['birthdate']);
        $weight = mysqli_real_escape_string($dbc, $_POST['weight']);

        // Input validation (ensure weight is numeric and valid)
        if (!is_numeric($weight) || $weight <= 0) {
            echo "<p class='alert alert-danger'>Please enter a valid weight greater than zero.</p>";
        } else {
            // Prepare and execute the update query
            $query = "UPDATE user_exercise SET 
                        username = ?,  
                        first_name = ?,  
                        last_name = ?,  
                        gender = ?, 
                        birthdate = ?, 
                        weight = ? 
                      WHERE user_id = ?";
            
            // Prepare the statement
            if ($stmt = mysqli_prepare($dbc, $query)) {
                // Bind parameters and execute
                mysqli_stmt_bind_param($stmt, 'sssssdi', $username, $first_name, $last_name, $gender, $birthdate, $weight, $user_id);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    echo "<p class='alert alert-success'>Profile updated successfully!</p>";
                } else {
                    echo "<p class='alert alert-danger'>Error updating profile. Please try again.</p>";
                }
                // Close the statement
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Retrieve the current user's data from the database
    $query = "SELECT * FROM user_exercise WHERE user_id = ?";
    if ($stmt = mysqli_prepare($dbc, $query)) {
        // Bind parameter and execute
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }

    // Format the birthdate to 'Y-m-d' for the date input
    $birthdate = date('Y-m-d', strtotime($row['birthdate']));

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
                <label for="gender">Gender:</label>
                <select name="gender" id="gender" class="form-control">
                    <option value="Male" <?= $row['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $row['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Non Binary" <?= $row['gender'] == 'Non Binary' ? 'selected' : '' ?>>Non Binary</option>
                </select>
            </div>
            <div class="form-group">
                <label for="birthdate">Birthdate:</label>
                <input type="date" name="birthdate" id="birthdate" class="form-control" value="<?= htmlspecialchars($birthdate) ?>" required>
            </div>
            <div class="form-group">
                <label for="weight">Weight (lbs):</label>
                <input type="number" name="weight" id="weight" class="form-control" value="<?= htmlspecialchars($row['weight']) ?>" required min="1">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save Profile</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFfRlMFy6i5SRaxvf
