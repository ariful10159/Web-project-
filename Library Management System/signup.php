<?php
include 'database.php'; // ডাটাবেস কানেকশন

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['userType']; // ইউজার টাইপ (user বা admin)
    $username = htmlspecialchars($_POST['username']); // স্যানিটাইজেশন
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // স্যানিটাইজেশন
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // পাসওয়ার্ড হ্যাশিং

    // টেবিল সিলেক্ট করুন (user বা admin)
    $table = ($userType == 'admin') ? 'admins' : 'users';

    // প্রিপেয়ার্ড স্টেটমেন্ট ব্যবহার করুন
    $stmt = $conn->prepare("INSERT INTO $table (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>