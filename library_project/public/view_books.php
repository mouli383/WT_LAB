<?php
require_once '../config/connection.php';
requireLogin();

$page_title = 'Asset Catalog';
$role = getSession('role');

$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

$where  = []; $params = [];
if ($search !== '') {
    $where[]  = "(title LIKE ? OR author LIKE ? OR isbn LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%";
}
if ($category !== '') { $where[] = "category = ?"; $params[] = $category; }

$sql = "SELECT * FROM books";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY added_at DESC";

$stmt = $pdo->prepare($sql); $stmt->execute($params);
$books = $stmt->fetchAll();

$allCategories = $pdo->query("SELECT DISTINCT category FROM books WHERE category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

include 'nav.php';
?>

<div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:40px;">
    <div>
        <h1 style="font-size:48px; letter-spacing:-2px;">CENTRAL <span>CATALOG</span></h1>
        <p style="color:var(--text-muted); font-size:18px;"><?= count($books) ?> architectural assets registered in system</p>
    </div>
    <?php if (in_array($role,['admin','librarian'])): ?>
    <a href="add_book.php" class="btn btn-primary" style="height:54px;"><i class="fas fa-plus-circle"></i> Register New Asset</a>
    <?php endif; ?>
</div>

<form method="GET" class="card" style="background:#0a0a0a; border-radius:24px; padding:16px; margin-bottom:40px;">
    <div style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:16px;">
        <div class="form-group" style="margin-bottom:0;">
            <input type="text" name="search" class="form-control" placeholder="Search designation, author, or ISBN..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                <?php foreach ($allCategories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $category===$cat?'selected':'' ?>><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Synchronize Filters</button>
        <a href="view_books.php" class="btn btn-secondary" style="background:#111; padding:0 24px;"><i class="fas fa-rotate"></i></a>
    </div>
</form>

<div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:40px;">
    <?php foreach ($books as $b): ?>
    <div class="card" style="padding:0; overflow:hidden; border:none; background:transparent;">
        <div style="aspect-ratio: 2/3; background:var(--bg-surface-alt); position:relative; overflow:hidden; border-radius:4px; margin-bottom:16px;">
            <?php 
                $title = strtolower($b['title']);
                $poster = "book_" . (($b['id'] % 3) + 1) . ".png"; // Global Fallback

                if (strpos($title, 'code') !== false || strpos($title, 'program') !== false || strpos($title, 'python') !== false) {
                    $poster = "book_code.png";
                } elseif (strpos($title, 'algo') !== false || strpos($title, 'network') !== false || strpos($title, 'system') !== false) {
                    $poster = "book_algo.png";
                } elseif (strpos($title, 'habits') !== false || strpos($title, 'atomic') !== false) {
                    $poster = "book_habits.png";
                } elseif (strpos($title, 'finance') !== false || strpos($title, 'rich') !== false || strpos($title, 'dad') !== false) {
                    $poster = "book_finance.png";
                } elseif (strpos($title, 'history') !== false || strpos($title, 'sapiens') !== false) {
                    $poster = "book_history.png";
                } elseif (strpos($title, 'wings') !== false || strpos($title, 'fire') !== false) {
                    $poster = "book_biography.png";
                } elseif (strpos($title, 'gatsby') !== false || strpos($title, 'mockingbird') !== false) {
                    $poster = "book_classic.png";
                }
                
                $posterPath = "../assets/img/{$poster}";
            ?>
            <img src="<?= $posterPath ?>" alt="Book Cover" style="width:100%; height:100%; object-fit:cover; transition:var(--transition);" class="poster-img">
            <?php if($b['available'] > 0): ?>
            <div style="position:absolute; bottom:0; left:0; right:0; padding:20px; background:linear-gradient(transparent, rgba(0,0,0,0.8));">
                <div style="color:var(--primary); font-size:10px; font-weight:900; letter-spacing:1px; text-transform:uppercase;">Operational Available</div>
            </div>
            <?php endif; ?>
        </div>
        <div style="padding:0 8px;">
            <h3 style="font-size:24px; margin-bottom:4px; letter-spacing:1px;"><?= htmlspecialchars($b['title']) ?></h3>
            <div style="color:var(--text-muted); font-size:14px; margin-bottom:16px;"><?= htmlspecialchars($b['author']) ?></div>
            
            <div style="display:flex; gap:8px;">
                <?php if (in_array($role,['admin','librarian'])): ?>
                <a href="add_book.php?edit=<?= $b['id'] ?>" class="btn btn-secondary" style="flex:1; font-size:11px; height:40px;">EDIT</a>
                <a href="issue_book.php?book_id=<?= $b['id'] ?>" class="btn btn-primary" style="flex:1; font-size:11px; height:40px; <?= $b['available']<1?'opacity:0.2;pointer-events:none;':'' ?>">ISSUE</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

        </main>
    </div>
</div>
</body>
</html>
