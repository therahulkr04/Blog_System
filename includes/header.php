<?php
// Ensure config is included (it starts the session)
// Use require_once if header might be included before config elsewhere
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php'; // Adjust path if needed
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'PHP Blog System'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Add custom CSS if needed */
        .form-input { @apply w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm; }
        .btn { @apply px-4 py-2 rounded-md shadow-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2; }
        .btn-primary { @apply text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500; }
        .btn-secondary { @apply text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-indigo-500; }
        .alert { @apply p-4 mb-4 rounded-md; }
        .alert-success { @apply bg-green-100 border border-green-400 text-green-700; }
        .alert-danger { @apply bg-red-100 border border-red-400 text-red-700; }
        .alert-info { @apply bg-blue-100 border border-blue-400 text-blue-700; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 flex flex-col min-h-screen">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>/index.php" class="text-xl font-bold text-indigo-600 hover:text-indigo-800">PHP Blog</a>
            <div class="flex items-center space-x-4">
                <a href="<?php echo BASE_URL; ?>/index.php" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded">Home</a>
                <a href="<?php echo BASE_URL; ?>/posts_list.php" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded">Blog</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>/post_create.php" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded">New Post</a>
                    <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <a href="<?php echo BASE_URL; ?>/logout.php" class="btn btn-secondary text-sm">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login.php" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded">Login</a>
                    <a href="<?php echo BASE_URL; ?>/register.php" class="btn btn-primary text-sm">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-6 flex-grow">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?php echo $_SESSION['message_type'] === 'success' ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                <?php
                echo htmlspecialchars($_SESSION['message']);
                // Clear the message after displaying it
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            </div>
        <?php endif; ?>

        
