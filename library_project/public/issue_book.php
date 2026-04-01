<?php
require_once '../config/connection.php';
requireLogin();
requireRole(['admin','librarian']);

$page_title = 'Issue Resource';
$preBook = isset($_GET['book_id']) ? (int)$_GET['book_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id   = (int)$_POST['book_id'];
    $member_id = (int)$_POST['member_id'];
    $issue_dt  = trim($_POST['issued_date'] ?? date('Y-m-d'));
    $due_dt    = trim($_POST['due_date'] ?? '');
    $issued_by = (int)getSession('user_id');

    if (!$due_dt) {
        $days = (int)($_POST['loan_days'] ?? 14);
        $due_dt = date('Y-m-d', strtotime("+$days days", strtotime($issue_dt)));
    }

    $errors = [];
    if (!$book_id)   $errors[] = 'Selection required: Book';
    if (!$member_id) $errors[] = 'Selection required: Member';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT available, title FROM books WHERE id=?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();
        if (!$book || $book['available'] < 1) $errors[] = 'Inventory Error: Item not available.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO issued_books (book_id, member_id, issued_by, issued_date, due_date, status) VALUES (?,?,?,?,?,'issued')");
        $stmt->execute([$book_id, $member_id, $issued_by, $issue_dt, $due_dt]);
        $pdo->prepare("UPDATE books SET available = available - 1 WHERE id=?")->execute([$book_id]);
        flashMessage('success', 'Asset "' . $book['title'] . '" assigned successfully.');
        header('Location: view_issued_books.php');
        exit;
    } else {
        foreach ($errors as $e) flashMessage('danger', $e);
    }
}

$books   = $pdo->query("SELECT id, title, author, available FROM books WHERE available > 0 ORDER BY title")->fetchAll();
$members = $pdo->query("SELECT id, name, username FROM users WHERE role='student' ORDER BY name")->fetchAll();

include 'nav.php';
?>

<div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:32px; align-items:start;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-hand-holding-heart"></i> Issue Details</h3>
        </div>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Resource Asset</label>
                <select name="book_id" class="form-control" required>
                    <option value="">-- Select a book --</option>
                    <?php foreach ($books as $b): ?>
                    <option value="<?= $b['id'] ?>" <?= $preBook === $b['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($b['title']) ?> (<?= $b['available'] ?> in stock)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Assign to Member</label>
                <select name="member_id" class="form-control" required>
                    <option value="">-- Select a member --</option>
                    <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['name']) ?> (@<?= htmlspecialchars($m['username']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:20px;">
                <div class="form-group">
                    <label class="form-label">Issue Date</label>
                    <input type="date" name="issued_date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Loan Period</label>
                    <select name="loan_days" class="form-control">
                        <option value="7">One Week</option>
                        <option value="14" selected>Two Weeks</option>
                        <option value="30">One Month</option>
                    </select>
                </div>
            </div>

            <div style="margin-top:32px;">
                <button type="submit" class="btn btn-primary" style="padding:14px 28px;"><i class="fas fa-check"></i> Assign Asset</button>
                <a href="view_books.php" class="btn btn-secondary">Discard</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-circle-info"></i> Assignment Policy</h3>
        </div>
        <div style="color:var(--text-dim); font-size:14px; line-height:2;">
            <p>Ensure the following standards are met:</p>
            <ul style="padding-left:18px; margin-top:10px;">
                <li>Verify member identity before handover.</li>
                <li>Check for existing overdue items.</li>
                <li>Default loan period is strictly enforced.</li>
                <li>System fine: ₹5.00/day after deadline.</li>
            </ul>
        </div>
    </div>
</div>

        </main>
    </div>
</div>
</body>
</html>
