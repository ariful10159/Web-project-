<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

include 'database.php';

$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title == "" || $content == "") {
        $error = "Title and content are required!";
    } else {
        $stmt = $conn->prepare("INSERT INTO notices (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            $success = "Notice added successfully!";
        } else {
            $error = "Failed to add notice!";
        }
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM notices WHERE id = $id");
    $success = "Notice deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Notice Board</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="stylesadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-content {
            padding: 30px;
            background: #f7f9fc;
        }

        .form-box {
            background: #fff;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .form-box label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-box input[type="text"],
        .form-box textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.2s;
        }

        .form-box input:focus,
        .form-box textarea:focus {
            border-color: #3b82f6;
            outline: none;
        }

        .form-box button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 18px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        .form-box button:hover {
            background: #2563eb;
        }

        .alert.success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-left: 5px solid #198754;
            border-radius: 4px;
        }

        .alert.error {
            background: #f8d7da;
            color: #842029;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-left: 5px solid #dc3545;
            border-radius: 4px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .styled-table th, .styled-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .styled-table th {
            background: #3b82f6;
            color: white;
            font-weight: 600;
        }

        .styled-table tr:hover {
            background-color: #f1f5f9;
        }

        .styled-table .text-red {
            color: #dc2626;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .form-box, .styled-table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">
        <h1 class="page-title"><i class="fas fa-bullhorn"></i> Manage Notices</h1>

        <?php if ($success): ?>
            <div class="alert success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="form-box">
            <label for="title">Notice Title</label>
            <input type="text" name="title" id="title" placeholder="Enter notice title..." required>

            <label for="content">Notice Content</label>
            <textarea name="content" id="content" placeholder="Write the full notice here..." rows="5" required></textarea>

            <button type="submit"><i class="fas fa-paper-plane"></i> Post Notice</button>
        </form>

        <h2 style="margin-top: 30px;">All Notices</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Posted On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");
                $count = 1;
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this notice?')">
                            <i class="fas fa-trash text-red"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if ($count === 1): ?>
                <tr><td colspan="4">No notices posted.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
