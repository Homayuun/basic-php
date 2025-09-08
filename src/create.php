<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $insertNoteSQL = "INSERT INTO NotesTable (title, content) VALUES (?, ?)";
        $statement = $connection->prepare($insertNoteSQL);
        $statement->bind_param("ss", $title, $content);

        if ($statement->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error: " . $connection->error;
        }
    } else {
        echo "Title and content are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add a note</title>
</head>
<body>
    <h1>Add a note</h1>
    <form method="post" action="">
        <label>Title:</label><br>
        <input type="text" name="title" required style="border: 1px solid black;"><br><br>

        <label> Content:</label><br>
        <textarea name="content" rows="5" cols="30" required></textarea><br><br>

        <button type="submit">Save</button>
    </form>
    <br>
    <a href="index.php">Back to list</a>
</body>
</html>