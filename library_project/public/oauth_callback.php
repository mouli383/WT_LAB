<?php
/**
 * LibManage Elite OAuth Callback Handler
 * 🏛️🤝✨ The Nexus Handshake
 */

require_once '../config/connection.php';
$oauth_config = require_once '../config/oauth_config.php';

$provider = $_GET['provider'] ?? '';
$code = $_GET['code'] ?? '';

if (!$provider || !$code || !isset($oauth_config[$provider])) {
    flashMessage("error", "OAuth Handshake Failed: Invalid response.");
    header("Location: login.php"); exit;
}

$config = $oauth_config[$provider];

// 1. Exchange Code for Access Token
$ch = curl_init($config['token_url']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'client_id'     => $config['client_id'],
    'client_secret' => $config['client_secret'],
    'code'          => $code,
    'redirect_uri'  => $config['redirect_uri'],
    'grant_type'    => 'authorization_code'
]));

// GitHub requires a User-Agent and JSON accept header
if ($provider === 'github') {
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'User-Agent: LibManage-App']);
}

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

$access_token = $response['access_token'] ?? '';

if (!$access_token) {
    flashMessage("error", "OAuth Error: Could not retrieve access token.");
    header("Location: login.php"); exit;
}

// 2. Fetch User Information
$ch = curl_init($config['userinfo_url']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $access_token",
    'User-Agent: LibManage-App'
]);
$userinfo = json_decode(curl_exec($ch), true);
curl_close($ch);

// Normalize User Data
$oauth_id = $userinfo['sub'] ?? $userinfo['id'] ?? '';
$email    = $userinfo['email'] ?? '';
$username = $userinfo['name'] ?? $userinfo['login'] ?? 'OAuth_User';

if (!$oauth_id) {
    flashMessage("error", "Identity Crisis: Could not verify OAuth ID.");
    header("Location: login.php"); exit;
}

// 3. Persistent Identity Mapping
$stmt = $pdo->prepare("SELECT * FROM users WHERE oauth_id = ? AND oauth_provider = ?");
$stmt->execute([$oauth_id, $provider]);
$user = $stmt->fetch();

if (!$user) {
    // Check if user exists by email (if available)
    if ($email) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    }

    if ($user) {
        // Map existing user to OAuth
        $stmt = $pdo->prepare("UPDATE users SET oauth_id = ?, oauth_provider = ? WHERE id = ?");
        $stmt->execute([$oauth_id, $provider, $user['id']]);
    } else {
        // Provision New User
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, oauth_id, oauth_provider) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$email ?: $username, 'OAUTH_MANAGED', 'student', $oauth_id, $provider]);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE oauth_id = ?");
        $stmt->execute([$oauth_id]);
        $user = $stmt->fetch();
    }
}

// 4. Establish Session
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

flashMessage("success", "Welcome back, {$_SESSION['username']}. Elite Session Established.");
header("Location: dashboard.php");
exit;
