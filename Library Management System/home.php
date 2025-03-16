<?php
session_start();

// ইউজার লগইন এবং টাইপ চেক
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'user') {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home Page</title>
    
    <!-- CDN Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styleshome.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <ul>
                <li>
                    <a href="view_profile.php">
                        <i class="fas fa-user-circle"></i>
                        View Profile
                    </a>
                </li>
                <li>
                    <a href="book_request.php">
                        <i class="fas fa-book-medical"></i>
                        Book Request
                    </a>
                </li>
                <li>
                    <a href="book_view.php">
                        <i class="fas fa-book-open"></i>
                        Book View
                    </a>
                </li>
                <li>
                    <a href="book_report.php">
                        <i class="fas fa-file-alt"></i>
                        Book Report
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <h1>User Dashboard</h1>
            <div class="welcome-message">
                <i class="fas fa-heart"></i>
                <p>Welcome to your dashboard. Here you can manage your profile, request books, view books, and generate reports.</p>
            </div>
        </div>
    </div>
</body>
</html>