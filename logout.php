<?php
// Start the session to access session variables
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

// Set a success message (optional, requires starting a new session temporarily or passing via URL)
// For simplicity, we'll just redirect.
// session_start(); // Start a new session just for the message
// $_SESSION['message'] = "You have been logged out successfully.";
// $_SESSION['message_type'] = "success";

// Redirect to the login page or home page
header('Location: index.php'); // Redirect to home page after logout
exit();
?>
