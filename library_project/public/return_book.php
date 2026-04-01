<?php
require_once '../config/connection.php';
requireLogin();
requireRole(['admin','librarian']);

$page_title = 'Process Return';
$issue_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = (int)$_POST['issue_id'];
    $stmt = $pdo->prepare("SELECT * FROM issued_books WHERE id = ? AND status='issued'");
    $stmt->execute([$issue_id]);
    $rec = $stmt->fetch();

    if ($rec) {
        $today = new DateTime();
        $due   = new DateTime($rec['due_date']);
        $fine  = 0;
        if ($today > $due) {
            $diff = $today->diff($due)->days;
            $fine = $diff * 5;
        }

        $pdo->prepare("UPDATE issued_books SET status='returned', return_date=CURDATE(), fine=? WHERE id=?")->execute([$fine, $issue_id]);
        $pdo->prepare("UPDATE books SET available = available + 1 WHERE id=?")->execute([$rec['book_id']]);
        
        flashMessage('success', 'Return completed successfully.' . ($fine > 0 ? " Fine of ₹$fine applied." : ""));
        header('Location: view_issued_books.php');
        exit;
    } else {
        flashMessage('danger', 'Invalid or already returned record.');
    }
}

// Recent active issues
$activeIssues = $pdo->query(
    "SELECT ib.id, b.title, u.name as member, ib.due_date, ib.issued_date
     FROM issued_books ib
     JOIN books b ON b.id = ib.book_id
     JOIN users u ON u.id = ib.member_id
     WHERE ib.status = 'issued'
     ORDER BY ib.id DESC"
)->fetchAll();

include 'nav.php';
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-undo-alt"></i> Finalize Return</h3>
    </div>

    <?php if ($activeIssues): ?>
    <form method="POST">
        <div class="form-group">
            <label class="form-label">Select Issued Record</label>
            <select name="issue_id" class="form-control" required>
                <option value="">-- Search by member or book --</option>
                <?php foreach ($activeIssues as $row): ?>
                <option value="<?= $row['id'] ?>" <?= $issue_id === $row['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['title']) ?> (To: <?= htmlspecialchars($row['member']) ?> · Due: <?= date('d M', strtotime($row['due_date'])) ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-top:24px; padding:20px; background:var(--bg-surface-alt); border-radius:10px; border:1px solid var(--border);">
            <div style="font-size:12px; color:var(--text-dim); margin-bottom:12px;">Automated Calculations</div>
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div style="font-size:14px; color:var(--text-muted);">Status Check</div>
                <div style="font-weight:700; color:var(--primary);"><i class="fas fa-shield-check"></i> System Verified</div>
            </div>
        </div>

        <div style="margin-top:32px;">
            <button type="submit" class="btn btn-primary" style="padding:14px 30px;"><i class="fas fa-check-circle"></i> Complete Return</button>
            <a href="view_issued_books.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
    <?php else: ?>
    <div class="empty-state" style="padding: 40px;">
        <i class="fas fa-check-double" style="font-size:40px; color:var(--primary); opacity:0.5;"></i>
        <h3 style="margin-top:20px;">All items returned</h3>
        <p>No active loan records found in the system.</p>
    </div>
    <?php endif; ?>
</div>

        </main>
    </div>
</div>
</body>
</html>
