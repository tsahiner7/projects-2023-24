<?php
require_once('authorizeaccess.php'); // Ensure user is authenticated

// Function to log unauthorized access attempts for admin-only actions
function logUnauthorizedAdminAccess($message) {
    $logFile = 'admin_access.log';
    $timestamp = date('Y-m-d H:i:s');
    $entry = "[$timestamp] $message\n";
    file_put_contents($logFile, $entry, FILE_APPEND);
}

// Check if the user has admin privileges
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Log the unauthorized access attempt
    $requestedPage = $_SERVER['REQUEST_URI'];
    $username = $_SESSION['username'] ?? 'Unknown';
    logUnauthorizedAdminAccess("Unauthorized admin access attempt to $requestedPage by user: $username");

    // Redirect to access denied page
    header("Location: access_denied.php");
    exit();
}
?>
