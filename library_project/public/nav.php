<?php 
$current = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? 'user';

$iconMap = [
    'dashboard.php' => 'fa-home',
    'view_books.php' => 'fa-book',
    'check.php' => 'fa-search',
    'issue_book.php' => 'fa-hand-holding',
    'return_book.php' => 'fa-undo',
    'view_issued_books.php' => 'fa-list-ul',
    'add_book.php' => 'fa-plus-circle',
    'file_manager.php' => 'fa-file-invoice',
    'file_functions_demo.php' => 'fa-flask',
    'file_modes_demo.php' => 'fa-gears',
    'about.php' => 'fa-info-circle',
    'contact.php' => 'fa-envelope',
    'login.php' => 'fa-lock'
];
$currentIcon = $iconMap[$current] ?? 'fa-spinner';
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibManage | <?= $page_title ?? 'Elite Infrastructure' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body class="layout">
    <div id="scroll-progress"></div>
    <div id="preloader">
        <i id="loader-icon" class="fas <?= $currentIcon ?> fa-spin-pulse"></i>
    </div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">LIB<span>MANAGE</span></div>
        </div>

        <nav class="sidebar-nav">
            <div class="label" style="padding: 24px 20px 8px;">MAIN HUB</div>
            <a href="dashboard.php" class="sidebar-link <?= $current==='dashboard.php'?'active':'' ?>" data-icon="fa-home">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
            <a href="view_books.php" class="sidebar-link <?= $current==='view_books.php'?'active':'' ?>" data-icon="fa-book">
                <i class="fas fa-book"></i><span>Browse Books</span>
            </a>
            <a href="check.php" class="sidebar-link <?= $current==='check.php'?'active':'' ?>" data-icon="fa-search">
                <i class="fas fa-search"></i><span>Check Availability</span>
            </a>

            <div class="label" style="padding: 24px 20px 8px;">CIRCULATION</div>
            <a href="issue_book.php" class="sidebar-link <?= $current==='issue_book.php'?'active':'' ?>" data-icon="fa-hand-holding">
                <i class="fas fa-hand-holding"></i><span>Issue Book</span>
            </a>
            <a href="return_book.php" class="sidebar-link <?= $current==='return_book.php'?'active':'' ?>" data-icon="fa-undo">
                <i class="fas fa-undo"></i><span>Return Book</span>
            </a>
            <a href="view_issued_books.php" class="sidebar-link <?= $current==='view_issued_books.php'?'active':'' ?>" data-icon="fa-list-ul">
                <i class="fas fa-list-ul"></i><span>Issued Books</span>
            </a>

            <div class="label" style="padding: 24px 20px 8px;">CATALOG</div>
            <a href="add_book.php" class="sidebar-link <?= $current==='add_book.php'?'active':'' ?>" data-icon="fa-plus-circle">
                <i class="fas fa-plus-circle"></i><span>Add Book</span>
            </a>

            <div class="label" style="padding: 24px 20px 8px;">ADMIN TOOLS</div>
            <a href="file_manager.php" class="sidebar-link <?= $current==='file_manager.php'?'active':'' ?>" data-icon="fa-file-invoice">
                <i class="fas fa-file-invoice"></i><span>File Manager</span>
            </a>
            <a href="file_functions_demo.php" class="sidebar-link <?= $current==='file_functions_demo.php'?'active':'' ?>" data-icon="fa-flask">
                <i class="fas fa-flask"></i><span>File Functions</span>
            </a>
            <a href="file_modes_demo.php" class="sidebar-link <?= $current==='file_modes_demo.php'?'active':'' ?>" data-icon="fa-gears">
                <i class="fas fa-gears"></i><span>File Modes</span>
            </a>

            <div class="label" style="padding: 24px 20px 8px;">SUPPORT</div>
            <a href="about.php" class="sidebar-link <?= $current==='about.php'?'active':'' ?>" data-icon="fa-info-circle">
                <i class="fas fa-info-circle"></i><span>About Application</span>
            </a>
            <a href="contact.php" class="sidebar-link <?= $current==='contact.php'?'active':'' ?>" data-icon="fa-envelope">
                <i class="fas fa-envelope"></i><span>Contact Support</span>
            </a>
        </nav>

        <div style="padding: 24px; border-top: 1px solid var(--border);">
            <a href="logout.php" class="sidebar-link" data-icon="fa-sign-out-alt">
                <i class="fas fa-sign-out-alt"></i><span>Sign Out</span>
            </a>
        </div>
    </aside>

    <div class="main-wrap">
        <header class="top-bar">
            <h4 style="font-weight:700;"><?= ucfirst(str_replace(['.php', '_'], ['', ' '], $current)) ?></h4>
            <div style="display:flex; align-items:center; gap:24px;">
                <button onclick="toggleTheme()" style="background:none; border:none; cursor:pointer; font-size:18px; color:var(--text-main);">
                    <i class="theme-icon fas fa-moon"></i>
                </button>
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="text-align:right;">
                        <div style="font-size:12px; font-weight:900;"><?= $_SESSION['username'] ?? 'User' ?></div>
                        <div style="font-size:10px; color:var(--text-dim); text-transform:uppercase; letter-spacing:1px;"><?= $role ?></div>
                    </div>
                    <div style="width:40px; height:40px; background:var(--primary-dim); border-radius:12px; display:flex; align-items:center; justify-content:center; color:var(--primary); font-weight:900;">
                        <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Global Scripts for v10.0 -->
        <script src="../assets/js/main.js"></script>
        
        <main style="padding: 40px; flex: 1;">
            <?php if ($msg = getFlashMessage()): ?>
                <div class="card" style="border-left:4px solid var(--primary); padding:16px 24px; margin-bottom:24px; background:rgba(250,204,21,0.05); color:var(--primary); font-weight:600;">
                    <i class="fas fa-circle-info" style="margin-right:12px;"></i> <?= $msg['message'] ?>
                </div>
            <?php endif; ?>
