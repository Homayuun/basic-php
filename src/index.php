<?php
include 'auth.php';
include 'connect.php';

$notesPerPage = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $notesPerPage;

$totalNotesResult = $connection->query("SELECT COUNT(*) AS total FROM NotesTable");
$totalNotes = $totalNotesResult->fetch_assoc()['total'];
$totalPages = ceil($totalNotes / $notesPerPage);

$getAllNotesSQL = "SELECT * FROM NotesTable ORDER BY created_at DESC LIMIT $notesPerPage OFFSET $offset";
$getAllNotesResult = $connection->query($getAllNotesSQL);

$notes = [];
if ($getAllNotesResult && $getAllNotesResult->num_rows > 0) {
    while ($row = $getAllNotesResult->fetch_assoc()) {
        $notes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notes List</title>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Notes</h1>
        <div>
            Logged in as: <?= htmlspecialchars($_SESSION['username']); ?> |
            <a href="logout.php" style="color: red; text-decoration: none;">Logout</a>
        </div>
    </div>

    <a href="create.php" style="color: blue; text-decoration: none;">Add a new note</a><br><br>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($notes)): ?>
                <?php foreach ($notes as $note): ?>
                    <tr>
                        <td><?= htmlspecialchars($note['title']) ?></td>
                        <td><?= nl2br(htmlspecialchars($note['content'])) ?></td>
                        <td><?= $note['created_at'] ?></td>
                        <td>
                            <a href="delete.php?id=<?= $note['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            <a href="edit.php?id=<?= $note['id'] ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No notes found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 10px;">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <?php if ($p == $page): ?>
                <strong><?= $p ?></strong>
            <?php else: ?>
                <a href="?page=<?= $p ?>"><?= $p ?></a>
            <?php endif; ?>
            <?= ($p < $totalPages) ? ' | ' : '' ?>
        <?php endfor; ?>
    </div>
</body>
</html>