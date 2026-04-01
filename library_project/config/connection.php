<?php
// ============================================
// connection.php - Database connection & helpers
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host     = 'localhost';
$dbname   = 'library_db';
$db_user  = 'root';
$db_pass  = '';
$port     = 3307;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('<div style="font-family:sans-serif;padding:40px;background:#0f172a;color:#f85149;border-radius:12px;margin:20px;border:1px solid #1e293b;">
        <h2 style="color:#6366f1;">&#9888; Database Connection Failed</h2>
        <p style="color:#94a3b8;">' . htmlspecialchars($e->getMessage()) . '</p>
        <p style="color:#94a3b8;">Make sure XAMPP is running and the database <strong>library_db</strong> exists on port 3307.</p>
    </div>');
}

// ─── Helper Functions ───────────────────────────

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireRole($roles) {
    requireLogin();
    $roles = (array)$roles;
    if (!in_array($_SESSION['role'], $roles)) {
        $_SESSION['error'] = 'You do not have permission to access that page.';
        header('Location: index.php');
        exit;
    }
}

function getSession($key, $default = '') {
    return $_SESSION[$key] ?? $default;
}

function flashMessage($type, $message) {
    $_SESSION['flash_type']    = $type;
    $_SESSION['flash_message'] = $message;
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'] ?? 'info';
        $msg  = $_SESSION['flash_message'];
        unset($_SESSION['flash_type'], $_SESSION['flash_message']);
        return ['type' => $type, 'message' => $msg];
    }
    return null;
}

function sanitize($input) {
    return htmlspecialchars(trim(addslashes($input)));
}

function calcFine($due_date) {
    $today = new DateTime();
    $due   = new DateTime($due_date);
    if ($today > $due) {
        $diff = $today->diff($due)->days;
        return $diff * 5; // ₹5 per day
    }
    return 0;
}
?>