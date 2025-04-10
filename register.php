<?php
$page_title = 'Register';
require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'includes/header.php';
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Create Account</h1>

    <form action="auth_action.php" method="POST">
        <input type="hidden" name="action" value="register">

        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" name="username" id="username" class="form-input" required>
            </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" class="form-input" required>
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" id="password" class="form-input" required>
            </div>

        <div class="mb-6">
            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-input" required>
        </div>

        <div>
            <button type="submit" class="w-full btn btn-primary">Register</button>
        </div>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
        Already have an account? <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-500">Login here</a>
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>
