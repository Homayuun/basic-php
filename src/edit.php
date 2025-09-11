<?php
include 'auth.php';
include 'connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) { die("Invalid id"); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $updateSQL = "UPDATE NotesTable SET title = ?, content = ? WHERE id = ?";
        $stmt = $connection->prepare($updateSQL);
        $stmt->bind_param("ssi", $title, $content, $id);

        if ($stmt->execute()) {
            exit("OK");
        } else {
            die("Error updating: " . $connection->error);
        }
    } else {
        $error = "Title and content are required.";
    }
}

$selectSQL = "SELECT * FROM NotesTable WHERE id = ?";
$stmt = $connection->prepare($selectSQL);
$stmt->bind_param("i", $id);
$stmt->execute();
$note = $stmt->get_result()->fetch_assoc();
if (!$note) { die("Note not found"); }
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input 
            type="text" 
            name="title" 
            value="<?= htmlspecialchars($note['title']) ?>" 
            required 
            class="form-control"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea 
            name="content" 
            rows="5" 
            required 
            class="form-control"
        ><?= htmlspecialchars($note['content']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
