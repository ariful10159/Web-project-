<?php
$host = 'localhost'; // ডাটাবেস হোস্ট
$user = 'root'; // ডিফল্ট ইউজারনেম
$password = ''; // ডিফল্ট পাসওয়ার্ড (XAMPP এ সাধারণত খালি থাকে)
$database = 'signup_db'; // ডাটাবেস নাম

// ডাটাবেস কানেকশন তৈরি করুন
$conn = new mysqli($host, $user, $password, $database);

// কানেকশন চেক করুন
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>