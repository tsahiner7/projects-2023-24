<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Blog</title>
</head>
<body class="bg-light">
    <?php
    // Handle form submission
    if (isset($_POST['blog_submission'], $_POST['blog_title'], $_POST['blog_comment'])) {
        require_once('dbconnection.php');

        $blog_title = $_POST['blog_title'];
        $blog_comment = $_POST['blog_comment'];

        // Validate inputs
        if (!empty($blog_title) && !empty($blog_comment)) {
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

            $query = "INSERT INTO blog (title, comment) VALUES ('$blog_title', '$blog_comment')";
            mysqli_query($dbc, $query)
                or trigger_error('Error querying database: Failed to insert data', E_USER_ERROR);

            // Redirect to the same page to avoid resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
    ?>

    <div class="container text-center my-5">
        <div class="d-flex justify-content-end">
            <a class="btn btn-primary" href="authorizedindex.php">Edit Comments</a>
        </div>
        <h1 class="mb-4">Blog Post Submission</h1>
        <div class="row justify-content-center">
            <form class="col-md-8 needs-validation" novalidate method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="mb-3">
                    <label for="blog_title" class="form-label">Title:</label>
                    <input type="text" class="form-control" id="blog_title" name="blog_title" placeholder="Add a title" required>
                    <div class="invalid-feedback">Please provide a valid title.</div>
                </div>
                <div class="mb-3">
                    <label for="blog_comment" class="form-label">Comment:</label>
                    <textarea class="form-control" id="blog_comment" name="blog_comment" rows="5" placeholder="Comment" required></textarea>
                    <div class="invalid-feedback">Please provide a valid comment.</div>
                </div>
                <button class="btn btn-primary" type="submit" name="blog_submission">Add a Blog Post</button>
            </form>
        </div>

        <div class="row mt-5">
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
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <h5 class="card-title"><?= $row['title'] ?></h5>
                            </div>
                            <p class="card-text flex-grow-1"><?= $row['comment'] ?></p>
                            <p class="text-muted"><em><?= $row['date'] ?></em></p>
                        </div>
                    </div>
                </div>
            <?php
                endwhile;
            else:
            ?>
                <div class="col-12">
                    <h3>No Blog Posts Found :-(</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
