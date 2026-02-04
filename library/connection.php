<?php
$conn = mysqli_connect("localhost", "root", "", "library_db",3307);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
