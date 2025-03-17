<?php
session_start();


// সেশন চেক করুন
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: signin.php");
    exit();
}

// এরপর সাইডবার ইনক্লুড করুন
include 'admin_sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesadmin.css">
</head>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>
        
        <div class="main-content">
            <h1>Admin Dashboard</h1>
            <div class="dashboard-stats">
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
