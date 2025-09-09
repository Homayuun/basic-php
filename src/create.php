<?php
include 'auth.php';
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $insertNoteSQL = "INSERT INTO NotesTable (title, content, user_id) VALUES (?, ?, ?)";
        $statement = $connection->prepare($insertNoteSQL);
        $statement->bind_param("ssi", $title, $content, $currentUserId);

        if ($statement->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error: " . $connection->error;
        }
    } else {
        $error = "Title and content are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Note</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4 w-100" style="max-width: 500px;">
        <h1 class="h4 mb-4 text-center fw-bold">Add Note</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" name="title" required class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Content:</label>
                <textarea name="content" rows="5" required class="form-control"></textarea>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="index.php" class="btn btn-link">Back</a>
            </div>
        </form>
    </div>
</body>
</html>