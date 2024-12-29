<?php
    // Start the session to use session variables
    session_start();

    // Function to log unauthorized access attempts (optional)
    function logUnauthorizedAccess($message) {
        $logFile = 'unauthorized_access.log';
        $timestamp = date('Y-m-d H:i:s');
        $entry = "[$timestamp] $message\n";
        file_put_contents($logFile, $entry, FILE_APPEND);
    }

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Log the unauthorized access attempt (optional)
        $requestedPage = $_SERVER['REQUEST_URI'];
        logUnauthorizedAccess("Unauthorized access attempt to $requestedPage by an unauthenticated user.");

        // Redirect to login page with an error message
        header("Location: login.php?error=unauthorized");
        exit();
    }

    // Enhance session security by regenerating the session ID
    if (!isset($_SESSION['session_initialized'])) {
        session_regenerate_id(true); // Regenerate session ID to prevent session fixation
        $_SESSION['session_initialized'] = true; // Prevent repeated regeneration
    }
?>
