<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'user') {
    header("Location: signin.php");
    exit();
}

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];

    try {
        // ইউজার ডেটা ফেচ করুন
        $user_stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user = $user_stmt->get_result()->fetch_assoc();

        if(!$user) {
            throw new Exception("User not found!");
        }

        // বই ডেটা ফেচ করুন
        $book_stmt = $conn->prepare("SELECT title, author, cover_image FROM books WHERE id = ?");
        $book_stmt->bind_param("i", $book_id);
        $book_stmt->execute();
        $book = $book_stmt->get_result()->fetch_assoc();

        if(!$book) {
            throw new Exception("Book not found!");
        }

        // রিকুয়েস্ট ডেটা ইনসার্ট করুন
        $stmt = $conn->prepare("INSERT INTO book_requests 
            (user_id, username, email, book_id, book_title, author, cover_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ississs", 
            $user_id,
            $user['username'],
            $user['email'],
            $book_id,
            $book['title'],
            $book['author'],
            $book['cover_image']
        );

        if($stmt->execute()) {
            $_SESSION['success'] = "Book request submitted successfully!";
        } else {
            throw new Exception("Request failed: " . $conn->error);
        }
    } catch(Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

header("Location: book_request.php");
exit();
?>