<?php
require_once '../config/connection.php';
requireLogin();

$page_title = 'Control Center';
$role = getSession('role');

// ── Performance Metrics ──
$totalBooks    = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$totalAvailable= $pdo->query("SELECT SUM(available) FROM books")->fetchColumn() ?? 0;
$totalIssued   = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status='issued'")->fetchColumn();
$totalOverdue  = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status='overdue'")->fetchColumn();

// ── Recent Activity Feed ──
$recentQuery = "SELECT ib.id, b.title, u.name AS member, ib.issued_date, ib.due_date, ib.status
                FROM issued_books ib
                JOIN books b ON b.id = ib.book_id
                JOIN users u ON u.id = ib.member_id ";
if ($role === 'student') $recentQuery .= "WHERE ib.member_id = " . getSession('user_id');
$recentQuery .= " ORDER BY ib.id DESC LIMIT 5";
$recentIssued = $pdo->query($recentQuery)->fetchAll();

include 'nav.php';
?>

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:32px; margin-bottom:60px;">
    <div class="card" style="padding:40px; border-left:4px solid var(--primary); background:rgba(251, 191, 36, 0.02);">
        <h4 class="label" style="margin-bottom:12px; color:var(--text-dim);">Digital Assets</h4>
        <div style="font-size:64px; font-family:var(--font-head); color:var(--primary); line-height:1;"><?= $totalBooks ?></div>
        <div style="font-size:12px; color:var(--text-muted); margin-top:8px;">Total items in cloud catalog</div>
    </div>
    <div class="card" style="padding:40px;">
        <h4 class="label" style="margin-bottom:12px; color:var(--text-dim);">Live Availability</h4>
        <div style="font-size:64px; font-family:var(--font-head); line-height:1;"><?= $totalAvailable ?></div>
        <div style="font-size:12px; color:var(--text-muted); margin-top:8px;">Operational readiness in-orbit</div>
    </div>
    <div class="card" style="padding:40px;">
        <h4 class="label" style="margin-bottom:12px; color:var(--text-dim);">Active Flow</h4>
        <div style="font-size:64px; font-family:var(--font-head); line-height:1;"><?= $totalIssued ?></div>
        <div style="font-size:12px; color:var(--text-muted); margin-top:8px;">Current external deployments</div>
    </div>
    <div class="card" style="padding:40px; border-color:rgba(239, 68, 68, 0.3);">
        <h4 class="label" style="margin-bottom:12px; color:#ef4444;">Overdue Protocol</h4>
        <div style="font-size:64px; font-family:var(--font-head); color:#ef4444; line-height:1;"><?= $totalOverdue ?></div>
        <div style="font-size:12px; color:var(--text-muted); margin-top:8px;">Critical security violations</div>
    </div>
</div>

<div class="card" style="padding: 0; overflow:hidden; border-radius:0;">
    <div style="padding: 32px 40px; border-bottom: 1px solid var(--border); background:rgba(255,255,255,0.02);">
        <h2 style="font-size:32px; letter-spacing:1px;">LATEST <span>TRANSACTIONS</span></h2>
        <p style="font-size:14px; color:var(--text-muted); font-family:var(--font-body);">Global telemetry of archival movement</p>
    </div>
    
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>DESIGNATION</th>
                    <?php if ($role !== 'student'): ?><th>MEMBER</th><?php endif; ?>
                    <th>TIMESTAMP</th>
                    <th>DEADLINE</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentIssued as $row): 
                    $isOverdue = (new DateTime() > new DateTime($row['due_date']));
                ?>
                <tr>
                    <td><div style="font-family:var(--font-head); font-size:20px; letter-spacing:1px;"><?= htmlspecialchars($row['title']) ?></div></td>
                    <?php if ($role !== 'student'): ?><td><div style="font-weight:600; opacity:0.8;"><?= htmlspecialchars($row['member']) ?></div></td><?php endif; ?>
                    <td><div style="font-size:13px; color:var(--text-muted);"><?= date('M d, Y', strtotime($row['issued_date'])) ?></div></td>
                    <td><div style="font-size:13px; font-weight:700; color:<?= $isOverdue?'#ef4444':'var(--text-muted)' ?>;"><?= date('M d, Y', strtotime($row['due_date'])) ?></div></td>
                    <td>
                        <div style="border:1px solid var(--primary); color:var(--primary); font-size:10px; font-weight:900; padding:4px 12px; text-transform:uppercase; letter-spacing:1px; display:inline-block;">
                            <?= $row['status'] ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

        </main>
        <footer style="padding: 40px; text-align:center; color:var(--text-dim); font-size:12px; font-family:var(--font-head); font-weight:700; letter-spacing:2px; text-transform:uppercase;">
            &copy; <?= date('Y') ?> LibManage Advanced Infrastructure.
        </footer>
    </div>
</div>
</body>
</html>
