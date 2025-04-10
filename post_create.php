<?php
$page_title = 'Create New Post';
require_once 'config.php';

// --- Authentication Check ---
// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Please login to create a post.";
    $_SESSION['message_type'] = "danger";
    header('Location: login.php');
    exit();
}

require_once 'includes/header.php';
?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Create New Blog Post</h1>

    <form action="post_action.php" method="POST">
        <input type="hidden" name="action" value="create">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Post Title</label>
            <input type="text" name="title" id="title" class="form-input" required>
        </div>

        <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
            <textarea name="content" id="content" rows="10" class="form-input" required></textarea>
            </div>

        <div>
            <button type="submit" class="w-full btn btn-primary">Publish Post</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
