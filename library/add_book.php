<?php
include "connection.php";
$msg = "";

if (isset($_POST['add'])) {
    $name = $_POST['book_name'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $qty = $_POST['quantity'];

    mysqli_query($conn,
        "INSERT INTO books(book_name,author,category,quantity)
         VALUES('$name','$author','$category','$qty')"
    );
    $msg = "Book added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header"><h1>Add Book</h1></header>

<main class="content">
<div class="welcome">
<form method="post">
    <input name="book_name" placeholder="Book Name" required><br><br>
    <input name="author" placeholder="Author" required><br><br>
    <input name="category" placeholder="Category"><br><br>
    <input type="number" name="quantity" placeholder="Quantity" required><br><br>
    <button name="add">Add Book</button>
</form>
<p><?= $msg ?></p>
</div>
</main>

</body>
</html>
