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
            header("Location: index.php");
            exit;
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Note</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow p-4" style="max-width: 600px; width: 100%;">
        <h1 class="h4 mb-4">Edit Note</h1>

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

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="index.php" class="btn btn-link">Back</a>
            </div>
        </form>
    </div>
</body>
</html>