<!-- 23) user_sidebar.php -->
<?php
// user_sidebar.php
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'user') {
    header("Location: signin.php");
    exit();
}
?>

<div class="user-sidebar">
    <div class="sidebar-header">
        <h2><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    </div>
    
    <nav class="sidebar-nav">
        <a href="home.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : '' ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="view_profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'view_profile.php' ? 'active' : '' ?>">
            <i class="fas fa-user"></i> View Profile
        </a>
        <a href="book_request.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'book_request.php' ? 'active' : '' ?>">
            <i class="fas fa-hand-holding"></i> Book Request
        </a>
        <a href="user_view_books.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'user_view_books.php' ? 'active' : '' ?>">
            <i class="fas fa-book-open"></i> View Books
        </a>
        <a href="book_report.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'book_report.php' ? 'active' : '' ?>">
            <i class="fas fa-chart-bar"></i> Book Report
        </a>
        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</div>