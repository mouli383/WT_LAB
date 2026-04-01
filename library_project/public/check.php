<?php
require_once '../config/connection.php';
requireLogin();
$page_title = 'Asset Search';
$search = trim($_GET['search'] ?? '');
$book = null;
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR isbn = ? LIMIT 1");
    $stmt->execute(["%$search%", $search]);
    $book = $stmt->fetch();
}
include 'nav.php';
?>

<div class="card" style="max-width: 600px; margin: 0 auto 32px;">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-magnifying-glass"></i> Check Availability</h2>
    </div>
    <form method="GET">
        <div class="form-group" style="display:flex; gap:12px; margin-bottom:0;">
            <input type="text" name="search" class="form-control" placeholder="Enter book title or ISBN..." value="<?= htmlspecialchars($search) ?>" required autofocus>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
</div>

<?php if ($search): ?>
    <?php if ($book): ?>
    <div class="card" style="max-width: 600px; margin: 0 auto; animation: slideUp 0.4s ease;">
        <div style="display:flex; gap:24px; align-items:flex-start;">
            <div style="width:100px; height:140px; background:var(--bg-surface-alt); border-radius:10px; display:flex; align-items:center; justify-content:center; color:var(--primary); font-size:40px; flex-shrink:0; border:1px solid var(--border);">
                <i class="fas fa-book"></i>
            </div>
            <div style="flex:1;">
                <h3 style="font-size:24px; margin-bottom:4px;"><?= htmlspecialchars($book['title']) ?></h3>
                <div style="color:var(--text-muted); margin-bottom:12px;">by <?= htmlspecialchars($book['author']) ?></div>
                
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:20px;">
                    <div>
                        <div style="font-size:11px; color:var(--text-dim); text-transform:uppercase;">Status</div>
                        <div style="font-weight:700; color:<?= $book['available']>0?'var(--primary)':'var(--danger)' ?>;">
                            <?= $book['available']>0 ? 'Available ('.$book['available'].')' : 'Out of Stock' ?>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:11px; color:var(--text-dim); text-transform:uppercase;">Category</div>
                        <div style="font-weight:600;"><?= htmlspecialchars($book['category'] ?: 'General') ?></div>
                    </div>
                </div>

                <div style="display:flex; gap:10px;">
                    <?php if (getSession('role')!=='student' && $book['available']>0): ?>
                    <a href="issue_book.php?book_id=<?= $book['id'] ?>" class="btn btn-primary btn-sm">Issue Now</a>
                    <?php endif; ?>
                    <a href="view_books.php" class="btn btn-secondary btn-sm">Full Catalog</a>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="empty-state" style="max-width:600px; margin: 0 auto; padding: 60px;">
        <i class="fas fa-ban" style="font-size:40px; color:var(--danger); opacity:0.5;"></i>
        <h3 style="margin-top:20px;">Resource Not Found</h3>
        <p>No matching books were found in the current inventory.</p>
    </div>
    <?php endif; ?>
<?php endif; ?>

        </main>
    </div>
</div>
</body>
</html>
