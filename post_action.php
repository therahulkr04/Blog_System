<?php
require_once 'config.php'; // Includes session_start() and $conn

// Function to safely redirect
function redirect($url) {
    // Use BASE_URL if defined, otherwise assume relative path
    $location = defined('BASE_URL') ? BASE_URL . '/' . ltrim($url, '/') : $url;
    header('Location: ' . $location);
    exit();
}

// --- Authentication Check ---
// Most actions require user to be logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
    $_SESSION['message_type'] = "danger";
    redirect('login.php');
}

// --- Handle Create Post ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id']; // Get user ID from session

    // Basic Validation
    if (empty($title) || empty($content)) {
        $_SESSION['message'] = "Title and content cannot be empty.";
        $_SESSION['message_type'] = "danger";
        redirect('post_create.php'); // Redirect back to create form
    }

    // Insert post using prepared statements
    $sql = "INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Prepare failed (create post): " . $conn->error);
        $_SESSION['message'] = "An error occurred while preparing the post.";
        $_SESSION['message_type'] = "danger";
        redirect('post_create.php');
    }

    // Bind parameters (i: integer, s: string)
    $stmt->bind_param("iss", $user_id, $title, $content);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Blog post created successfully!";
        $_SESSION['message_type'] = "success";
        $stmt->close();
        redirect('posts_list.php'); // Redirect to the blog list
    } else {
        error_log("Execute failed (create post): " . $stmt->error);
        $_SESSION['message'] = "Failed to create post. Please try again.";
        $_SESSION['message_type'] = "danger";
        $stmt->close();
        redirect('post_create.php');
    }
}

// --- Handle Delete Post ---
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    $user_id = $_SESSION['user_id']; // Current logged-in user

    if (!$post_id) {
        $_SESSION['message'] = "Invalid post ID.";
        $_SESSION['message_type'] = "danger";
        redirect('posts_list.php');
    }

    // Verify that the logged-in user is the owner of the post before deleting
    $sql_verify = "SELECT user_id FROM posts WHERE post_id = ?";
    $stmt_verify = $conn->prepare($sql_verify);
    if(!$stmt_verify) { /* Handle error */ redirect('posts_list.php'); }
    $stmt_verify->bind_param("i", $post_id);
    $stmt_verify->execute();
    $result_verify = $stmt_verify->get_result();
    $post_owner = $result_verify->fetch_assoc();
    $stmt_verify->close();

    if (!$post_owner || $post_owner['user_id'] !== $user_id) {
        $_SESSION['message'] = "You do not have permission to delete this post.";
        $_SESSION['message_type'] = "danger";
        redirect('posts_list.php');
    }

    // Proceed with deletion using prepared statements
    $sql_delete = "DELETE FROM posts WHERE post_id = ? AND user_id = ?"; // Double check user_id
    $stmt_delete = $conn->prepare($sql_delete);

     if ($stmt_delete === false) {
        error_log("Prepare failed (delete post): " . $conn->error);
        $_SESSION['message'] = "An error occurred while preparing to delete the post.";
        $_SESSION['message_type'] = "danger";
        redirect('posts_list.php');
    }

    $stmt_delete->bind_param("ii", $post_id, $user_id);

    if ($stmt_delete->execute()) {
        if ($stmt_delete->affected_rows > 0) {
            $_SESSION['message'] = "Post deleted successfully.";
            $_SESSION['message_type'] = "success";
        } else {
             $_SESSION['message'] = "Could not delete the post or post not found."; // Should not happen due to check above
             $_SESSION['message_type'] = "danger";
        }
    } else {
        error_log("Execute failed (delete post): " . $stmt_delete->error);
        $_SESSION['message'] = "Failed to delete post. Please try again.";
        $_SESSION['message_type'] = "danger";
    }
    $stmt_delete->close();
    redirect('posts_list.php');

}

// --- Handle Update Post (Placeholder - Implement fully later) ---
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    // Get post_id, title, content from $_POST
    // Verify ownership (like in delete)
    // Prepare UPDATE statement
    // Bind parameters
    // Execute
    // Set session message
    // Redirect (e.g., back to post_view.php or posts_list.php)
    $_SESSION['message'] = "Update functionality not yet implemented.";
    $_SESSION['message_type'] = "info";
    redirect('posts_list.php'); // Redirect for now
}

// --- Handle Invalid Actions ---
else {
    $_SESSION['message'] = "Invalid action.";
    $_SESSION['message_type'] = "danger";
    redirect('index.php');
}

// Close connection if it wasn't closed by redirect exit()
if (isset($conn) && $conn instanceof mysqli && $conn->thread_id) {
     $conn->close();
}
?>
