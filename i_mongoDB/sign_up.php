<?php
session_start();
require __DIR__ . '/config/db.php';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
if (!$email || !$password) {
die("Email and password are required.");
}
// Check if user already exists
$existingUser = $users->findOne(['email' => $email]);
if ($existingUser) {
die("User already exists. <a href='index.html'>Go back</a>");
}
// Hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
// Insert user
$users->insertOne([
'email' => $email,
'password' => $hashedPassword,
'createdAt' => date('Y-m-d H:i:s')
]);
echo "Signup successful! <a href='index.html'>Go to Login</a>";