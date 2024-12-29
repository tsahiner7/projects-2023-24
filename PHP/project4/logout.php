<?php
    session_start();  // Start the session

    // Clear session variables
    $_SESSION = [];

    // Destroy the session and its associated cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Redirect to the index page
    header("Location: index.php");
    exit();
?>
