<?php
include "connection.php";

if (isset($_POST['issue'])) {
    mysqli_query($conn,
        "INSERT INTO issued_books(student_name,book_id,issue_date,status)
         VALUES(
            '".$_POST['student']."',
            '".$_POST['book_id']."',
            '".$_POST['date']."',
            'Issued'
         )"
    );
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

</body>
</html>
