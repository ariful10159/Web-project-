<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: signin.php");
    exit();
}

require_once 'database.php';

$user_id = $_SESSION['user_id'];
$books = [];

// Available books fetch
try {
    $stmt = $conn->prepare("SELECT * FROM books WHERE available = 1");
    $stmt->execute();
    $books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}

// Request handling
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO book_requests 
            (user_id, book_id, status) 
            VALUES (?, ?, 'pending')");
        $stmt->bind_param("ii", $user_id, $book_id);
        
        if($stmt->execute()) {
            $_SESSION['success'] = "Book requested successfully!";
        } else {
            throw new Exception("Request failed!");
        }
    } catch(Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    header("Location: book_request.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Book</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="book_request.css">
</head>
<body>
    <?php include 'user_sidebar.php'; ?>

    <div class="main-content">
        <h1>Available Books</h1>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']) ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']) ?></div>
        <?php endif; ?>

        <div class="book-grid">
            <?php foreach($books as $book): ?>
                <div class="book-card">
                    <img src="uploads/books/<?= $book['cover_image'] ?>" alt="<?= $book['title'] ?>">
                    <h3><?= $book['title'] ?></h3>
                    <p><?= $book['author'] ?></p>
                    <form method="POST">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <button type="submit">Request This Book</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>