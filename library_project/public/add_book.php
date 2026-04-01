<?php
require_once '../config/connection.php';
requireLogin();
requireRole(['admin','librarian']);

$page_title = 'Add New Book';
$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$book = null;

if ($edit_id) {
    $page_title = 'Edit Book';
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$edit_id]);
    $book = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && $edit_id) {
        $pdo->prepare("DELETE FROM books WHERE id = ?")->execute([$edit_id]);
        flashMessage('success', 'Book deleted successfully.');
        header('Location: view_books.php');
        exit;
    }

    $title    = trim($_POST['title']);
    $author   = trim($_POST['author']);
    $isbn     = trim($_POST['isbn']);
    $category = trim($_POST['category']);
    $year     = trim($_POST['year']);
    $quantity = (int)$_POST['quantity'];
    $desc     = trim($_POST['description']);

    if ($edit_id) {
        $stmt = $pdo->prepare("UPDATE books SET title=?, author=?, isbn=?, category=?, year=?, quantity=?, available=available+(?-quantity), description=? WHERE id=?");
        $stmt->execute([$title, $author, $isbn, $category, $year, $quantity, $quantity, $desc, $edit_id]);
        flashMessage('success', 'Book updated successfully.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, isbn, category, year, quantity, available, description) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $author, $isbn, $category, $year, $quantity, $quantity, $desc]);
        flashMessage('success', 'Book added successfully.');
    }
    header('Location: view_books.php');
    exit;
}

include 'nav.php';
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas <?= $edit_id?'fa-pen-to-square':'fa-circle-plus' ?>"></i> <?= $page_title ?></h3>
    </div>

    <form method="POST">
        <div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:24px;">
            <div class="form-group">
                <label class="form-label">Book Title</label>
                <input type="text" name="title" class="form-control" value="<?= $book ? htmlspecialchars($book['title']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Authors</label>
                <input type="text" name="author" class="form-control" value="<?= $book ? htmlspecialchars($book['author']) : '' ?>" required>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:20px; margin-top:16px;">
            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" list="catList" value="<?= $book ? htmlspecialchars($book['category']) : '' ?>">
                <datalist id="catList">
                    <option>Fiction</option><option>Science</option><option>History</option><option>Technology</option>
                </datalist>
            </div>
            <div class="form-group">
                <label class="form-label">ISBN-13</label>
                <input type="text" name="isbn" class="form-control" value="<?= $book ? htmlspecialchars($book['isbn']) : '' ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Stock Quantity</label>
                <input type="number" name="quantity" class="form-control" value="<?= $book ? $book['quantity'] : '1' ?>" min="1" required>
            </div>
        </div>

        <div class="form-group" style="margin-top:16px;">
            <label class="form-label">Book Description</label>
            <textarea name="description" class="form-control" rows="4"><?= $book ? htmlspecialchars($book['description']) : '' ?></textarea>
        </div>

        <div style="margin-top:32px; display:flex; gap:12px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $edit_id?'Save Changes':'Publish Book' ?></button>
            <a href="view_books.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    <?php if ($edit_id): ?>
    <div style="margin-top:40px; padding-top:32px; border-top:1px solid var(--border);">
        <h4 style="color:var(--danger); margin-bottom:8px;">Danger Zone</h4>
        <p style="color:var(--text-dim); font-size:13px; margin-bottom:16px;">Permanently remove this book from the library infrastructure. This cannot be undone.</p>
        <form method="POST" onsubmit="return confirm('Deep Delete: Are you absolutely sure?')">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="btn btn-secondary" style="color:var(--danger); border-color:var(--danger);"><i class="fas fa-trash"></i> Delete Book</button>
        </form>
    </div>
    <?php endif; ?>
</div>

        </main>
    </div>
</div>
</body>
</html>
