<?php
require_once '../config/connection.php';
$page_title = 'Communications';
include 'nav.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = 'Communication uplink established. Our team will contact you shortly.';
}
?>

<div class="card" style="max-width: 1000px; margin: 0 auto; background: var(--bg-surface-alt); border-radius:32px; padding:48px;">
    <div style="margin-bottom:48px; text-align:center;">
        <h2 style="font-size:48px; letter-spacing:-2px; margin-bottom:12px;">GLOBAL <span>SUPPORT</span></h2>
        <p style="color:var(--text-muted); font-size:18px;">Direct access to the LibManage administrative team.</p>
    </div>

    <?php if ($success): ?>
        <div class="flash" style="background:#0a0a0a; border:1px solid var(--primary); color:var(--primary); padding:20px; border-radius:16px; margin-bottom:40px; font-weight:700; text-align:center;">
            <i class="fas fa-satellite-dish"></i> <?= $success ?>
        </div>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns: 1.2fr 1fr; gap:64px;">
        <form method="POST">
            <div class="form-group" style="margin-bottom:24px;">
                <label style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--text-dim); font-weight:900; margin-bottom:10px; display:block;">Operator Designation</label>
                <input type="text" name="name" class="form-control" value="<?= getSession('name') ?>" required>
            </div>
            <div class="form-group" style="margin-bottom:24px;">
                <label style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--text-dim); font-weight:900; margin-bottom:10px; display:block;">Secure Reply Email</label>
                <input type="email" name="email" class="form-control" value="<?= getSession('email') ?>" required>
            </div>
            <div class="form-group" style="margin-bottom:24px;">
                <label style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--text-dim); font-weight:900; margin-bottom:10px; display:block;">Detailed Request</label>
                <textarea name="message" class="form-control" rows="6" placeholder="Construct your inquiry..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="padding:16px 40px; font-size:14px;"><i class="fas fa-paper-plane"></i> Initialize Transmission</button>
        </form>

        <div style="padding-left:48px; border-left:1px solid var(--border);">
            <h4 style="font-size:18px; margin-bottom:32px; letter-spacing:1px;">COORDINATES</h4>
            <div style="display:flex; flex-direction:column; gap:32px;">
                <div style="display:flex; gap:20px; align-items:flex-start;">
                    <div style="width:44px; height:44px; background:#1e1e1e; border:1px solid var(--border); color:var(--primary); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-location-dot"></i>
                    </div>
                    <div>
                        <div style="font-weight:900; font-size:15px; margin-bottom:4px;">Main Hub</div>
                        <div style="font-size:13px; color:var(--text-muted); line-height:1.6;">Level 4, Infrastructure Block<br>Central Scholars Park, CP-500</div>
                    </div>
                </div>
                <div style="display:flex; gap:20px; align-items:flex-start;">
                    <div style="width:44px; height:44px; background:#1e1e1e; border:1px solid var(--border); color:var(--primary); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div>
                        <div style="font-weight:900; font-size:15px; margin-bottom:4px;">Voice Line</div>
                        <div style="font-size:13px; color:var(--text-muted);">+1 (LL) 500-INFRA-9</div>
                    </div>
                </div>
                <div style="display:flex; gap:20px; align-items:flex-start;">
                    <div style="width:44px; height:44px; background:#1e1e1e; border:1px solid var(--border); color:var(--primary); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div style="font-weight:900; font-size:15px; margin-bottom:4px;">Operational Hours</div>
                        <div style="font-size:13px; color:var(--text-muted);">0800 - 2000 HRS Global Time</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        </main>
    </div>
</div>
</body>
</html>
