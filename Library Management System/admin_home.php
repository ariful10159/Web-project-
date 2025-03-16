<?php
session_start();

// অ্যাডমিন লগইন চেক
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Existing meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- CDN Links Start -->
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="stylesadmin.css">
    <!-- CDN Links End -->
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Welcome Admin, <?php echo $_SESSION['username']; ?></h2>
            <ul class="admin-menu">
                <!-- Book Management -->
                <li class="menu-section">
                    <span><i class="fas fa-book"></i> Book Management</span>
                    <ul>
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

        <div class="main-content">
            <h1>Admin Dashboard</h1>
            <div class="dashboard-stats">
                <!-- এখানে স্ট্যাটিস্টিক্স কার্ড যোগ করুন -->
                <div class="stat-card">
                    <i class="fas fa-book"></i>
                    <h3>Total Books</h3>
                    <p>1,234</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h3>Total Students</h3>
                    <p>567</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-hand-holding"></i>
                    <h3>Issued Books</h3>
                    <p>89</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>