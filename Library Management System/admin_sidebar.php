<?php
// admin_sidebar.php

// Remove session_start() from here
// session_start(); // এটিকে মুছে ফেলুন

// Security check (যদি সেশন শুরু করা হয়ে থাকে)
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: signin.php");
    exit();
}
?>
<div class="sidebar">
    <h2>Welcome Admin, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <ul class="admin-menu">
        <!-- Book Management -->
        <li class="menu-section">
             <ul>
                <li><i class="fas fa-book"></i> Book Management</span>
                <li><a href="admin_home.php"><i class="fas fa-plus"></i> Admin Deshboard</a></li>
                <li><a href="admin_add_book.php"><i class="fas fa-plus"></i> Add Book</a></li>
                <li><a href="admin_books.php"><i class="fas fa-book-open"></i> View Books</a></li>
                <li><a href="admin_book_requests.php"><i class="fas fa-clipboard-list"></i> Book Requests</a></li>
            </ul>
        </li>

        <!-- Student Management -->
        <li class="menu-section">
            <span><i class="fas fa-users"></i> Student Management</span>
            <ul>
                <li><a href="admin_add_student.php"><i class="fas fa-user-plus"></i> Add Student</a></li>
                <li><a href="admin_students.php"><i class="fas fa-list"></i> Student List</a></li>
            </ul>
        </li>

        <!-- Issue Management -->
        <li class="menu-section">
            <span><i class="fas fa-hand-holding"></i> Issue Management</span>
            <ul>
                <li><a href="admin_issue_book.php"><i class="fas fa-share-square"></i> Issue Book</a></li>
                <li><a href="admin_issued_books.php"><i class="fas fa-clipboard-check"></i> Issued Books</a></li>
            </ul>
        </li>

        <!-- Reports -->
        <li class="menu-section">
            <span><i class="fas fa-chart-bar"></i> Reports</span>
            <ul>
                <li><a href="admin_book_report.php"><i class="fas fa-book"></i> Book Report</a></li>
                <li><a href="admin_student_report.php"><i class="fas fa-user-graduate"></i> Student Report</a></li>
                <li><a href="admin_issue_report.php"><i class="fas fa-file-alt"></i> Issue Report</a></li>
            </ul>
        </li>

        <!-- Logout -->
        <li class="logout-link">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</div>