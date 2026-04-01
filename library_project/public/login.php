<?php
require_once '../config/connection.php';
$page_title = 'System Entry';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    if ($action === 'register') {
        $role = $_POST['role'] ?? 'student';
        if ($user && $pass) {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$user, $pass, $role]);
                flashMessage("success", "Registration successful! Please log in.");
                header("Location: login.php"); exit;
            } catch (Exception $e) { $error = "Username already exists."; }
        } else { $error = "All fields are required."; }
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->execute([$user, $pass]);
        $userData = $stmt->fetch();
        if ($userData) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role'];
            header("Location: dashboard.php"); exit;
        } else { $error = "Invalid credentials."; }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibManage | Luxe Entry Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <style>
    <style>
        .auth-wrapper { 
            min-height: 100vh; display: flex; align-items: center; justify-content: center; 
            padding: 40px 20px; position: relative; overflow: hidden;
        }
        .auth-blob { position: absolute; width: 600px; height: 600px; background: var(--primary-dim); filter: blur(120px); z-index: -1; animation: pulse 10s infinite; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .auth-card { width: 440px; padding: 60px; text-align: center; position: relative; z-index: 10; margin: auto; }
        .form-label { display: block; text-align: left; font-size: 11px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; color: var(--text-dim); margin-bottom: 8px; margin-top: 24px; }
    </style>
</head>
<body>
    <div id="preloader"><i id="loader-icon" class="fas fa-lock fa-spin-pulse"></i></div>
    
    <div class="auth-wrapper">
        <div class="auth-blob"></div>

        <div class="glass-card auth-card">
            <div class="logo" style="margin-bottom:12px;">LIB<span>MANAGE</span></div>
            <p class="sub-title" style="margin-bottom:40px;" id="authDesc">Access Elite Infrastructure</p>

            <?php if (isset($error)): ?>
                <div style="background:rgba(239,68,68,0.1); color:#f87171; padding:12px; border-radius:12px; font-size:13px; margin-bottom:24px; font-weight:600;">
                    <i class="fas fa-circle-exclamation" style="margin-right:8px;"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($msg = getFlashMessage()): ?>
                <div style="background:rgba(52,211,153,0.1); color:#34d399; padding:12px; border-radius:12px; font-size:13px; margin-bottom:24px; font-weight:600;">
                    <i class="fas fa-circle-check" style="margin-right:8px;"></i> <?= $msg['message'] ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="authForm">
                <input type="hidden" name="action" id="actionInput" value="login">
                
                <div class="form-group">
                    <label class="form-label">Identifier</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Security Key</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div id="registerFields" style="display:none;">
                    <label class="form-label">Assigned Role</label>
                    <select name="role" class="form-control">
                        <option value="student">Student Node</option>
                        <option value="librarian">Librarian Operator</option>
                        <option value="admin">System Administrator</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:40px; height:56px;" id="submitBtn">Launch Session</button>
            </form>

            <div style="margin: 32px 0; display:flex; align-items:center; gap:16px;">
                <div style="flex:1; height:1px; background:var(--border);"></div>
                <div style="font-size:10px; font-weight:900; color:var(--text-dim); text-transform:uppercase; letter-spacing:2px;">Elite Entry</div>
                <div style="flex:1; height:1px; background:var(--border);"></div>
            </div>

            <div style="display:flex; gap:12px;">
                <?php 
                    $oauth = require_once '../config/oauth_config.php';
                    $googleUrl = $oauth['google']['auth_url'] . "?" . http_build_query([
                        'client_id' => $oauth['google']['client_id'],
                        'redirect_uri' => $oauth['google']['redirect_uri'],
                        'response_type' => 'code',
                        'scope' => $oauth['google']['scope']
                    ]);
                    $githubUrl = $oauth['github']['auth_url'] . "?" . http_build_query([
                        'client_id' => $oauth['github']['client_id'],
                        'redirect_uri' => $oauth['github']['redirect_uri'],
                        'scope' => $oauth['github']['scope']
                    ]);
                ?>
                <a href="<?= $googleUrl ?>" class="btn btn-secondary" style="flex:1; height:48px; font-size:11px; font-weight:800;"><i class="fab fa-google" style="color:#4285F4;"></i> GOOGLE</a>
                <a href="<?= $githubUrl ?>" class="btn btn-secondary" style="flex:1; height:48px; font-size:11px; font-weight:800;"><i class="fab fa-github"></i> GITHUB</a>
            </div>

            <div style="margin-top:40px; font-size:13px; color:var(--text-muted);">
                <span id="toggleText">New to the infrastructure?</span> 
                <a href="#" onclick="toggleAuthView(event)" id="toggleLink" style="color:var(--primary); font-weight:700; margin-left:4px;">Request Access</a>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script>
        function toggleAuthView(e) {
            if (e) e.preventDefault();
            const action = document.getElementById('actionInput');
            const regFields = document.getElementById('registerFields');
            const submitBtn = document.getElementById('submitBtn');
            const toggleLink = document.getElementById('toggleLink');
            const toggleText = document.getElementById('toggleText');
            const authDesc = document.getElementById('authDesc');

            if (action.value === 'login') {
                action.value = 'register';
                regFields.style.display = 'block';
                submitBtn.innerText = 'Establish Identity';
                toggleLink.innerText = 'Log In Instead';
                toggleText.innerText = 'Already verified?';
                authDesc.innerText = 'Identity Registration Protocol';
            } else {
                action.value = 'login';
                regFields.style.display = 'none';
                submitBtn.innerText = 'Launch Session';
                toggleLink.innerText = 'Request Access';
                toggleText.innerText = 'New to the infrastructure?';
                authDesc.innerText = 'Access Elite Infrastructure';
            }
        }
    </script>
</body>
</html>
