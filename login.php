<?php
$page_title = 'Login';
require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'includes/header.php';
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h1>

    <form action="auth_action.php" method="POST">
         <input type="hidden" name="action" value="login">

        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username or Email</label>
            <input type="text" name="username" id="username" class="form-input" required>
            </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" id="password" class="form-input" required>
        </div>

        <div>
            <button type="submit" class="w-full btn btn-primary">Login</button>
        </div>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
        Don't have an account? <a href="register.php" class="font-medium text-indigo-600 hover:text-indigo-500">Register here</a>
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>
