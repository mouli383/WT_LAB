<?php
$uploadDir="uploads/";

if(!is_dir($uploadDir)){
    mkdir($uploadDir);
}

$msg="";

if(isset($_POST['upload'])){
    $file=$_FILES['file']['name'];
    $tmp=$_FILES['file']['tmp_name'];
    move_uploaded_file($tmp,$uploadDir.$file);
    $msg="File Uploaded!";
}

if(isset($_GET['delete'])){
    unlink($uploadDir.$_GET['delete']);
}

if(isset($_GET['download'])){
    $file=$uploadDir.$_GET['download'];
    header("Content-Disposition: attachment; filename=".basename($file));
    readfile($file);
    exit();
}

$files=scandir($uploadDir);
?>
<!DOCTYPE html>
<html>
<head>
<title>File Manager</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header"><h1>Mini File Manager</h1></header>

<form method="post" enctype="multipart/form-data">
<input type="file" name="file" required>
<button name="upload">Upload</button>
</form>

<p><?= $msg ?></p>

<table border="1" width="100%">
<tr>
<th>Name</th><th>Size (KB)</th><th>Modified</th><th>Action</th>
</tr>

<?php
foreach($files as $f){
if($f!="." && $f!=".."){
?>
<tr>
<td><?= $f ?></td>
<td><?= round(filesize($uploadDir.$f)/1024,2) ?></td>
<td><?= date("Y-m-d H:i:s",filemtime($uploadDir.$f)) ?></td>
<td>
<a href="?download=<?= $f ?>">Download</a> |
<a href="?delete=<?= $f ?>">Delete</a>
</td>
</tr>
<?php }} ?>
</table>

</body>
</html>
