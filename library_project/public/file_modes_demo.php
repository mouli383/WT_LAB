<?php
require_once '../config/connection.php';
requireLogin();
$page_title = 'File Modes Demo';

$fileDir = __DIR__ . '/library_files/';
if (!is_dir($fileDir)) mkdir($fileDir, 0755, true);

$results = [];
$errors  = [];

// ── Run demos only if requested ──
if (isset($_GET['run']) && $_GET['run'] === '1') {

    // ── Mode: w (write/create) ──
    $f1 = $fileDir . 'mode_w_demo.txt';
    $h  = fopen($f1, 'w');
    if ($h) {
        fwrite($h, "Mode 'w' — Created/Overwritten on " . date('d M Y H:i:s') . "\nThis file was created fresh.\n");
        fclose($h);
        $results['w'] = ['status'=>'success', 'out'=>file_get_contents($f1)];
    } else { $errors['w'] = 'Could not open with mode w'; }

    // ── Mode: a (append) ──
    $f2 = $fileDir . 'mode_a_demo.txt';
    $h  = fopen($f2, 'a');
    if ($h) {
        fwrite($h, "Appended at " . date('H:i:s') . " — Line " . (file_exists($f2) ? (substr_count(file_get_contents($f2),"\n")+1) : 1) . "\n");
        fclose($h);
        $results['a'] = ['status'=>'success', 'out'=>file_get_contents($f2)];
    } else { $errors['a'] = 'Could not open with mode a'; }

    // ── Mode: r (read) ──
    $f3 = $fileDir . 'mode_w_demo.txt';
    $h  = fopen($f3, 'r');
    if ($h) {
        $content = fread($h, filesize($f3));
        fclose($h);
        $results['r'] = ['status'=>'success', 'out'=>$content];
    } else { $errors['r'] = 'File does not exist for reading'; }

    // ── Mode: r+ (read+write) ──
    $f4 = $fileDir . 'mode_rplus_demo.txt';
    file_put_contents($f4, "Original content for r+ mode test.\n");
    $h  = fopen($f4, 'r+');
    if ($h) {
        $old = fread($h, 50);
        fseek($h, 0, SEEK_END);
        fwrite($h, "Appended via r+ at " . date('H:i:s') . "\n");
        fclose($h);
        $results['r+'] = ['status'=>'success', 'out'=>file_get_contents($f4)];
    } else { $errors['r+'] = 'Could not open with mode r+'; }

    // ── Mode: w+ (write+read) ──
    $f5 = $fileDir . 'mode_wplus_demo.txt';
    $h  = fopen($f5, 'w+');
    if ($h) {
        fwrite($h, "Mode w+ — Written at " . date('d M Y H:i:s') . "\nThis truncates and allows read.\n");
        rewind($h);
        $readBack = fread($h, 200);
        fclose($h);
        $results['w+'] = ['status'=>'success', 'out'=>$readBack];
    } else { $errors['w+'] = 'Could not open with mode w+'; }

    // ── Mode: a+ (append+read) ──
    $f6 = $fileDir . 'mode_aplus_demo.txt';
    $h  = fopen($f6, 'a+');
    if ($h) {
        fwrite($h, "a+ Line at " . date('H:i:s') . "\n");
        rewind($h);
        $readBack = fread($h, 500);
        fclose($h);
        $results['a+'] = ['status'=>'success', 'out'=>$readBack];
    } else { $errors['a+'] = 'Could not open with mode a+'; }

    // ── fgets / fgetc demo ──
    $f7 = $fileDir . 'mode_w_demo.txt';
    $h  = fopen($f7, 'r');
    $lines = []; $chars = '';
    if ($h) {
        while (!feof($h)) {
            $line = fgets($h);
            if ($line) $lines[] = rtrim($line);
        }
        fclose($h);
        $h = fopen($f7, 'r');
        for ($i=0; $i<10; $i++) $chars .= fgetc($h);
        fclose($h);
        $results['fgets'] = ['lines' => $lines, 'chars' => $chars];
    }
}

include 'nav.php';
?>

<div class="page-header">
    <div>
        <h2>File Modes Demo</h2>
        <p>Live demonstration of all PHP file open modes: r, w, a, r+, w+, a+</p>
    </div>
    <a href="?run=1" class="btn btn-primary"><i class="fas fa-play"></i> Run All Demos</a>
</div>

<!-- Modes Reference Table -->
<div class="card" style="margin-bottom:24px;">
    <div class="card-title"><i class="fas fa-table"></i> PHP File Mode Reference</div>
    <div class="table-wrap" style="border:none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Mode</th>
                    <th>Description</th>
                    <th>Read</th>
                    <th>Write</th>
                    <th>Creates File</th>
                    <th>Truncates</th>
                    <th>Pointer Starts</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $modes = [
                    ['r',   'Open for reading only',                  '✅','❌','❌','❌','Beginning'],
                    ['r+',  'Open for reading and writing',            '✅','✅','❌','❌','Beginning'],
                    ['w',   'Open for writing only (truncate/create)', '❌','✅','✅','✅','Beginning'],
                    ['w+',  'Open for reading and writing (truncate)', '✅','✅','✅','✅','Beginning'],
                    ['a',   'Open for writing (append/create)',        '❌','✅','✅','❌','End'],
                    ['a+',  'Open for reading and writing (append)',   '✅','✅','✅','❌','End'],
                    ['x',   'Create for writing (fails if exists)',    '❌','✅','✅','❌','Beginning'],
                    ['x+',  'Create for r/w (fails if exists)',        '✅','✅','✅','❌','Beginning'],
                    ['c',   'Open for writing (no truncate)',          '❌','✅','✅','❌','Beginning'],
                    ['c+',  'Open for r/w (no truncate)',              '✅','✅','✅','❌','Beginning'],
                ];
                foreach ($modes as [$mode,$desc,$r,$w,$c,$t,$ptr]):
                    $isMain = in_array($mode,['r','r+','w','w+','a','a+']);
                ?>
                <tr style="<?= $isMain?'':'opacity:0.6;' ?>">
                    <td><code style="color:var(--accent);font-size:14px;font-weight:700;"><?= $mode ?></code>
                    <?php if ($isMain): ?><span class="badge badge-info" style="margin-left:6px;font-size:9px;">Core</span><?php endif; ?></td>
                    <td><?= $desc ?></td>
                    <td style="color:<?= $r==='✅'?'var(--success)':'var(--danger)' ?>;text-align:center;"><?= $r ?></td>
                    <td style="color:<?= $w==='✅'?'var(--success)':'var(--danger)' ?>;text-align:center;"><?= $w ?></td>
                    <td style="color:<?= $c==='✅'?'var(--success)':'var(--danger)' ?>;text-align:center;"><?= $c ?></td>
                    <td style="color:<?= $t==='✅'?'var(--warning)':'var(--muted)' ?>;text-align:center;"><?= $t ?></td>
                    <td style="color:var(--muted);font-size:12px;"><?= $ptr ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Run Results -->
<?php if (!empty($results) || !empty($errors)): ?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
<?php
$modeInfo = [
    'r'    => ['Read Mode', 'info',    'Opens existing file for reading. File pointer at beginning.'],
    'w'    => ['Write Mode', 'warning','Creates or truncates file for writing.'],
    'a'    => ['Append Mode','success','Creates or opens file. Writes at end. Existing data preserved.'],
    'r+'   => ['Read+Write', 'info',   'Opens existing file for both read and write.'],
    'w+'   => ['Write+Read', 'warning','Creates or truncates, allows read after write.'],
    'a+'   => ['Append+Read','success','Creates or opens, appends writes, allows reading.'],
    'fgets'=> ['fgets/fgetc','accent', 'Read file line by line (fgets) or char by char (fgetc).'],
];
foreach ($results as $mode => $res):
    $info = $modeInfo[$mode] ?? [$mode, 'muted', ''];
    $color = match($info[1]) {
        'info'=>'var(--info)', 'warning'=>'var(--warning)', 'success'=>'var(--success)', 'accent'=>'var(--accent)', default=>'var(--muted)'
    };
?>
<div class="card">
    <div class="card-title" style="color:<?= $color ?>;">
        <i class="fas fa-file-code"></i>
        Mode: <code style="color:<?= $color ?>;"><?= $mode ?></code> — <?= $info[0] ?>
    </div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:12px;"><?= $info[2] ?></p>
    <?php if ($mode === 'fgets'): ?>
    <div class="demo-block">
        <div class="demo-label">fgets() — Lines read:</div>
        <?php foreach ($res['lines'] as $i => $line): ?>
        <div class="demo-output">Line <?= $i+1 ?>: <?= htmlspecialchars($line) ?></div>
        <?php endforeach; ?>
    </div>
    <div class="demo-block" style="margin-top:10px;">
        <div class="demo-label">fgetc() — First 10 chars:</div>
        <div class="demo-output"><?= htmlspecialchars($res['chars']) ?></div>
    </div>
    <?php else: ?>
    <div class="demo-block">
        <div class="demo-label">File output:</div>
        <div class="demo-output"><?= htmlspecialchars($res['out']) ?></div>
    </div>
    <div style="margin-top:8px;font-size:11px;color:var(--muted);">
        <?php if ($res['status'] === 'success'): ?>
        <span style="color:var(--success);"><i class="fas fa-check"></i> Operation successful</span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php endforeach; ?>
</div>

<!-- Additional file pointer functions -->
<div class="card" style="margin-top:20px;">
    <div class="card-title"><i class="fas fa-code"></i> File Pointer Functions Reference</div>
    <div class="table-wrap" style="border:none;">
        <table class="table">
            <thead><tr><th>Function</th><th>Purpose</th><th>Example</th></tr></thead>
            <tbody>
                <tr><td><code style="color:var(--accent);">fopen($file, $mode)</code></td><td>Open file, returns handle</td><td><code style="color:var(--muted);">$h = fopen('a.txt','w')</code></td></tr>
                <tr><td><code style="color:var(--accent);">fread($h, $length)</code></td><td>Read up to $length bytes</td><td><code style="color:var(--muted);">fread($h, filesize($f))</code></td></tr>
                <tr><td><code style="color:var(--accent);">fwrite($h, $str)</code></td><td>Write string to file</td><td><code style="color:var(--muted);">fwrite($h, "Hello!")</code></td></tr>
                <tr><td><code style="color:var(--accent);">fgets($h)</code></td><td>Read one line</td><td><code style="color:var(--muted);">while($l=fgets($h))</code></td></tr>
                <tr><td><code style="color:var(--accent);">fgetc($h)</code></td><td>Read one character</td><td><code style="color:var(--muted);">$c = fgetc($h)</code></td></tr>
                <tr><td><code style="color:var(--accent);">feof($h)</code></td><td>Check end of file</td><td><code style="color:var(--muted);">while(!feof($h))</code></td></tr>
                <tr><td><code style="color:var(--accent);">rewind($h)</code></td><td>Move pointer to start</td><td><code style="color:var(--muted);">rewind($h)</code></td></tr>
                <tr><td><code style="color:var(--accent);">fseek($h, $pos)</code></td><td>Move to specific position</td><td><code style="color:var(--muted);">fseek($h, 0, SEEK_END)</code></td></tr>
                <tr><td><code style="color:var(--accent);">ftell($h)</code></td><td>Get current pointer position</td><td><code style="color:var(--muted);">$pos = ftell($h)</code></td></tr>
                <tr><td><code style="color:var(--accent);">fclose($h)</code></td><td>Close file handle</td><td><code style="color:var(--muted);">fclose($h)</code></td></tr>
            </tbody>
        </table>
    </div>
</div>

<?php else: ?>
<div class="empty-state" style="padding:60px;">
    <i class="fas fa-play-circle" style="font-size:56px;color:var(--accent);opacity:1;"></i>
    <h3>Ready to Run</h3>
    <p>Click the "Run All Demos" button above to execute all file mode demonstrations and see live output from each mode.</p>
    <a href="?run=1" class="btn btn-primary" style="margin-top:16px;"><i class="fas fa-play"></i> Run All Demos</a>
</div>
<?php endif; ?>

        </main>
        <footer class="page-footer">&copy; <?= date('Y') ?> LibManage</footer>
    </div>
</div>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');}</script>
</body>
</html>
