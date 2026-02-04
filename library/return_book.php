<?php
include "connection.php";
$msg = "";

if (isset($_POST['return'])) {

    $student = $_POST['student'];
    $book_id = $_POST['book_id'];
    $return_date = $_POST['return_date'];

    // Check if book is issued
    $check = mysqli_query($conn,
        "SELECT * FROM issued_books 
         WHERE student_name='$student' 
         AND book_id='$book_id' 
         AND status='Issued'"
    );

    if (mysqli_num_rows($check) == 1) {

        // Update issued_books table
        mysqli_query($conn,
            "UPDATE issued_books 
             SET status='Returned', return_date='$return_date'
             WHERE student_name='$student' AND book_id='$book_id'"
        );

        // Increase book quantity
        mysqli_query($conn,
            "UPDATE books 
             SET quantity = quantity + 1 
             WHERE id='$book_id'"
        );

        $msg = "✅ Book returned successfully!";
    } else {
        $msg = "❌ No issued record found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <h1>Return Book</h1>
</header>

<nav class="navbar">
    <ul>
        <li><a href="index.php">Home</a></li>
    </ul>
</nav>

<div class="layout">
    <aside class="side-nav">
        <a href="add_book.php">➕ Add Book</a>
        <a href="view_books.php">📚 View Books</a>
        <a href="issue_book.php">📤 Issue Book</a>
        <a class="active" href="return_book.php">📥 Return Book</a>
        <a href="view_issued_books.php">📋 Issued Books</a>
    </aside>

    <main class="content">
        <div class="welcome">
            <h2>Return Issued Book</h2>

            <?php if ($msg != "") { ?>
                <p style="color:green;"><?php echo $msg; ?></p>
            <?php } ?>

            <form method="post">
                <label>Student Name</label><br>
                <input type="text" name="student" required><br><br>

                <label>Book ID</label><br>
                <input type="number" name="book_id" required><br><br>

                <label>Return Date</label><br>
                <input type="date" name="return_date" required><br><br>

                <button type="submit" name="return">Return Book</button>
            </form>
        </div>
    </main>
</div>

<footer class="footer">
    <p>© 2025–26 Library Management System</p>
</footer>

</body>
</html>
