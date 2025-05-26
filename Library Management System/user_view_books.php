<?php
session_start();

// Admin login check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'user') {
    header("Location: signin.php");
    exit();
}

include 'database.php';

$books = [];
$error = "";

try {
    // Fetch all books
    $stmt = $conn->prepare("SELECT * FROM books ORDER BY id DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $books = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch(Exception $e) {
    $error = "Error fetching books: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="user_sidebar.css">

    <link rel="stylesheet" href="user_dashboard.css">
    <link rel="stylesheet" href="book_request.css">
</head>
<body>
    <div class="container">
        <?php include 'user_sidebar.php'; ?>

        <div class="main-content">
            <div class="books-list-container">
                <h1 class="section-title">Book Inventory <span class="book-count">(<?php echo count($books); ?>)</span></h1>

                <?php if($error): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if(empty($books)): ?>
                    <div class="no-books">
                        <i class="fas fa-book-open"></i>
                        <p>No books found in the database</p>
                    </div>
                <?php else: ?>
                    <div class="books-table-wrapper">
                        <table class="books-table">
                            <thead>
                                <tr>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                   
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($books as $book): ?>
                                    <tr>
                                        <td class="book-cover">
                                            <?php if(!empty($book['cover_image'])): ?>
                                                <img src="uploads/books/<?php echo htmlspecialchars($book['cover_image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($book['title']); ?> Cover">
                                            <?php else: ?>
                                                <div class="no-cover">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                                        <td><?php echo htmlspecialchars($book['isbn'] ?? 'N/A'); ?></td>
                                      
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>