<?php
require_once '../config/connection.php';
requireLogin();

$page_title = 'Transaction Logs';
$role = getSession('role');
$uid  = getSession('user_id');

// ── Filters ──
$filter = trim($_GET['filter'] ?? 'all');
$search = trim($_GET['search'] ?? '');

$where  = [];
$params = [];

if ($role === 'student') {
    $where[]  = "ib.member_id = ?";
    $params[] = $uid;
}

if ($filter === 'issued')       $where[] = "ib.status = 'issued'";
elseif ($filter === 'returned')  $where[] = "ib.status = 'returned'";
elseif ($filter === 'overdue')   $where[] = "ib.status = 'overdue'";

if ($search !== '') {
    $where[] = "(b.title LIKE ? OR u.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

$stmt = $pdo->prepare(
    "SELECT ib.id, b.title, u.name AS member, ib.issued_date, ib.due_date, ib.return_date, ib.fine, ib.status
     FROM issued_books ib
     JOIN books b ON b.id = ib.book_id
     JOIN users u ON u.id = ib.member_id
     $whereSql
     ORDER BY ib.id DESC"
);
$stmt->execute($params);
$records = $stmt->fetchAll();

include 'nav.php';
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-list-check"></i> <?= $role === 'student' ? 'My Borrowed Items' : 'Global Transactions' ?></h2>
        <?php if (in_array($role,['admin','librarian'])): ?>
        <a href="issue_book.php" class="btn btn-primary btn-sm">Issue Book</a>
        <?php endif; ?>
    </div>

    <!-- Stats summary strip -->
    <div style="display:flex; gap:20px; border-bottom:1px solid var(--border); padding-bottom:24px; margin-bottom:24px; overflow-x:auto;">
        <div style="flex:1; min-width:120px;">
            <div style="font-size:11px; color:var(--text-dim); text-transform:uppercase;">Volume</div>
            <div style="font-size:20px; font-weight:800;"><?= count($records) ?> Records</div>
        </div>
        <div style="flex:1; min-width:120px;">
            <div style="font-size:11px; color:var(--text-dim); text-transform:uppercase;">Status Filter</div>
            <div style="display:flex; gap:8px; margin-top:4px;">
                <a href="?filter=all" class="badge <?= $filter==='all'?'badge-primary':'badge-secondary' ?>" style="font-size:10px;">All</a>
                <a href="?filter=issued" class="badge <?= $filter==='issued'?'badge-primary':'badge-secondary' ?>" style="font-size:10px;">Active</a>
                <a href="?filter=overdue" class="badge <?= $filter==='overdue'?'badge-primary':'badge-secondary' ?>" style="font-size:10px;">Overdue</a>
            </div>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Asset</th>
                    <?php if ($role !== 'student'): ?><th>Member</th><?php endif; ?>
                    <th>Issued On</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <?php if (in_array($role,['admin','librarian'])): ?><th>Action</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $row): 
                    $isOverdue = $row['status'] === 'issued' && new DateTime() > new DateTime($row['due_date']);
                ?>
                <tr>
                    <td>
                        <div style="font-weight:700; color:var(--text-main);"><?= htmlspecialchars($row['title']) ?></div>
                    </td>
                    <?php if ($role !== 'student'): ?>
                    <td><?= htmlspecialchars($row['member']) ?></td>
                    <?php endif; ?>
                    <td><span style="font-size:13px; color:var(--text-muted);"><?= date('M d, Y', strtotime($row['issued_date'])) ?></span></td>
                    <td>
                        <span style="font-size:13px; font-weight:600; color:<?= $isOverdue?'var(--danger)':'var(--text-muted)' ?>;">
                            <?= date('M d, Y', strtotime($row['due_date'])) ?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        $statusClass = $isOverdue ? 'danger' : ($row['status']==='returned' ? 'success' : 'info');
                        $statusText = $isOverdue ? 'Overdue' : ucfirst($row['status']);
                        ?>
                        <span class="badge" style="background:var(--primary-dim); color:var(--primary); font-size:11px; padding:4px 10px;"><?= $statusText ?></span>
                    </td>
                    <?php if (in_array($role,['admin','librarian'])): ?>
                    <td>
                        <?php if ($row['status'] !== 'returned'): ?>
                        <a href="return_book.php?id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm" style="font-size:11px;"><i class="fas fa-undo"></i> Return</a>
                        <?php else: ?>
                        <span style="color:var(--text-dim); font-size:11px;">Closed</span>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

        </main>
    </div>
</div>
</body>
</html>
