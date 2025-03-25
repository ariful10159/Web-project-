<?php
session_start();

// Admin লগইন চেক
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// 2nd Code-এর মতো ডাটাবেস কানেকশন
include 'database.php';

// রিপোর্ট ফেচ
$sql = "SELECT * FROM book_report ORDER BY report_date DESC";
$result = $conn->query($sql); // Object-Oriented Style

if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Book Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS স্টাইল পূর্বের মতো */
    </style>
</head>
<body>
    <?php include('admin_sidebar.php'); ?>
    
    <div class="admin-container">
        <h1>Book Reports Management 
            <a href="logout.php" style="float:right; color:#e74c3c; text-decoration:none;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </h1>

        <table class="report-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Book Details</th>
                    <th>Issue Description</th>
                    <th>Date Reported</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['user_name']); ?><br>
                                <small><?php echo htmlspecialchars($row['gmail']); ?></small>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['book_name']); ?></strong><br>
                                <em><?php echo htmlspecialchars($row['author']); ?></em>
                            </td>
                            <td><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
                            <td><?php echo $row['report_date']; ?></td>
                            <td class="status-<?php echo strtolower($row['status']); ?>">
                                <?php echo $row['status']; ?>
                            </td>
                            <td>
                                <a href="edit_report.php?id=<?php echo $row['id']; ?>" class="edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_report.php?id=<?php echo $row['id']; ?>" class="delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No reports found in database</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>
</body>
</html>