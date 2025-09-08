<?php
include 'auth.php';
include 'connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $deleteSQL = "DELETE FROM NotesTable WHERE id = ?";
    $stmt = $connection->prepare($deleteSQL);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting note: " . $connection->error;
    }
} else {
    echo "Invalid request.";
}
?>