<?php
require_once '../config/connection.php';
requireLogin();
$page_title = 'File & String Functions Demo';

// ── Demo string: use a book title from DB for realism ──
$stmt = $pdo->query("SELECT title, author FROM books LIMIT 1");
$sampleBook = $stmt->fetch();
$sampleTitle  = $sampleBook['title']  ?? 'Introduction to PHP Programming';
$sampleAuthor = $sampleBook['author'] ?? 'John Doe';
$str = $sampleTitle;

// ── Demo file ──
$demoFile = __DIR__ . '/library_files/demo_functions.txt';
if (!is_dir(__DIR__ . '/library_files/')) mkdir(__DIR__ . '/library_files/', 0755, true);
if (!file_exists($demoFile)) {
    file_put_contents($demoFile, "Library Demo File\nCreated on: " . date('d M Y H:i:s') . "\nSample book: $sampleTitle\nAuthor: $sampleAuthor\n");
}
$fileContent = file_get_contents($demoFile);

include 'nav.php';
?>

<div class="page-header">
    <div>
        <h2>File & String Functions Demo</h2>
        <p>PHP Lab Task — All mandatory string and file functions with live output</p>
    </div>
    <a href="file_manager.php" class="btn btn-secondary btn-sm"><i class="fas fa-folder"></i> File Manager</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    <!-- PART A: String Functions -->
    <div>
        <div class="card" style="margin-bottom:20px;">
            <div class="card-title" style="color:var(--accent);"><i class="fas fa-font"></i> PART A — String Functions</div>
            <div style="background:var(--bg);border-radius:6px;padding:12px;margin-bottom:16px;font-size:12px;border:1px solid var(--border);">
                <span style="color:var(--muted);">Working String: </span>
                <code style="color:var(--info);">"<?= htmlspecialchars($str) ?>"</code>
            </div>

            <?php
            $fns = [
                ['strlen()', 'String Length', strlen($str), 'Number of characters'],
                ['str_word_count()', 'Word Count', str_word_count($str), 'Number of words'],
                ['strrev()', 'Reversed', strrev($str), 'String reversed'],
                ['strtoupper()', 'Uppercase', strtoupper($str), 'All capitals'],
                ['strtolower()', 'Lowercase', strtolower($str), 'All lowercase'],
                ['ucfirst()', 'UCFirst', ucfirst(strtolower($str)), 'First letter capital'],
                ['ucwords()', 'UCWords', ucwords(strtolower($str)), 'Each word capitalized'],
                ['trim()', 'Trimmed', trim("  " . $str . "  "), 'Whitespace removed'],
                ['ltrim()', 'L-Trimmed', ltrim("  " . $str . "  "), 'Left whitespace removed'],
                ['rtrim()', 'R-Trimmed', rtrim("  " . $str . "  "), 'Right whitespace removed'],
                ['substr(0,15)', 'Substring', substr($str, 0, 15) . '...', 'First 15 chars'],
                ['strpos("the")', 'Find Pos', strpos($str, 'the') !== false ? strpos($str,'the') : 'Not found', 'Position of "the"'],
                ['str_replace()', 'Replace', str_replace('o', '0', $str), 'Replace o→0'],
                ['strcmp()', 'String Compare', strcmp($str, $str) === 0 ? 0 : 'Different', 'Compare with itself'],
                ['strcasecmp()', 'Case Compare', strcasecmp($str, strtoupper($str)), 'Case-insensitive'],
                ['htmlspecialchars()', 'HTML Safe', htmlspecialchars('<b>' . $str . '</b>'), 'Escapes HTML'],
                ['addslashes()', 'Add Slashes', addslashes("It's a \"great\" book"), 'Escape quotes'],
            ];
            foreach ($fns as [$fn, $label, $result, $desc]):
            ?>
            <div class="demo-block" style="margin-bottom:10px;padding:12px 16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <code style="color:var(--accent);font-size:12px;"><?= $fn ?></code>
                    <span style="font-size:10px;color:var(--muted);"><?= $desc ?></span>
                </div>
                <div class="demo-output"><?= htmlspecialchars((string)$result) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div>
        <!-- PART B: File Functions -->
        <div class="card" style="margin-bottom:20px;">
            <div class="card-title" style="color:var(--info);"><i class="fas fa-file-code"></i> PART B — File Functions</div>
            <div style="background:var(--bg);border-radius:6px;padding:12px;margin-bottom:16px;font-size:12px;border:1px solid var(--border);">
                <span style="color:var(--muted);">Working File: </span>
                <code style="color:var(--info);">library_files/demo_functions.txt</code>
            </div>

            <?php
            $fileFns = [
                ['file_exists()', 'File Exists?', file_exists($demoFile) ? 'true' : 'false', 'Check existence'],
                ['filesize()', 'File Size', filesize($demoFile) . ' bytes', 'Size in bytes'],
                ['filemtime()', 'Modified Time', date('d M Y H:i:s', filemtime($demoFile)), 'Last modified'],
                ['filectime()', 'Created Time', date('d M Y H:i:s', filectime($demoFile)), 'Creation time'],
                ['basename()', 'Basename', basename($demoFile), 'Filename only'],
                ['dirname()', 'Dir Name', basename(dirname($demoFile)), 'Directory only'],
                ['pathinfo()', 'Path Info', json_encode(pathinfo($demoFile)), 'Full path breakdown'],
                ['is_file()', 'Is File?', is_file($demoFile) ? 'true' : 'false', 'Regular file check'],
                ['is_readable()', 'Readable?', is_readable($demoFile) ? 'true' : 'false', 'Read permission'],
                ['is_writable()', 'Writable?', is_writable($demoFile) ? 'true' : 'false', 'Write permission'],
            ];
            foreach ($fileFns as [$fn, $label, $result, $desc]):
            ?>
            <div class="demo-block" style="margin-bottom:10px;padding:12px 16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <code style="color:var(--info);font-size:12px;"><?= $fn ?></code>
                    <span style="font-size:10px;color:var(--muted);"><?= $desc ?></span>
                </div>
                <div class="demo-output" style="word-break:break-all;"><?= htmlspecialchars((string)$result) ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- PART C: Variables & Scope -->
        <div class="card" style="margin-bottom:20px;">
            <div class="card-title" style="color:var(--success);"><i class="fas fa-code"></i> PART C — Variable Scope Demo</div>

            <?php
            // Global variable
            $globalVar = "I am a GLOBAL variable";
            $counter   = 0;

            function localScopeDemo() {
                $localVar = "I am a LOCAL variable — only inside this function";
                return $localVar;
            }

            function globalScopeDemo() {
                global $globalVar;
                return "Accessed from inside function: " . $globalVar;
            }

            function staticScopeDemo() {
                static $count = 0;
                $count++;
                return "Static counter value: $count";
            }
            ?>

            <div class="demo-block" style="margin-bottom:10px;padding:12px 16px;">
                <div class="demo-label">Local Scope</div>
                <div class="demo-output"><?= htmlspecialchars(localScopeDemo()) ?></div>
            </div>
            <div class="demo-block" style="margin-bottom:10px;padding:12px 16px;">
                <div class="demo-label">Global Scope</div>
                <div class="demo-output"><?= htmlspecialchars(globalScopeDemo()) ?></div>
            </div>
            <div class="demo-block" style="margin-bottom:10px;padding:12px 16px;">
                <div class="demo-label">Static Scope (called 3 times)</div>
                <div class="demo-output"><?= staticScopeDemo() ?><br><?= staticScopeDemo() ?><br><?= staticScopeDemo() ?></div>
            </div>

            <!-- PHP Data Types -->
            <div class="demo-block" style="padding:12px 16px;">
                <div class="demo-label">PHP Data Types</div>
                <?php
                $types = [
                    ['string',  '$title',   $sampleTitle,           'string'],
                    ['integer', '$year',    2024,                   'integer'],
                    ['float',   '$fine',    25.50,                  'double'],
                    ['boolean', '$avail',   true,                   'boolean'],
                    ['array',   '$cats',    ['PHP','CS','Fiction'], 'array'],
                    ['null',    '$empty',   null,                   'NULL'],
                ];
                foreach ($types as [$type,$var,$val,$actual]):
                ?>
                <div style="display:flex;gap:10px;padding:3px 0;font-size:12px;">
                    <code style="color:var(--warning);width:70px;"><?= $var ?></code>
                    <span style="color:var(--success);width:70px;"><?= $actual ?></span>
                    <span style="color:var(--text);"><?= htmlspecialchars(is_array($val)?implode(',',$val):var_export($val,true)) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- PART D: Output Functions -->
        <div class="card">
            <div class="card-title" style="color:var(--warning);"><i class="fas fa-terminal"></i> PART D — Output Functions</div>
            <div class="demo-block" style="padding:14px;">
                <div class="demo-label">echo vs print vs die()</div>
                <?php ob_start(); ?>
                <div style="font-size:13px;color:var(--text);line-height:2;">
                    <?php echo "<span style='color:var(--accent);'>echo</span>: Quick output — " . htmlspecialchars($sampleTitle) . "<br>"; ?>
                    <?php print "<span style='color:var(--info);'>print</span>: Returns 1, outputs — " . htmlspecialchars($sampleAuthor) . "<br>"; ?>
                    <span style='color:var(--danger);'>die()</span>: Stops script execution — used in connection failures, validation errors
                </div>
                <?php ob_end_flush(); ?>
            </div>
        </div>
    </div>
</div>

        </main>
        <footer class="page-footer">&copy; <?= date('Y') ?> LibManage</footer>
    </div>
</div>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');}</script>
</body>
</html>
