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
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Note</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <h1 class="text-xl font-bold mb-4">Edit Note</h1>

        <?php if (!empty($error)): ?>
            <p class="text-red-500 mb-3"><?= $error ?></p>
        <?php endif; ?>

        <form method="post" class="space-y-3">
            <div>
                <label class="block mb-1">Title:</label>
                <input 
                type="text" name="title" value="<?= htmlspecialchars($note['title']) ?>" 
                required class="w-full border px-2 py-1 rounded focus:ring focus:ring-blue-300"
                >
            </div>

            <div>
                <label class="block mb-1">Content:</label>
                <textarea 
                name="content" rows="5" required
                class="w-full border px-2 py-1 rounded focus:ring focus:ring-blue-300"><?= htmlspecialchars($note['content']) 
                ?></textarea>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                    Update
                </button>
                <a href="index.php" class="text-blue-500 hover:underline">Back</a>
            </div>
        </form>
    </div>
</body>
</html>