<?php
$result = "";
$content = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $filename = $_POST["filename"];
    $mode = $_POST["mode"];

    $fp = @fopen($filename, $mode);

    if ($fp) {

        $message = "Written using $mode mode\n";

        // Write if mode allows writing
        if (in_array($mode, ['w','a','r+','w+','a+','x','x+'])) {
            fwrite($fp, $message);
        }

        fclose($fp);

        $result = "File opened successfully using mode: $mode";

        if (file_exists($filename)) {
            $content = htmlspecialchars(file_get_contents($filename));
        }

    } else {
        $result = "Failed to open file using mode: $mode";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dynamic File Mode Demo</title>
<style>
body { font-family: Arial; background:#f4f6f9; }
.container { width:60%; margin:auto; margin-top:30px; }
.card { background:white; padding:20px; border-radius:8px; box-shadow:0 0 5px #ccc; }
.success { color:green; }
.fail { color:red; }
pre { background:#eee; padding:10px; }
</style>
</head>
<body>

<div class="container">
<div class="card">

<h2>Task 3: File Operation Modes</h2>

<form method="post">
    <label>Enter File Name:</label><br>
    <input type="text" name="filename" required><br><br>

    <label>Select Mode:</label><br>
    <select name="mode" required>
        <option value="r">r</option>
        <option value="w">w</option>
        <option value="a">a</option>
        <option value="x">x</option>
        <option value="r+">r+</option>
        <option value="w+">w+</option>
        <option value="a+">a+</option>
        <option value="x+">x+</option>
    </select><br><br>

    <button type="submit">Execute</button>
</form>

<hr>

<?php if ($result != "") { ?>
    <p><strong>Result:</strong> <?= $result ?></p>

    <?php if ($content != "") { ?>
        <h3>File Content:</h3>
        <pre><?= $content ?></pre>
    <?php } ?>
<?php } ?>

<hr>

<h3>Mode Explanation</h3>
<p>
r → Read only (Fails if file not exists)<br>
w → Write only (Erases old data)<br>
a → Append only<br>
x → Create new file (Fails if exists)<br>
r+ → Read & Write<br>
w+ → Read & Write (Erases old data)<br>
a+ → Read & Append<br>
x+ → Create new file for Read & Write<br>
</p>

</div>
</div>

</body>
</html>
