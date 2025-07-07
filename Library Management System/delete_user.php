<?php
session_start();
include 'database.php';

// Admin verification
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    $_SESSION['error'] = "Unauthorized access!";
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $user_type = $_POST['user_type'];

    try {
        // Validate user type
        $valid_types = ['admin', 'user'];
        if (!in_array($user_type, $valid_types)) {
            throw new Exception("Invalid user type");
        }

        // Start transaction
        $conn->begin_transaction();

        // For users, delete related records first
        if ($user_type === 'user') {
            // Delete from book_requests
            $stmt = $conn->prepare("DELETE FROM book_requests WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            
            // Add other related tables here if needed
            // $stmt = $conn->prepare("DELETE FROM another_table WHERE user_id = ?");
            // $stmt->execute();
        }

        // Delete from main table
        $table = ($user_type == 'admin') ? 'admins' : 'users';
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "User deleted successfully";
            $conn->commit();
        } else {
            $_SESSION['error'] = "User not found or already deleted";
            $conn->rollback();
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

header("Location: admin_students.php");
exit();
?>