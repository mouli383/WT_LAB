<?php
require_once '../config/connection.php';
requireLogin();
$page_title = 'File Manager';

// Allowed for all roles (student, librarian, admin) for demonstration
$canWrite = true;
$fileDir  = __DIR__ . '/library_files/';
if (!is_dir($fileDir)) mkdir($fileDir, 0755, true);

$msg = '';
$msgType = 'info';
$viewContent = '';
$viewFile    = '';

// ── Create / Write / Upload File ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canWrite) {
    $action   = $_POST['action'] ?? '';
    
    if ($action === 'create' || $action === 'write') {
        $filename = basename(preg_replace('/[^a-zA-Z0-9_\-]/', '', trim($_POST['filename'] ?? ''))) . '.txt';
        $content  = $_POST['file_content'] ?? '';
        if (empty(trim($_POST['filename'] ?? ''))) {
            $msg = 'Please enter a valid filename.'; $msgType = 'danger';
        } else {
            $mode   = $action === 'create' ? 'w' : 'a';
            $handle = fopen($fileDir . $filename, $mode);
            if ($handle) {
                fwrite($handle, $content);
                fclose($handle);
                $msg = "File '{$filename}' " . ($action==='create'?'created':'appended') . " successfully!";
                $msgType = 'success';
            }
        }
    } elseif ($action === 'upload') {
        if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
            $filename = basename($_FILES['upload_file']['name']);
            if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $fileDir . $filename)) {
                $msg = "File '{$filename}' uploaded successfully!";
                $msgType = 'success';
            } else {
                $msg = "Error moving uploaded file."; $msgType = 'danger';
            }
        } else {
            $msg = "No file selected or upload error."; $msgType = 'danger';
        }
    } elseif ($action === 'delete') {
        $del = basename(trim($_POST['delfile'] ?? ''));
        $path = $fileDir . $del;
        if (file_exists($path)) {
            unlink($path);
            $msg = "File '{$del}' deleted successfully."; $msgType = 'success';
        } else {
            $msg = 'File not found.'; $msgType = 'danger';
        }
    }
}

// ── View File ──
if (isset($_GET['view'])) {
    $vf = basename($_GET['view']);
    $vpath = $fileDir . $vf;
    if (file_exists($vpath)) {
        $viewContent = file_get_contents($vpath);
        $viewFile = $vf;
    }
}

// ── List all files ──
$files = glob($fileDir . '*') ?: [];

include 'nav.php';
?>

<div class="page-header">
    <div>
        <h2>File Manager</h2>
        <p>Create, read, write and delete .txt files — PHP file handling demo</p>
    </div>
</div>

<?php if ($msg): ?>
<div class="flash flash-<?= $msgType ?>" style="margin-bottom:20px;">
    <i class="fas <?= $msgType==='success'?'fa-circle-check':'fa-circle-xmark' ?>"></i> <?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">
    <!-- ── Section 1: Quick Upload ── -->
    <?php if ($canWrite): ?>
    <div class="card">
        <div class="card-title"><i class="fas fa-upload"></i> Quick Upload</div>
        <p style="font-size:12px; color:var(--text-dim); margin-bottom:20px;">Upload any document, image, or media file directly to the library repository.</p>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload">
            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label">Select File</label>
                <input type="file" name="upload_file" class="form-control" required style="padding:10px;">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; height:50px;">
                <i class="fas fa-cloud-arrow-up"></i> Push to Server
            </button>
        </form>
    </div>

    <!-- ── Section 2: Manual Create ── -->
    <div class="card">
        <div class="card-title"><i class="fas fa-file-signature"></i> Manual Create</div>
        <p style="font-size:12px; color:var(--text-dim); margin-bottom:20px;">Generate a new plain text document (.txt) by entering content manually.</p>
        
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label">Filename</label>
                <input type="text" name="filename" class="form-control" placeholder="e.g. manifest" pattern="[a-zA-Z0-9_\-]+" required>
            </div>
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label">Document Content</label>
                <textarea name="file_content" class="form-control" rows="3" placeholder="Enter text content here..."></textarea>
            </div>
            <button type="submit" class="btn btn-secondary" style="width:100%; height:50px; border:1px dashed var(--primary);">
                <i class="fas fa-plus-circle"></i> Initialize Text File
            </button>
        </form>
    </div>
    <?php else: ?>
    <div class="card" style="grid-column: span 2;">
        <div class="card-title"><i class="fas fa-lock"></i> Access Restricted</div>
        <p style="color:var(--muted);">File creation and upload operations require Librarian or Admin elevated privileges.</p>
    </div>
    <?php endif; ?>
</div>

<!-- ── Section 3: Files in Library (Full Width) ── -->
<div class="card" style="margin-top:20px;">
    <div class="card-title"><i class="fas fa-folder-tree"></i> Files in Library repository
        <span class="badge badge-info" style="margin-left:auto; background:var(--primary-dim); color:var(--primary); padding:4px 12px; border-radius:100px; font-size:10px; font-weight:900;">
            <?= count($files) ?> ITEMS DETECTED
        </span>
    </div>
    
    <?php if (empty($files)): ?>
    <div class="empty-state" style="padding:60px 0; text-align:center; opacity:0.5;">
        <i class="fas fa-box-open" style="font-size:48px; margin-bottom:16px;"></i>
        <p style="letter-spacing:1px; font-weight:700;">REPOSITORY IS CURRENTLY EMPTY</p>
    </div>
    <?php else: ?>
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:16px;">
        <?php foreach ($files as $fp):
            if (is_dir($fp)) continue;
            $fn   = basename($fp);
            $size = filesize($fp);
            $mod  = filemtime($fp);
            $ext  = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
            
            $icon = 'fa-file';
            $color = 'var(--text-muted)';
            if ($ext === 'pdf') { $icon = 'fa-file-pdf'; $color = '#ef4444'; }
            elseif (in_array($ext, ['jpg','jpeg','png','gif'])) { $icon = 'fa-file-image'; $color = '#3b82f6'; }
            elseif ($ext === 'txt') { $icon = 'fa-file-lines'; $color = 'var(--primary)'; }
            elseif ($ext === 'zip' || $ext === 'rar') { $icon = 'fa-file-zipper'; $color = '#a855f7'; }
            
            $sizeStr = $size < 1024 ? $size . ' B' : ($size < 1048576 ? round($size/1024,1) . ' KB' : round($size/1048576,1) . ' MB');
        ?>
        <div style="display:flex; align-items:center; gap:16px; background:var(--bg-surface-alt); padding:16px; border-radius:12px; border:1px solid var(--border); transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="width:48px; height:48px; background:rgba(255,255,255,0.03); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas <?= $icon ?>" style="color:<?= $color ?>; font-size:24px;"></i>
            </div>
            <div style="flex:1; min-width:0;">
                <div style="font-weight:700; font-size:14px; color:var(--text-main); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars($fn) ?></div>
                <div style="font-size:11px; color:var(--text-dim); margin-top:4px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;"><?= $sizeStr ?> &bull; <?= date('d M H:i', $mod) ?></div>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="?view=<?= urlencode($fn) ?>" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                <a href="library_files/<?= urlencode($fn) ?>" download class="btn-icon" title="Download"><i class="fas fa-download"></i></a>
                <?php if ($canWrite): ?>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Erase <?= htmlspecialchars($fn) ?> permanently?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="delfile" value="<?= htmlspecialchars($fn) ?>">
                    <button type="submit" class="btn-icon" style="color:#ef4444; background:none; border:none; cursor:pointer;"><i class="fas fa-trash-can"></i></button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.btn-icon {
    width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
    border-radius: 8px; background: rgba(255,255,255,0.05); color: var(--text-muted);
    font-size: 14px; transition: var(--transition);
}
.btn-icon:hover { background: var(--primary-dim); color: var(--primary); transform: translateY(-2px); }
</style>

<!-- View File Content -->
<?php if ($viewFile): ?>
<div class="card" style="margin-top:20px;">
    <div class="card-title">
        <i class="fas fa-file-lines"></i> Viewing: <?= htmlspecialchars($viewFile) ?>
        <a href="file_manager.php" class="btn btn-sm btn-secondary" style="margin-left:auto;"><i class="fas fa-times"></i> Close</a>
    </div>
    <div style="background:var(--bg);border:1px solid var(--border);border-radius:6px;padding:16px;">
        <pre style="font-family:'Courier New',monospace;font-size:13px;color:var(--text);white-space:pre-wrap;margin:0;line-height:1.7;"><?= htmlspecialchars($viewContent ?: '(Empty file)') ?></pre>
    </div>
    <div style="margin-top:12px;font-size:11px;color:var(--muted);">
        Size: <?= strlen($viewContent) ?> bytes &nbsp;·&nbsp;
        Words: <?= str_word_count($viewContent) ?> &nbsp;·&nbsp;
        Lines: <?= substr_count($viewContent,"\n")+1 ?>
    </div>
</div>
<?php endif; ?>

<!-- PHP Functions Reference -->
<div class="card" style="margin-top:20px;">
    <div class="card-title"><i class="fas fa-code"></i> PHP File Functions Used</div>
    <div class="table-wrap" style="border:none;">
        <table class="table">
            <thead><tr><th>Function</th><th>Description</th><th>Used In</th></tr></thead>
            <tbody>
                <tr><td><code style="color:var(--accent);">fopen($path, $mode)</code></td><td>Opens a file with specified mode</td><td>Create / Append</td></tr>
                <tr><td><code style="color:var(--accent);">fwrite($handle, $str)</code></td><td>Writes string to file</td><td>Create / Append</td></tr>
                <tr><td><code style="color:var(--accent);">fclose($handle)</code></td><td>Closes open file handle</td><td>After every write</td></tr>
                <tr><td><code style="color:var(--accent);">file_get_contents($path)</code></td><td>Reads entire file into string</td><td>View file</td></tr>
                <tr><td><code style="color:var(--accent);">file_exists($path)</code></td><td>Checks if file or directory exists</td><td>Delete / View</td></tr>
                <tr><td><code style="color:var(--accent);">unlink($path)</code></td><td>Deletes a file</td><td>Delete</td></tr>
                <tr><td><code style="color:var(--accent);">glob($pattern)</code></td><td>Finds pathnames matching a pattern</td><td>List files</td></tr>
                <tr><td><code style="color:var(--accent);">filesize($path)</code></td><td>Gets file size in bytes</td><td>File listing</td></tr>
                <tr><td><code style="color:var(--accent);">filemtime($path)</code></td><td>Gets file modification time</td><td>File listing</td></tr>
                <tr><td><code style="color:var(--accent);">basename($path)</code></td><td>Returns filename from path</td><td>Security / Display</td></tr>
                <tr><td><code style="color:var(--accent);">is_dir($path)</code></td><td>Checks if path is a directory</td><td>Folder init</td></tr>
                <tr><td><code style="color:var(--accent);">mkdir($path, $mode)</code></td><td>Creates a new directory</td><td>Folder init</td></tr>
            </tbody>
        </table>
    </div>
</div>

        </main>
        <footer class="page-footer">&copy; <?= date('Y') ?> LibManage</footer>
    </div>
</div>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');}</script>
</body>
</html>
