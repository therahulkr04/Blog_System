<?php
$page_title = 'Blog Posts';
require_once 'config.php';
require_once 'includes/header.php';
?>

<div class="bg-white p-8 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h1 class="text-3xl font-bold text-gray-800">Blog Posts</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="post_create.php" class="btn btn-primary">Create New Post</a>
        <?php endif; ?>
    </div>

    <?php
    // --- Fetch All Posts ---
    // Join with users table to get the author's username
    $sql = "SELECT p.post_id, p.title, p.content, p.created_at, p.updated_at, u.username
            FROM posts p
            JOIN users u ON p.user_id = u.user_id
            ORDER BY p.created_at DESC"; // Show newest posts first

    // Execute the query (Simple query is okay here as no user input is directly used in the SQL)
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo '<div class="space-y-8">'; // Add space between posts
        // Loop through each post
        while ($post = $result->fetch_assoc()) {
            ?>
            <article class="border p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                <h2 class="text-2xl font-semibold mb-2">
                    <a href="post_view.php?id=<?php echo $post['post_id']; ?>" class="text-indigo-700 hover:underline">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                </h2>
                <p class="text-sm text-gray-500 mb-3">
                    Posted by <strong class="text-gray-700"><?php echo htmlspecialchars($post['username']); ?></strong>
                    on <?php echo date('M d, Y H:i', strtotime($post['created_at'])); ?>
                    <?php if ($post['created_at'] !== $post['updated_at']): ?>
                        (Last updated: <?php echo date('M d, Y H:i', strtotime($post['updated_at'])); ?>)
                    <?php endif; ?>
                </p>
                <div class="text-gray-700 leading-relaxed">
                    <?php
                        // Display a preview (e.g., first 200 characters)
                        // Using mb_substr for multi-byte character safety if needed
                        $content_preview = strip_tags($post['content']); // Remove HTML for preview
                        echo htmlspecialchars(substr($content_preview, 0, 200));
                        if (strlen($content_preview) > 200) {
                            echo '...';
                        }
                    ?>
                </div>
                <div class="mt-4">
                    <a href="post_view.php?id=<?php echo $post['post_id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-medium">Read More &rarr;</a>
                    <?php
                    // Add Edit/Delete links if the logged-in user is the author
                    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) {
                        echo ' | <a href="post_edit.php?id=' . $post['post_id'] . '" class="text-green-600 hover:text-green-800 font-medium">Edit</a>';
                        // Add delete link/button (use POST for delete actions)
                        echo ' | <form action="post_action.php" method="POST" class="inline" onsubmit="return confirm(\'Are you sure you want to delete this post?\');">';
                        echo '<input type="hidden" name="action" value="delete">';
                        echo '<input type="hidden" name="post_id" value="' . $post['post_id'] . '">';
                        // Add CSRF token here in a real application
                        echo '<button type="submit" class="text-red-600 hover:text-red-800 font-medium underline">Delete</button>';
                        echo '</form>';
                    }
                    ?>
                </div>
            </article>
            <?php
        }
        echo '</div>'; // Close space-y-8
        // Free the result set
        $result->free();
    } else {
        // No posts found
        echo '<p class="text-gray-600">No blog posts have been created yet.</p>';
    }
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>
