<?php
include 'auth.php';
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $stmt = $connection->prepare("INSERT INTO NotesTable (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $currentUserId);
        if ($stmt->execute()) {
            exit("OK");
        } else {
            die("Error: " . $connection->error);
        }
    } else {
        $error = "Title and content are required.";
    }
}
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" required class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea name="content" rows="5" required class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>
