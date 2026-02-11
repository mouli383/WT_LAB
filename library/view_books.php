<?php
include "connection.php";
$result = mysqli_query($conn, "SELECT * FROM books");
?>
<!DOCTYPE html>
<html>
<head>
<title>View Books</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header"><h1>Available Books</h1></header>

<table border="1" width="100%">
<tr>
<th>ID</th><th>Name</th><th>Author</th><th>Category</th><th>Qty</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['book_name'] ?></td>
<td><?= $row['author'] ?></td>
<td><?= $row['category'] ?></td>
<td><?= $row['quantity'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
