<?php
require_once '../config/connection.php';
requireLogin();
$page_title = 'File Manager';

// Only admin/librarian can write/delete
$canWrite = in_array(getSession('role'), ['admin','librarian']);
$fileDir  = __DIR__ . '/library_files/';
if (!is_dir($fileDir)) mkdir($fileDir, 0755, true);

$msg = '';
$msgType = 'info';
$viewContent = '';
$viewFile    = '';

// ── Create / Write File ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canWrite) {
    $action   = $_POST['action'] ?? '';
    $filename = basename(preg_replace('/[^a-zA-Z0-9_\-]/', '', trim($_POST['filename'] ?? ''))) . '.txt';
    $content  = $_POST['file_content'] ?? '';

    if ($action === 'create' || $action === 'write') {
        if (empty(trim($_POST['filename'] ?? ''))) {
            $msg = 'Please enter a valid filename.'; $msgType = 'danger';
        } else {
            $mode   = $action === 'create' ? 'w' : 'a';
            $handle = fopen($fileDir . $filename, $mode);
            if ($handle) {
                fwrite($handle, $content);
                fclose($handle);
                $msg = "File '{$filename}' " . ($action==='create'?'created/overwritten':'content appended') . " successfully!";
                $msgType = 'success';
            } else {
                $msg = 'Could not open file for writing.'; $msgType = 'danger';
            }
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

// ── List files ──
$files = glob($fileDir . '*.txt') ?: [];

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
    <!-- Create/Write Form -->
    <?php if ($canWrite): ?>
    <div class="card">
        <div class="card-title"><i class="fas fa-file-pen"></i> Create / Write to File</div>
        <form method="POST">
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label">Filename (no extension needed)</label>
                <div style="position:relative;">
                    <input type="text" name="filename" class="form-control" placeholder="e.g. notes, log_2024, report"
                           style="padding-right:40px;" pattern="[a-zA-Z0-9_\-]+">
                    <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:12px;">.txt</span>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label">Content</label>
                <textarea name="file_content" class="form-control" rows="5"
                          placeholder="Enter content to write..."></textarea>
            </div>
            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label">Write Mode</label>
                <select name="action" class="form-control">
                    <option value="create">Overwrite (mode: w) — Create new or overwrite existing</option>
                    <option value="write">Append (mode: a) — Add to end of existing file</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save File</button>
        </form>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-title"><i class="fas fa-lock"></i> Access Restricted</div>
        <p style="color:var(--muted);">File creation and deletion requires Librarian or Admin role.</p>
    </div>
    <?php endif; ?>

    <!-- File List -->
    <div class="card">
        <div class="card-title"><i class="fas fa-folder-open"></i> Files in Library
            <span class="badge badge-info" style="margin-left:auto;"><?= count($files) ?> file<?= count($files)!=1?'s':'' ?></span>
        </div>
        <?php if (empty($files)): ?>
        <div class="empty-state" style="padding:30px 0;">
            <i class="fas fa-folder" style="font-size:32px;"></i>
            <p>No files yet. Create one using the form.</p>
        </div>
        <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:8px;">
        <?php foreach ($files as $fp):
            $fn   = basename($fp);
            $size = filesize($fp);
            $mod  = filemtime($fp);
            $sizeStr = $size < 1024 ? $size . ' B' : round($size/1024,1) . ' KB';
        ?>
            <div style="display:flex;align-items:center;gap:12px;background:var(--bg);padding:10px 14px;border-radius:6px;border:1px solid var(--border);">
                <i class="fas fa-file-lines" style="color:var(--info);font-size:20px;flex-shrink:0;"></i>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($fn) ?></div>
                    <div style="font-size:11px;color:var(--muted);"><?= $sizeStr ?> &nbsp;·&nbsp; <?= date('d M Y H:i', $mod) ?></div>
                </div>
                <div style="display:flex;gap:6px;">
                    <a href="?view=<?= urlencode($fn) ?>" class="btn btn-sm btn-secondary" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="library_files/<?= urlencode($fn) ?>" download class="btn btn-sm btn-secondary" title="Download">
                        <i class="fas fa-download"></i>
                    </a>
                    <?php if ($canWrite): ?>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete <?= htmlspecialchars($fn) ?>?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="delfile" value="<?= htmlspecialchars($fn) ?>">
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

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
