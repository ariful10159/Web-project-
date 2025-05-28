<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: signin.php");
    exit();
}
include 'database.php';
// Display messages
if (isset($_SESSION['message'])) {
    echo '<div class="alert success">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert error">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
//  admin list
$admin_query = "SELECT id, username, email, 'admin' AS user_type FROM admins";
$admin_result = $conn->query($admin_query);

// student list
$student_query = "SELECT id, username, email, 'user' AS user_type FROM users WHERE user_type='user'";
$student_result = $conn->query($student_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="stylesadmin.css">
    <link rel="stylesheet" href="student_list.css">
</head>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <h1>User Management</h1>

            <!-- Admin List Section -->
            <div class="user-list-section">
                <h2 class="section-title">Admin List</h2>
                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($admin_result->num_rows > 0): ?>
                                <?php while($row = $admin_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><span class="user-type admin"><?= $row['user_type'] ?></span></td>
                                        <td>
                                            <form method="POST" action="delete_user.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="user_type" value="admin">
                                                <button type="submit" class="btn-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="no-data">No admins found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Student List Section -->
            <div class="user-list-section">
                <h2 class="section-title">Student List</h2>
                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($student_result->num_rows > 0): ?>
                                <?php while($row = $student_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><span class="user-type student"><?= $row['user_type'] ?></span></td>
                                        <td>
                                            <form method="POST" action="delete_user.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="user_type" value="user">
                                                <button type="submit" class="btn-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="no-data">No students found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>