<?php
session_start();

// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: signin.php");
    exit();
}

include 'database.php';

// Upload settings
$upload_dir = "uploads/books/";
$allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
$max_size = 2 * 1024 * 1024; // 2MB

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn'] ?? '');
    $cover_image = null;

    // Validation
    if(empty($title) || empty($author)) {
        $error = "Title and Author are required!";
    } else {
        // File upload handling
        if(isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['cover_image'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if(!in_array($file_ext, $allowed_types)) {
                $error = "Only JPG, JPEG, PNG & WEBP files are allowed!";
            } elseif($file['size'] > $max_size) {
                $error = "File size must be less than 2MB!";
            } else {
                $filename = uniqid('book_', true) . '.' . $file_ext;
                $target_path = $upload_dir . $filename;
                
                if(move_uploaded_file($file['tmp_name'], $target_path)) {
                    $cover_image = $filename;
                } else {
                    $error = "Error uploading file!";
                }
            }
        }

        // Database insert if no errors
        if(empty($error)) {
            $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, cover_image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $author, $isbn, $cover_image);
            
            if($stmt->execute()) {
                $success = "Book added successfully!";
                $_POST = []; // Clear form
            } else {
                $error = "Database error: " . $conn->error;
                // Cleanup uploaded file if database failed
                if(isset($target_path) && file_exists($target_path)) {
                    unlink($target_path);
                }
            }
        }
    }
}

// Create upload directory if needed
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link rel="stylesheet" href="stylesadmin.css">
    <link rel="stylesheet" href="add_book.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'admin_sidebar.php'; ?>

        <div class="main-content">
            <div class="add-book-container">
                <h1 class="add-book-title">Add New Book</h1>

                <?php if($success): ?>
                    <div class="add-book-alert success">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="add-book-alert error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="add-book-input-group">
                        <label class="add-book-label">
                            Title <span class="required-star">*</span>
                        </label>
                        <input type="text" name="title" class="add-book-input" 
                               value="<?php echo $_POST['title'] ?? ''; ?>" required>
                    </div>

                    <div class="add-book-input-group">
                        <label class="add-book-label">
                            Author <span class="required-star">*</span>
                        </label>
                        <input type="text" name="author" class="add-book-input" 
                               value="<?php echo $_POST['author'] ?? ''; ?>" required>
                    </div>

                    <div class="add-book-input-group">
                        <label class="add-book-label">ISBN</label>
                        <input type="text" name="isbn" class="add-book-input" 
                               value="<?php echo $_POST['isbn'] ?? ''; ?>">
                    </div>

                    <div class="add-book-input-group">
                        <label class="add-book-label">
                            Cover Image <span class="file-note">(Max 2MB, JPG/PNG/WEBP)</span>
                        </label>
                        <input type="file" name="cover_image" class="add-book-input" accept="image/*">
                    </div>

                    <button type="submit" class="add-book-submit">
                        <i class="fas fa-book-medical"></i> Add Book
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Basic image preview (optional - can be removed if not needed)
    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.add-book-input-group:last-child').insertAdjacentHTML(
                    'beforeend', 
                    '<div class="preview-image"><img src="' + e.target.result + '"></div>'
                );
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    </script>
</body>
</html>