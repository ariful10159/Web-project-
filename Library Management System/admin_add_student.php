<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Admin login check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: signin.php");
    exit();
}

include 'database.php';

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Validation
    if(empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } else {
        try {
            // Check duplicate email
            $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0) {
                $error_message = "Email already exists!";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert student
                $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, 'user')");
                $insert_stmt->bind_param("sss", $username, $email, $hashed_password);

                if($insert_stmt->execute()) {
                    $success_message = "Student added successfully!";
                    $_POST = array(); // Clear form
                } else {
                    throw new Exception("Error: ".$insert_stmt->error);
                }
            }
        } catch(Exception $e) {
            $error_message = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="stylesadmin.css">
    <link rel="stylesheet" href="add_student.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <div class="add-student-container">
                <h1 class="add-student-title">Add New Student</h1>

                <?php if(!empty($success_message)): ?>
                    <div class="add-student-alert success">
                        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if(!empty($error_message)): ?>
                    <div class="add-student-alert error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="add-student-input-group">
                        <label class="add-student-label">
                            Username <span class="required-star">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="username" 
                            class="add-student-input"
                            value="<?php echo $_POST['username'] ?? ''; ?>"
                            required
                        >
                    </div>

                    <div class="add-student-input-group">
                        <label class="add-student-label">
                            Email <span class="required-star">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            class="add-student-input"
                            value="<?php echo $_POST['email'] ?? ''; ?>"
                            required
                        >
                    </div>

                    <div class="add-student-input-group">
                        <label class="add-student-label">
                            Password <span class="required-star">*</span>
                        </label>
                        <div class="password-wrapper">
                            <input 
                                type="password" 
                                name="password" 
                                class="add-student-input"
                                id="passwordInput"
                                required
                            >
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                    </div>

                    <button type="submit" class="add-student-submit">
                        <i class="fas fa-user-plus"></i> Add Student
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Password Toggle
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>