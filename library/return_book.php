<?php
include "connection.php";
$msg = "";

if (isset($_POST['return'])) {

    $student = $_POST['student'];
    $book_id = $_POST['book_id'];
    $return_date = $_POST['return_date'];

    $check = mysqli_query($conn,
        "SELECT * FROM issued_books 
         WHERE student_name='$student' 
         AND book_id='$book_id' 
         AND status='Issued'");

    if (mysqli_num_rows($check) == 1) {

        mysqli_query($conn,
            "UPDATE issued_books 
             SET status='Returned', return_date='$return_date'
             WHERE student_name='$student' AND book_id='$book_id'");

        mysqli_query($conn,
            "UPDATE books 
             SET quantity = quantity + 1 
             WHERE id='$book_id'");

        $msg = "Book returned successfully!";
    } else {
        $msg = "No issued record found!";
    }
}
?>
