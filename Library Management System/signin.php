<?php
include 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['userType'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // টেবিল সিলেক্ট করুন
    $table = ($userType == 'admin') ? 'admins' : 'users';

    // প্রিপেয়ার্ড স্টেটমেন্ট
    $stmt = $conn->prepare("SELECT id, username, email, password FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // সেশন ডেটা সেট করুন
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $userType;
            
            // ইউজার টাইপ অনুযায়ী রিডাইরেক্ট
            if ($userType == 'admin') {
                header("Location: admin_home.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location='signin.php';</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.location='signin.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>