<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <h1>📚 Library Management System</h1>
</header>

<nav class="navbar">
    <ul>
        <li><a class="active" href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<div class="layout">
<aside class="side-nav">
    <a href="add_book.php">➕ Add Book</a>
    <a href="view_books.php">📚 View Books</a>
    <a href="issue_book.php">📤 Issue Book</a>
    <a href="return_book.php">📥 Return Book</a>
    <a href="view_issued_book.php">📋 Issued Books</a>

    <hr style="margin:15px 0;">

    <a href="file_manager.php">📁 File Manager</a>
    <a href="file_functions_demo.php">🧪 File Functions</a>
    <a href="file_modes_demo.php">📘 File Modes</a>
</aside>

<main class="content">
<div class="welcome">
<h2>Welcome</h2>
<p>This system manages library books AND includes a Mini File Manager.</p>
</div>
</main>
</div>

<footer class="footer">
<p>© 2025–26 Library + File Manager System</p>
</footer>

</body>
</html>
