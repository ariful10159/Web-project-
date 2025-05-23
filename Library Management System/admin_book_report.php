<?php
session_start();
require 'database.php';

// Admin authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: signin.php");
    exit();
}

// Fetch reports from database
$reports = [];
$error = '';
try {
    $stmt = $conn->prepare("
        SELECT br.*, u.username, u.email 
        FROM book_report br
        JOIN users u ON br.user_id = u.id
        ORDER BY br.report_date DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $reports = $result->fetch_all(MYSQLI_ASSOC);
} catch(Exception $e) {
    $error = "Error fetching reports: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reports Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_book_report.css">
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    
    <div class="admin-container">
        <div class="header">
            <h1>Book Reports Management</h1>
        </div>

        <?php if($error): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="report-section">
            <div class="table-container">
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
                        <?php if(count($reports) > 0): ?>
                            <?php foreach($reports as $report): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <strong><?= htmlspecialchars($report['username']) ?></strong>
                                            <small><?= htmlspecialchars($report['email']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="book-info">
                                            <strong><?= htmlspecialchars($report['book_name']) ?></strong>
                                            <em><?= htmlspecialchars($report['author']) ?></em>
                                        </div>
                                    </td>
                                    <td><?= nl2br(htmlspecialchars($report['description'])) ?></td>
                                    <td><?= date('M j, Y h:i A', strtotime($report['report_date'])) ?></td>
                                    <td>
                                        <span class="status-badge <?= strtolower($report['status']) ?>">
                                            <?= $report['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit_report.php?id=<?= $report['id'] ?>" class="btn edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="delete_report.php" method="POST">
                                                <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                                                <button type="submit" class="btn delete" 
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-data">No reports found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
