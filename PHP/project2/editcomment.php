<?php
require_once('administrativeaccess.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit a Blog</title>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
          crossorigin="anonymous">
</head>
<body>
    <div class="card">
        <div class="card-body">
            <h1>Edit a Comment</h1>
            <nav class="nav">
                <a class="nav-link" href="index.php">Blog Post</a>
            </nav>
            <hr/>
            <?php
            require_once('dbconnection.php');

            // Initialize variables
            $blog_title = '';
            $blog_comment = '';
            $blog_date = '';
            $id_to_edit = null;

            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or trigger_error('Error connecting to MySQL server for DB_NAME.', E_USER_ERROR);

            // Handle the case where the user wants to edit a blog post
            if (isset($_GET['id_to_edit'])) {
                $id_to_edit = $_GET['id_to_edit'];

                $query = "SELECT * FROM blog WHERE id = ?";
                $stmt = mysqli_prepare($dbc, $query);
                mysqli_stmt_bind_param($stmt, "i", $id_to_edit);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);

                    $blog_title = $row['title'];
                    $blog_comment = $row['comment'];
                    $blog_date = $row['date'];
                }
            }

            // Handle the form submission for updating a blog post
            if (isset($_POST['edit_blog_submission'],
                    $_POST['blog_title'], $_POST['blog_comment'],
                    $_POST['blog_date'], $_POST['id_to_update'])) {

                $user_blog_title = $_POST['blog_title'];
                $user_blog_comment = $_POST['blog_comment'];
                $user_blog_date = $_POST['blog_date'];
                $id_to_update = $_POST['id_to_update'];

                $query = "UPDATE blog SET title = ?, comment = ?, date = ? WHERE id = ?";
                $stmt = mysqli_prepare($dbc, $query);
                mysqli_stmt_bind_param($stmt, "sssi", $user_blog_title, $user_blog_comment, $user_blog_date, $id_to_update);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    header("Location: authorizedindex.php");
                    exit;
                } else {
                    echo "Error updating the blog post.";
                }
            } elseif (empty($id_to_edit)) {  // Redirect if no blog is found to edit
                header("Location: index.php");
                exit;
            }
            ?>
            <div class="row">
                <div class="col">
                    <form enctype="multipart/form-data" class="needs-validation" novalidate method="POST"
                          action="<?= $_SERVER['PHP_SELF'] ?>">
                        <div class="form-group row">
                            <label for="blog_title" class="col-sm-3 col-form-label-lg">Blog Title</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="blog_title"
                                       name="blog_title" value='<?= htmlspecialchars($blog_title) ?>'
                                       placeholder="Title" required>
                                <div class="invalid-feedback">
                                    Please provide a valid title.
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="blog_comment" class="col-sm-3 col-form-label-lg">Comment</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="blog_comment"
                                       name="blog_comment" value='<?= htmlspecialchars($blog_comment) ?>'
                                       placeholder="Add a comment" required>
                                <div class="invalid-feedback">
                                    Please provide a valid comment.
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="blog_date" class="col-sm-3 col-form-label-lg">Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="blog_date"
                                       name="blog_date" value='<?= htmlspecialchars($blog_date) ?>'
                                       required>
                                <div class="invalid-feedback">
                                    Please provide a valid date.
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" name="edit_blog_submission">Update Comment</button>
                        <input type="hidden" name="id_to_update" value="<?= $id_to_edit ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
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
