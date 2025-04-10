<?php
/**
 * config.php
 *
 * Database Configuration and Connection File for PHP Blog System
 */

// --- Error Reporting (Development vs Production) ---
// Show all errors during development
error_reporting(E_ALL);
ini_set('display_errors', 1);
// In production, you should log errors instead:
// error_reporting(0);
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log', '/path/to/your/php-error.log');

// --- Database Configuration ---
$servername = "localhost";          // Database server hostname (usually 'localhost' for XAMPP)
$username   = "root";               // Database username (default for XAMPP is 'root')
$password   = "";                   // Database password (default for XAMPP is empty)
$dbname     = "blog_system_db";     // <<--- USE THE DATABASE NAME FROM blog_setup.sql

// --- Establish Connection ---
// Use MySQLi (object-oriented style recommended for prepared statements)
$conn = new mysqli($servername, $username, $password, $dbname);

// --- Check Connection ---
if ($conn->connect_error) {
    // Log the error and display a generic message to the user
    error_log("Database Connection Error: " . $conn->connect_error); // Log detailed error
    die("Database connection failed. Please try again later or contact support."); // User-friendly message
}

// Set character set
$conn->set_charset("utf8mb4");

// --- Start Session ---
// Sessions are needed for user authentication (login status)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- Base URL (Optional but helpful for links/redirects) ---
// Detect scheme (http or https)
$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
// Get host name
$host = $_SERVER['HTTP_HOST'];
// Get the directory path of the current script, relative to the document root
$script_dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
// Construct the base URL (adjust if your project is not in the root of htdocs)
define('BASE_URL', $scheme . '://' . $host . rtrim($script_dir, '/'));


?>
