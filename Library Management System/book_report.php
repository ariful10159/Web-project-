<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "signup_db");

// রিপোর্ট সাবমিট হ্যান্ডলার
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $gmail = mysqli_real_escape_string($conn, $_POST['gmail']);
    $book_name = mysqli_real_escape_string($conn, $_POST['book_name']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Prepared Statement ব্যবহার করুন (সিকিউরিটির জন্য)
    $stmt = $conn->prepare("INSERT INTO book_report (user_name, gmail, book_name, author, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $user_name, $gmail, $book_name, $author, $description);
    
    if ($stmt->execute()) {
        $_SESSION['user_email'] = $gmail;
        echo "<script>alert('Report submitted!');</script>";
    } else {
        echo "<script>alert('Error!');</script>";
    }
    $stmt->close();
}

// ইউজারের রিপোর্ট ফেচ করুন
$reports = [];
if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $result = mysqli_query($conn, "SELECT * FROM book_report WHERE gmail = '$user_email' ORDER BY report_date DESC");
    $reports = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Book Report</title>
    <!-- Sidebar CSS (assumes you have user_sidebar.css already) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="user_sidebar.css">
    <link rel="stylesheet" href="book_report.css">

</head>
<body>
    <div class="container">
        <!-- Include the sidebar -->
        <?php include 'user_sidebar.php'; ?>

        <!-- Main content area -->
        <div class="main-content">
            <!-- রিপোর্ট জমা ফর্ম -->
            <div class="form-section">
                <h2>Report a Book Issue</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Your Name:</label>
                        <input type="text" name="user_name" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="gmail" required>
                    </div>
                    <div class="form-group">
                        <label>Book Name:</label>
                        <input type="text" name="book_name" required>
                    </div>
                    <div class="form-group">
                        <label>Author:</label>
                        <input type="text" name="author" required>
                    </div>
                    <div class="form-group">
                        <label>Issue Description:</label>
                        <textarea name="description" rows="4" required></textarea>
                    </div>
                    <button type="submit">Submit Report</button>
                </form>
            </div>

            <!-- জমা দেওয়া রিপোর্ট লিস্ট -->
            <!-- <div class="report-section">
                <h2>Your Submitted Reports</h2>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
                        <div class="report-card">
                            <h3><?php echo htmlspecialchars($report['book_name']); ?></h3>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($report['author']); ?></p>
                            <p><strong>Issue:</strong> <?php echo nl2br(htmlspecialchars($report['description'])); ?></p>
                            <p><em>Submitted on: <?php echo $report['report_date']; ?></em></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reports submitted yet.</p>
                <?php endif; ?>
            </div> -->
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
