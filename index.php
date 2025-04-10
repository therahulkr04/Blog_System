<?php
$page_title = 'Home - PHP Blog System';
require_once 'config.php'; // Ensure session is started, $conn is available
require_once 'includes/header.php';
?>

<div class="bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome to the PHP Blog System!</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p class="text-lg text-gray-700 mb-6">
            You are logged in as <strong class="text-indigo-600"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>.
        </p>
        <div class="space-x-4">
             <a href="post_create.php" class="btn btn-primary">Create New Post</a>
             <a href="posts_list.php" class="btn btn-secondary">View Blog Posts</a>
        </div>
    <?php else: ?>
        <p class="text-lg text-gray-700 mb-6">
            Please <a href="login.php" class="text-indigo-600 hover:underline">login</a> or
            <a href="register.php" class="text-indigo-600 hover:underline">register</a> to create posts and participate.
        </p>
         <a href="posts_list.php" class="btn btn-secondary">View Blog Posts</a>
    <?php endif; ?>

    <hr class="my-8">

    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Recent Posts</h2>
    <?php
        // Fetch recent posts (e.g., latest 5)
        $sql = "SELECT p.post_id, p.title, p.content, p.created_at, u.username
                FROM posts p
                JOIN users u ON p.user_id = u.user_id
                ORDER BY p.created_at DESC
                LIMIT 5"; // Get latest 5 posts

        $result = $conn->query($sql); // Using simple query here for read-only, prepared statements better if input involved

        if ($result && $result->num_rows > 0):
    ?>
        <div class="space-y-6">
            <?php while($post = $result->fetch_assoc()): ?>
                <article class="border-b pb-4">
                    <h3 class="text-xl font-semibold mb-1">
                        <a href="post_view.php?id=<?php echo $post['post_id']; ?>" class="text-indigo-700 hover:underline">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">
                        By <?php echo htmlspecialchars($post['username']); ?> on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                    </p>
                    <p class="text-gray-700">
                        <?php
                            // Display a snippet of the content
                            $snippet = strip_tags($post['content']); // Remove HTML tags for snippet
                            echo htmlspecialchars(substr($snippet, 0, 150)) . (strlen($snippet) > 150 ? '...' : '');
                        ?>
                        <a href="post_view.php?id=<?php echo $post['post_id']; ?>" class="text-indigo-600 hover:underline text-sm ml-1">Read More</a>
                    </p>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-600">No blog posts yet. <?php if(isset($_SESSION['user_id'])) echo '<a href="post_create.php" class="text-indigo-600 hover:underline">Create the first one!</a>'; ?></p>
    <?php endif; ?>
    <?php if($result) $result->free(); // Free result set ?>

</div>

<?php require_once 'includes/footer.php'; ?>
