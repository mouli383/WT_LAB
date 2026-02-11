<?php
include "connection.php";
$msg="";

if (isset($_POST['issue'])) {

    $student = $_POST['student'];
    $book_id = $_POST['book_id'];
    $date = $_POST['date'];

    $checkQty = mysqli_query($conn,"SELECT quantity FROM books WHERE id='$book_id'");
    $data = mysqli_fetch_assoc($checkQty);

    if ($data && $data['quantity'] > 0) {

        mysqli_query($conn,
        "INSERT INTO issued_books(student_name,book_id,issue_date,status)
         VALUES('$student','$book_id','$date','Issued')");

        mysqli_query($conn,
        "UPDATE books SET quantity = quantity - 1 WHERE id='$book_id'");

        $msg="Book Issued Successfully!";
    } else {
        $msg="Book not available!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Issue Book</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header"><h1>Issue Book</h1></header>

<form method="post">
<input name="student" placeholder="Student Name" required><br><br>
<input name="book_id" placeholder="Book ID" required><br><br>
<input type="date" name="date" required><br><br>
<button name="issue">Issue</button>
</form>

<p><?= $msg ?></p>

</body>
</html>
