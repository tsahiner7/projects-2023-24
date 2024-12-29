<?php
require_once('administrativeaccess.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Blog Management</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-4">Manage Comments</h1>
            <a class="btn btn-primary" href="index.php">Go to Blog Page</a>
        </div>
        
        <div class="row">
        <?php
        // Fetch and display blog posts
        require_once('dbconnection.php');
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

        $query = "SELECT * FROM blog ORDER BY id DESC";
        $result = mysqli_query($dbc, $query) or trigger_error('Error querying database', E_USER_ERROR);

        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
        ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm d-flex flex-column">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title"><?= $row['title'] ?></h5>
                                <a class='text-danger' href="removecomment.php?id_to_delete=<?= $row['id'] ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                            <p class="card-text flex-grow-1"><?= $row['comment'] ?></p>
                            <p class="text-muted"><em><?= $row['date'] ?></em></p>
                            <div class="d-flex justify-content-end mb-2">
                                <a href="editcomment.php?id_to_edit=<?= $row['id'] ?>"><button class="btn btn-secondary">Edit</button></a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            endwhile;
        else:
        ?>
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    <h3>No Blog Posts Found :-(</h3>
                </div>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
