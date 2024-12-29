<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Log Exercise</title>
</head>
<body class="bg-light">
    <?php
        require_once('authorizeaccess.php');
        require_once('dbconnection.php');
        require_once('navbar.php');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type = mysqli_real_escape_string($dbc, $_POST['type']);
            $date = mysqli_real_escape_string($dbc, $_POST['date']);
            $time = (int)$_POST['time'];
            $heartrate = (int)$_POST['heartrate'];
            $user_id = $_SESSION['user_id'];

            if ($time <= 0 || $heartrate <= 0) {
                echo "<div class='alert alert-danger text-center'>Time and Heart Rate must be positive values.</div>";
                exit;
            }

            $user_query = "SELECT * FROM user_exercise WHERE user_id = ?";
            $stmt = mysqli_prepare($dbc, $user_query);
            mysqli_stmt_bind_param($stmt, 'i', $user_id);
            mysqli_stmt_execute($stmt);
            $user_result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($user_result);
            mysqli_stmt_close($stmt);

            if (!$user) {
                echo "<div class='alert alert-danger text-center'>User not found.</div>";
                exit;
            }

            $weight = $user['weight'];
            $birthdate = $user['birthdate'];
            $age = date_diff(date_create($birthdate), date_create('today'))->y;

            if ($user['gender'] == 'Male') {
                $calories = ((-55.0969 + (0.6309 * $heartrate) + (0.090174 * $weight) + (0.2017 * $age)) / 4.184) * $time;
            } elseif ($user['gender'] == 'Female') {
                $calories = ((-20.4022 + (0.4472 * $heartrate) - (0.057288 * $weight) + (0.074 * $age)) / 4.184) * $time;
            } else {
                $calories = ((-37.7495 + (0.5391 * $heartrate) + (0.01644 * $weight) + (0.1379 * $age)) / 4.184) * $time;
            }

            $query = "INSERT INTO exercise_log (user_id, date, type, time_in_minutes, heart_rate, calories_burned) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($dbc, $query);
            mysqli_stmt_bind_param($stmt, 'issdii', $user_id, $date, $type, $time, $heartrate, $calories);

            if (mysqli_stmt_execute($stmt)) {
                echo "<div class='alert alert-success text-center'>Exercise logged successfully! Calories burned: " . round($calories, 2) . "</div>";
            } else {
                echo "<div class='alert alert-danger text-center'>Error logging exercise. Please try again later.</div>";
            }
            mysqli_stmt_close($stmt);
        }
    ?>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Log Exercise</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" class="p-3">
                            <div class="form-floating mb-3">
                                <select name="type" id="type" class="form-select" required>
                                    <option value="Running">Running</option>
                                    <option value="Walking">Walking</option>
                                    <option value="Swimming">Swimming</option>
                                    <option value="Weightlifting">Weightlifting</option>
                                </select>
                                <label for="type">Type</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="date" name="date" id="date" class="form-control" required>
                                <label for="date">Date</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" name="time" id="time" class="form-control" required min="1">
                                <label for="time">Time (minutes)</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" name="heartrate" id="heartrate" class="form-control" required min="1">
                                <label for="heartrate">Average Heart Rate</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Log Exercise</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
