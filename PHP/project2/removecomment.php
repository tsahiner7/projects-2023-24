<?php
require_once('administrativeaccess.php');
require_once('dbconnection.php');

if (isset($_GET['id_to_delete'])) {
    $id_to_delete = $_GET['id_to_delete'];
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

    // Fetch the comment details
    $query = "SELECT title, comment FROM blog WHERE id = $id_to_delete";
    $result = mysqli_query($dbc, $query) or trigger_error('Error querying database', E_USER_ERROR);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        // Redirect if the comment doesn't exist
        header('Location: authorizedindex.php');
        exit();
    }
} else {
    // Redirect if no ID is set
    header('Location: authorizedindex.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <title>Remove Comment</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Confirm Deletion</h5>
                <p>Are you sure you want to delete the following comment?</p>
                <h6>Title: <?= $row['title'] ?></h6>
                <p>Comment: <?= $row['comment'] ?></p>

                <form action="deletecomment.php" method="post">
                    <input type="hidden" name="id_to_delete" value="<?= $id_to_delete ?>">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <a href="authorizedindex.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
