<?php
session_start();

// ইউজার লগইন এবং টাইপ চেক
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="styleshome.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <ul>
                <li><a href="home.php">Dashboard</a></li>
                <li><a href="view_profile.php">View Profile</a></li>
                <li><a href="book_request.php">Book Request</a></li>
                <li><a href="book_view.php">Book View</a></li>
                <li><a href="book_report.php">Book Report</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <h1>Your Profile</h1>
            
            <div class="profile-info">
                <div class="info-item">
                    <span class="label">Username:</span>
                    <span class="value"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="label">Email:</span>
                    <span class="value"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="label">Account Type:</span>
                    <span class="value"><?php echo ucfirst($_SESSION['user_type']); ?></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>