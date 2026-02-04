<?php
include "connection.php";
$result = mysqli_query($conn, "SELECT * FROM issued_books");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issued Books</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header"><h1>Issued Books</h1></header>

<table border="1" width="100%">
<tr>
    <th>Student</th><th>Book ID</th><th>Date</th><th>Status</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['student_name'] ?></td>
    <td><?= $row['book_id'] ?></td>
    <td><?= $row['issue_date'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
