<?php
session_start();
include "connection.php";

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <h1>User Login</h1>
</header>

<nav class="navbar">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a class="active" href="login.php">Login</a></li>
    </ul>
</nav>

<div class="layout">
    <main class="content">
        <div class="welcome" style="max-width:400px;margin:auto;">
            <h2>Login</h2>

            <?php if ($error != "") { ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php } ?>

            <form method="post">
                <label>Username</label><br>
                <input type="text" name="username" required><br><br>

                <label>Password</label><br>
                <input type="password" name="password" required><br><br>

                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </main>
</div>

<footer class="footer">
    <p>© 2025–26 Library Management System</p>
</footer>

</body>
</html>
