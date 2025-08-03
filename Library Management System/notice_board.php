<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'user') {
    header("Location: signin.php");
    exit();
}
include 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notice Board</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="user_sidebar.css">
    <link rel="stylesheet" href="stylesadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .main-content {
            padding: 30px;
            background-color: #f9fafb;
        }

        .page-title {
            font-size: 28px;
            color: #1e3a8a;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .page-title i {
            margin-right: 10px;
            color: #2563eb;
        }

        .notice-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .notice {
            background: #ffffff;
            border-left: 6px solid #2563eb;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .notice:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .notice h3 {
            margin: 0;
            font-size: 20px;
            color: #1f2937;
        }

        .notice p {
            margin: 10px 0 12px;
            color: #374151;
            line-height: 1.6;
        }

        .notice small {
            color: #6b7280;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .notice h3 {
                font-size: 18px;
            }

            .page-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <?php include 'user_sidebar.php'; ?>

    <div class="main-content">
        <h1 class="page-title"><i class="fas fa-bullhorn"></i> Library Notice Board</h1>

        <div class="notice-list">
            <?php
            $query = "SELECT * FROM notices ORDER BY created_at DESC";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="notice">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                    echo '<small>Posted on: ' . $row['created_at'] . '</small>';
                    echo '</div>';
                }
            } else {
                echo '<p>No notices found.</p>';
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
