        </main> <footer class="bg-white border-t border-gray-200 mt-8 py-4">
        <div class="container mx-auto px-6 text-center text-gray-500 text-sm">
             <p>&copy; <?php echo date("Y"); ?> PHP Blog System. Qazibans, Uttarakhand, India.</p>
        </div>
    </footer>

</body>
</html>
<?php
// Close the database connection if it's still open
// Check if $conn exists and is a valid MySQLi connection object
if (isset($conn) && $conn instanceof mysqli && $conn->thread_id) {
     $conn->close();
}
?>
