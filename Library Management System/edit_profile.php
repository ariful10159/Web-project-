<?php
// edit_profile.php
session_start();

// সিকিউরিটি চেক
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: signin.php");
    exit();
}

require_once 'database.php';

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// ডাটাবেস থেকে বর্তমান ডেটা লোড করুন
try {
    $stmt = $conn->prepare("SELECT username, email, profile_image FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_data = $result->fetch_assoc();
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $profile_image = $current_data['profile_image']; // ডিফল্ট হিসেবে পুরনো ইমেজ

    // ভ্যালিডেশন
    if(empty($username) || empty($email)) {
        $error = "Username and Email are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        try {
            // প্রোফাইল ইমেজ হ্যান্ডলিং
            if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['profile_image'];
                
                // ফাইল ভ্যালিডেশন
                $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $max_size = 2 * 1024 * 1024; // 2MB

                if(!in_array($file_ext, $allowed_types)) {
                    throw new Exception("Only JPG, JPEG, PNG & WEBP files are allowed!");
                }

                if($file['size'] > $max_size) {
                    throw new Exception("File size must be less than 2MB!");
                }

                // নতুন ফাইলনেম জেনারেট করুন
                $new_filename = uniqid('profile_', true) . '.' . $file_ext;
                $upload_dir = "uploads/profiles/";
                
                if(!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                if(move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
                    // পুরনো ইমেজ ডিলিট করুন (যদি থাকে)
                    if(!empty($current_data['profile_image'])) {
                        unlink($upload_dir . $current_data['profile_image']);
                    }
                    $profile_image = $new_filename;
                } else {
                    throw new Exception("Error uploading file!");
                }
            }

            // ডাটাবেস আপডেট
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_image = ? WHERE id = ?");
            $stmt->bind_param("sssi", $username, $email, $profile_image, $user_id);
            
            if($stmt->execute()) {
                // সেশন ডেটা আপডেট করুন
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                $success = "Profile updated successfully!";
                $current_data = ['username' => $username, 'email' => $email, 'profile_image' => $profile_image];
            } else {
                throw new Exception("Error updating profile: " . $conn->error);
            }
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Library System</title>
    <link rel="stylesheet" href="user_sidebar.css">
    <link rel="stylesheet" href="edit_profile.css">
</head>
<body>
    <?php include 'user_sidebar.php'; ?>

    <div class="main-content">
        <div class="edit-profile-container">
            <h1><i class="fas fa-user-edit"></i> Edit Profile</h1>

            <?php if($error): ?>
                <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($current_data['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($current_data['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-camera"></i> Profile Picture</label>
                    <div class="image-upload">
                        <div class="preview-area">
                            <?php if(!empty($current_data['profile_image'])): ?>
                                <img src="uploads/profiles/<?php echo $current_data['profile_image']; ?>" alt="Current Profile">
                            <?php else: ?>
                                <div class="default-image"><i class="fas fa-user-circle"></i></div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="profile_image" accept="image/*">
                        <small>Max 2MB (JPEG, PNG, WEBP)</small>
                    </div>
                </div>

                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    <script>
    // ইমেজ প্রিভিউ স্ক্রিপ্ট
    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        const preview = document.querySelector('.preview-area');
        const file = e.target.files[0];
        
        if(file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="New Profile Preview">`;
            }
            
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>
</html>