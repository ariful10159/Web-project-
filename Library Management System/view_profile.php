<?php
// view_profile.php
session_start();

// সিকিউরিটি চেক
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: signin.php");
    exit();
}

require_once 'database.php';

// ডাটাবেস থেকে ইউজার ডেটা ফেচ করুন
$user_id = $_SESSION['user_id'];
$user_data = [];

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    } else {
        throw new Exception("User not found!");
    }
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile - Library System</title>
    
    <!-- CDN লিংক -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- কাস্টম CSS -->
    <link rel="stylesheet" href="user_sidebar.css">
    <link rel="stylesheet" href="view_profile.css">
</head>
<body>
    <div class="container">
        <?php include 'user_sidebar.php'; ?>

        <div class="main-content">
            <!-- প্রোফাইল হেডার -->
            <div class="profile-header">
                <h1><i class="fas fa-user-circle"></i> My Profile</h1>
                <a href="edit_profile.php" class="edit-btn">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>

            <!-- প্রোফাইল ইনফো কার্ড -->
            <div class="profile-card">
                <div class="profile-section">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user-tag"></i>
                            Username
                        </div>
                        <div class="info-value"><?= htmlspecialchars($user_data['username']) ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i>
                            Email
                        </div>
                        <div class="info-value"><?= htmlspecialchars($user_data['email']) ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-day"></i>
                            Member Since
                        </div>
                        <div class="info-value">
                            <?= date('d M Y', strtotime($user_data['created_at'])) ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-key"></i>
                            Password
                        </div>
                        <div class="info-value">
                            ********
                            <a href="change_password.php" class="change-password-link">
                                <i class="fas fa-lock"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>

                <!-- প্রোফাইল ইমেজ -->
                <div class="profile-image-section">
                    <div class="profile-image">
                        <?php if(!empty($user_data['profile_image'])): ?>
                            <img src="uploads/profiles/<?= $user_data['profile_image'] ?>" alt="Profile Image">
                        <?php else: ?>
                            <div class="default-image">
                                <i class="fas fa-user-astronaut"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- স্ক্রিপ্ট -->
    <script>
    // মোবাইলের জন্য সাইডবার টগল
    document.querySelector('.sidebar-toggle').addEventListener('click', () => {
        document.querySelector('.user-sidebar').classList.toggle('active');
    });
    </script>
</body>
</html>