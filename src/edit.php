<?php
include 'connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) { die("Invalid id"); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty(!$title) && $content !== '') {
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
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit note</title>
</head>
<body>
    <h1>Edit note</h1>
    <?php if (!empty($error)) echo '<p style="color:red">'.$error.'</p>'; ?>

    <form method="post">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($note['title']) ?>" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="5" cols="30" required><?= htmlspecialchars($note['content']) ?></textarea><br><br>

        <button type="submit">Update</button>
    </form>
    <br>
    <a href="index.php">Back to list</a>
</body>
</html>