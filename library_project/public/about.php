<?php
require_once '../config/connection.php';
$page_title = 'System Infrastructure';
include 'nav.php';
?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-microchip"></i> Infrastructure Overview</h2>
    </div>

    <div style="display:grid; grid-template-columns: 2fr 1.5fr; gap:40px; align-items:start;">
        <div>
            <h3 style="margin-bottom:16px;">The Evolution: LibManage v3.0</h3>
            <p style="color:var(--text-muted); margin-bottom:24px;">
                LibManage is a next-generation library infrastructure designed for high-performance resource tracking and member management. 
                Built on a robust PHP/PDO architecture, it provides a seamless experience for both administrators and students.
            </p>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:32px;">
                <div style="padding:16px; background:var(--bg-surface-alt); border-radius:12px; border:1px solid var(--border);">
                    <div style="font-size:11px; color:var(--primary); font-weight:700; text-transform:uppercase;">Architecture</div>
                    <div style="font-weight:600; margin-top:4px;">PHP 8.x + MariaDB</div>
                </div>
                <div style="padding:16px; background:var(--bg-surface-alt); border-radius:12px; border:1px solid var(--border);">
                    <div style="font-size:11px; color:var(--primary); font-weight:700; text-transform:uppercase;">Interface</div>
                    <div style="font-weight:600; margin-top:4px;">Emerald & Slate CSS</div>
                </div>
            </div>

            <h4 style="margin-bottom:12px;">Core Capabilities</h4>
            <div style="display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; align-items:center; gap:12px; font-size:14px; color:var(--text-muted);">
                    <i class="fas fa-check-circle" style="color:var(--primary)"></i> Real-time inventory synchronization.
                </div>
                <div style="display:flex; align-items:center; gap:12px; font-size:14px; color:var(--text-muted);">
                    <i class="fas fa-check-circle" style="color:var(--primary)"></i> Advanced role-based access control (RBAC).
                </div>
                <div style="display:flex; align-items:center; gap:12px; font-size:14px; color:var(--text-muted);">
                    <i class="fas fa-check-circle" style="color:var(--primary)"></i> Automated fine calculation and overdue tracking.
                </div>
            </div>
        </div>

        <div style="background:var(--bg-surface-alt); padding:32px; border-radius:20px; border:1px solid var(--border); text-align:center;">
            <div style="width:80px; height:80px; background:var(--primary-dim); color:var(--primary); border-radius:20px; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 20px;">
                <i class="fas fa-book-bookmark"></i>
            </div>
            <h3 style="margin-bottom:8px;">LibManage v3.0.4</h3>
            <div style="font-size:13px; color:var(--text-dim); margin-bottom:24px;">Enterprise Edition · Stable</div>
            
            <div style="display:flex; flex-direction:column; gap:8px;">
                <div class="badge badge-secondary" style="width:100%; justify-content:space-between; padding:12px 16px;">
                    <span>Database Status</span> <span style="color:var(--primary)"><i class="fas fa-circle" style="font-size:8px;"></i> Online</span>
                </div>
                <div class="badge badge-secondary" style="width:100%; justify-content:space-between; padding:12px 16px;">
                    <span>Theme Support</span> <span style="color:var(--primary)">Dual Mode</span>
                </div>
            </div>

            <p style="margin-top:32px; font-size:12px; color:var(--text-dim);">
                &copy; 2024 LibManage Infrastructure.<br>All rights reserved.
            </p>
        </div>
    </div>
</div>

        </main>
    </div>
</div>
</body>
</html>
