<?php
    // Include the necessary files for access control and database connection
    require_once('authorizeaccess.php');
    require_once('dbconnection.php');
    require_once('navbar.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
          crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">View Profile</h1>
        
        <div class="card mb-4">
            <div class="card-body">
                <?php
                    // Fetch the logged-in user's ID from the session
                    $user_id = $_SESSION['user_id']; // Assuming the user_id is stored in session

                    // Query the database to get user information using the logged-in user's ID
                    $user_query = "SELECT * FROM user_exercise WHERE user_id = ?";
                    $stmt = mysqli_prepare($dbc, $user_query);
                    mysqli_stmt_bind_param($stmt, 'i', $user_id);
                    mysqli_stmt_execute($stmt);
                    $user_result = mysqli_stmt_get_result($stmt);
                    $user = mysqli_fetch_assoc($user_result);

                    // Handle errors if the user data cannot be fetched
                    if ($user === false) {
                        echo '<p class="text-danger">Unable to fetch your profile data. Please try again later.</p>';
                        exit(); // Stop further execution
                    }
                ?>
                
                <h3>User Information</h3>
                <p><strong>User Name:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>First Name:</strong> <?= htmlspecialchars($user['first_name']) ?></p>
                <p><strong>Last Name:</strong> <?= htmlspecialchars($user['last_name']) ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']) ?></p>
                <p><strong>Birthdate:</strong> <?= htmlspecialchars($user['birthdate']) ?></p>
                <p><strong>Weight:</strong> <?= htmlspecialchars($user['weight']) ?> lbs</p>
            </div>
        </div>

        <h2>Exercise Log</h2>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Time (minutes)</th>
                    <th>Heart Rate</th>
                    <th>Calories Burned</th>
                    <th>Action</th> <!-- Added Action column for delete link -->
                </tr>
            </thead>
            <tbody>
                <?php
                    // Query to fetch the last 15 exercise logs for the logged-in user
                    $log_query = "SELECT * FROM exercise_log WHERE user_id = ? ORDER BY date DESC LIMIT 15";
                    $stmt = mysqli_prepare($dbc, $log_query);
                    mysqli_stmt_bind_param($stmt, 'i', $user_id);
                    mysqli_stmt_execute($stmt);
                    $log_result = mysqli_stmt_get_result($stmt);

                    // Check if there are any logs to display
                    if (mysqli_num_rows($log_result) == 0) {
                        echo '<tr><td colspan="6" class="text-center">No exercise logs available.</td></tr>';
                    } else {
                        // Loop through the logs and display them
                        while ($log = mysqli_fetch_assoc($log_result)) {
                ?>
                <tr>
                    <td><?= htmlspecialchars($log['date']) ?></td>
                    <td><?= htmlspecialchars($log['type']) ?></td>
                    <td><?= htmlspecialchars($log['time_in_minutes']) ?></td>
                    <td><?= htmlspecialchars($log['heart_rate']) ?></td>
                    <td><?= round($log['calories_burned'], 2) ?></td>
                    <td>
                        <!-- Add a delete link that triggers the deletion -->
                        <a href="delete_exercise.php?id=<?= $log['id'] ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to delete this log?');">Delete</a>
                    </td>
                </tr>
                <?php 
                        }
                    }
                ?>
            </tbody>
        </table>
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
