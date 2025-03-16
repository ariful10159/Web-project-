<?php
session_start();

// সব সেশন ডেটা ডিলিট করুন
$_SESSION = array();

// সেশন কুকি ডিলিট করুন
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// সেশন সম্পূর্ণ ডেস্ট্রয় করুন
session_destroy();

// সাইন-ইন পেজে রিডাইরেক্ট করুন
header("Location: index.html");
exit();