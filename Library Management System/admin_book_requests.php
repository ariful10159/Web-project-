<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: signin.php");
    exit();
}

include 'database.php';

// Fetch all book requests
$query = "
    SELECT 
        br.id AS request_id,
        u.username,
        u.email,
        b.title AS book_title,
        b.author,
        br.requested_at,
        br.status
    FROM 
        book_requests br
    JOIN 
        users u ON br.user_id = u.id
    JOIN 
        books b ON br.book_id = b.id
    ORDER BY 
        br.requested_at DESC
";

$requests = [];
try {
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Book Requests</title>
    <link rel="stylesheet" href="stylesadmin.css">
    <style>
        .request-table { width: 100%; border-collapse: collapse; }
        .request-table th, .request-table td { padding: 12px; text-align: left; }
        .status-pending { color: #e67e22; }
        .status-approved { color: #2ecc71; }
        .status-rejected { color: #e74c3c; }
        .action-btns form { display: inline-block; margin-right: 5px; }
        .btn { padding: 5px 10px; border: none; border-radius: 3px; color: white; }
        .approve-btn { background: #27ae60; }
        .reject-btn { background: #c0392b; }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">
        <h1>Manage Book Requests</h1>

        <table class="request-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Book</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($requests as $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['username']) ?><br>
                            <small><?= htmlspecialchars($request['email']) ?></small></td>
                        <td><?= htmlspecialchars($request['book_title']) ?><br>
                            <em><?= htmlspecialchars($request['author']) ?></em></td>
                        <td><?= $request['requested_at'] ?></td>
                        <td class="status-<?= strtolower($request['status']) ?>">
                            <?= ucfirst($request['status']) ?>
                        </td>
                        <td>
                            <?php if($request['status'] == 'pending'): ?>
                                <form action="update_request.php" method="POST">
                                    <input type="hidden" name="request_id" value="<?= $request['request_id'] ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn approve-btn">Approve</button>
                                </form>
                                <form action="update_request.php" method="POST">
                                    <input type="hidden" name="request_id" value="<?= $request['request_id'] ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="btn reject-btn">Reject</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>