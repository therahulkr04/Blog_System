<?php
require_once 'config.php'; // Includes session_start() and $conn

// Function to safely redirect
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

// --- Handle Registration ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    // Get form data
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic Validation (add more robust validation as needed)
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['message'] = "Please fill in all fields.";
        $_SESSION['message_type'] = "danger";
        redirect('register.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
        $_SESSION['message_type'] = "danger";
        redirect('register.php');
    }

    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match.";
        $_SESSION['message_type'] = "danger";
        redirect('register.php');
    }

    if (strlen($password) < 6) { // Example: Minimum password length
         $_SESSION['message'] = "Password must be at least 6 characters long.";
         $_SESSION['message_type'] = "danger";
         redirect('register.php');
    }

    // Check if username or email already exists using prepared statements
    $sql_check = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check === false) {
        error_log("Prepare failed (check): " . $conn->error);
        $_SESSION['message'] = "An error occurred during registration preparation. Please try again.";
        $_SESSION['message_type'] = "danger";
        redirect('register.php');
    }

    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $stmt_check->store_result(); // Store result to check num_rows

    if ($stmt_check->num_rows > 0) {
        $_SESSION['message'] = "Username or Email already exists.";
        $_SESSION['message_type'] = "danger";
        $stmt_check->close();
        redirect('register.php');
    }
    $stmt_check->close();

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    if ($password_hash === false) {
        error_log("Password hashing failed.");
        $_SESSION['message'] = "An error occurred during registration. Please try again.";
        $_SESSION['message_type'] = "danger";
        redirect('register.php');
    }


    // Insert new user using prepared statements
    $sql_insert = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
     if ($stmt_insert === false) {
        error_log("Prepare failed (insert): " . $conn->error);
        $_SESSION['message'] = "An error occurred during registration finalization. Please try again.";
        $_SESSION['message_type'] = "danger";
        redirect('register.php');
    }

    $stmt_insert->bind_param("sss", $username, $email, $password_hash);

    if ($stmt_insert->execute()) {
        $_SESSION['message'] = "Registration successful! You can now login.";
        $_SESSION['message_type'] = "success";
        $stmt_insert->close();
        redirect('login.php');
    } else {
        error_log("Execute failed: " . $stmt_insert->error);
        $_SESSION['message'] = "Registration failed. Please try again.";
        $_SESSION['message_type'] = "danger";
        $stmt_insert->close();
        redirect('register.php');
    }

}
// --- Handle Login ---
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username_or_email = trim($_POST['username'] ?? ''); // Input field name is 'username'
    $password = $_POST['password'] ?? '';

    if (empty($username_or_email) || empty($password)) {
        $_SESSION['message'] = "Please enter username/email and password.";
        $_SESSION['message_type'] = "danger";
        redirect('login.php');
    }

    // Prepare statement to fetch user by username OR email
    $sql = "SELECT user_id, username, password_hash FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
     if ($stmt === false) {
        error_log("Prepare failed (login): " . $conn->error);
        $_SESSION['message'] = "An error occurred during login preparation. Please try again.";
        $_SESSION['message_type'] = "danger";
        redirect('login.php');
    }

    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result(); // Get result set

    if ($result->num_rows === 1) {
        // User found, fetch data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            // Password is correct, start session
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['message'] = "Login successful!";
            $_SESSION['message_type'] = "success";
            $stmt->close();
            redirect('index.php'); // Redirect to dashboard or home page
        } else {
            // Invalid password
            $_SESSION['message'] = "Invalid username/email or password.";
            $_SESSION['message_type'] = "danger";
            $stmt->close();
            redirect('login.php');
        }
    } else {
        // User not found
        $_SESSION['message'] = "Invalid username/email or password.";
        $_SESSION['message_type'] = "danger";
        $stmt->close();
        redirect('login.php');
    }
}
// --- Handle Invalid Actions ---
else {
    // If accessed directly or with invalid action
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "danger";
    redirect('index.php'); // Redirect to a safe page
}

// Close connection if it wasn't closed by redirect exit()
if (isset($conn) && $conn instanceof mysqli && $conn->thread_id) {
     $conn->close();
}
?>
