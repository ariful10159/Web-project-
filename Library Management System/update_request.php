<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    try {
        // Update request status
        $status = ($action == 'approve') ? 'approved' : 'rejected';
        $stmt = $conn->prepare("UPDATE book_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $request_id);
        $stmt->execute();

        // If approved, mark book as unavailable
        if ($action == 'approve') {
            // Get book_id from the request
            $stmt = $conn->prepare("SELECT book_id FROM book_requests WHERE id = ?");
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
            $book_id = $stmt->get_result()->fetch_assoc()['book_id'];

            // Update book availability
            $update_book = $conn->prepare("UPDATE books SET available = 0 WHERE id = ?");
            $update_book->bind_param("i", $book_id);
            $update_book->execute();
        }

        $_SESSION['success'] = "Request $status successfully!";
    } catch(Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

header("Location: admin_book_requests.php");
exit();
?>