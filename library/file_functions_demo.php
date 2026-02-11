<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$filename = "demo.txt";
$copyfile = "demo_copy.txt";
$renamedfile = "demo_renamed.txt";
$folder = "uploads";
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Functions Demo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <h1> File Functions Demonstration</h1>
</header>

<div class="layout">
<main class="content">

<?php
echo "<h2> File Read / Write</h2>";

$file = fopen($filename, "w");
fwrite($file, "Hello Mouli!\nThis is PHP File Handling Demo.");
fclose($file);

echo "<b>File Written Successfully</b><br><br>";

$file = fopen($filename, "r");
echo "<b>Using fread():</b><br>";
echo nl2br(fread($file, filesize($filename)));
fclose($file);

echo "<br><br><b>Using file_get_contents():</b><br>";
echo nl2br(file_get_contents($filename));


file_put_contents($filename, "\nNew Line Added.", FILE_APPEND);

echo "<br><br><b>Using file() function:</b><br>";
$lines = file($filename);
foreach($lines as $line){
    echo $line . "<br>";
}

echo "<hr><h2> File Information</h2>";

echo "File Exists: " . (file_exists($filename) ? "Yes" : "No") . "<br>";
echo "File Size: " . filesize($filename) . " bytes<br>";
echo "File Type: " . filetype($filename) . "<br>";
echo "Last Access Time: " . date("Y-m-d H:i:s", fileatime($filename)) . "<br>";
echo "Last Modified Time: " . date("Y-m-d H:i:s", filemtime($filename)) . "<br>";
echo "Change Time: " . date("Y-m-d H:i:s", filectime($filename)) . "<br>";
echo "Permissions: " . substr(sprintf('%o', fileperms($filename)), -4) . "<br>";
echo "Owner ID: " . fileowner($filename) . "<br>";
echo "Group ID: " . filegroup($filename) . "<br>";
echo "Inode: " . fileinode($filename) . "<br>";

echo "<hr><h2> File & Folder Management</h2>";


copy($filename, $copyfile);
echo "File Copied<br>";

rename($filename, $renamedfile);
echo "File Renamed<br>";

echo "Is File? " . (is_file($renamedfile) ? "Yes" : "No") . "<br>";
echo "Is Directory? " . (is_dir($folder) ? "Yes" : "No") . "<br>";


if(!is_dir("temp_folder")){
    mkdir("temp_folder");
    echo "Temp Folder Created<br>";
}

unlink($copyfile);
echo "Copied File Deleted<br>";

rmdir("temp_folder");
echo "Temp Folder Removed<br>";

echo "<hr><h2> Directory Handling</h2>";

echo "Current Working Directory: " . getcwd() . "<br><br>";

echo "<b>Using scandir():</b><br>";
$files = scandir(".");
foreach($files as $f){
    echo $f . "<br>";
}

echo "<br><b>Using opendir():</b><br>";
$dir = opendir(".");
while(($file = readdir($dir)) !== false){
    echo $file . "<br>";
}
closedir($dir);

// Change directory
chdir($folder);
echo "<br>After chdir(): " . getcwd() . "<br>";
chdir("..");

echo "<hr><h2> File Locking</h2>";

$file = fopen($renamedfile, "a");

if(flock($file, LOCK_EX)){
    fwrite($file, "\nLocked Write Successful.");
    flock($file, LOCK_UN);
    echo "File Locked and Written Successfully<br>";
} else {
    echo "Could not Lock File<br>";
}

fclose($file);

echo "<br><h2> Task Completed Successfully</h2>";
?>

</main>
</div>

<footer class="footer">
<p>© 2025–26 Library + File Manager System</p>
</footer>

</body>
</html>
