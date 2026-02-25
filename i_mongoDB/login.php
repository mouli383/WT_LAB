login.php
<?php
session_start();
require __DIR__ . '/config/db.php';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
if (!$email || !$password) {
die("Email and password are required.");
}
// Find user
$user = $users->findOne(['email' => $email]);
if (!$user) {
die("User not found. <a href='index.html'>Go back</a>");
}
// Verify password
if (!password_verify($password, $user['password'])) {
die("Invalid password. <a href='index.html'>Go back</a>");
}
// Login success
$_SESSION['user'] = $user['email'];
header("Location: dashboard.php");
exit;
