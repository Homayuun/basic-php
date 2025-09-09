<?php
include 'auth.php';
include 'connect.php';

function getTotalNotes($connection, $userId) {
    $stmt = $connection->prepare("SELECT COUNT(*) AS total FROM NotesTable WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? intval($result->fetch_assoc()['total']) : 0;
}

function getNotes($connection, $userId, $limit, $offset) {
    $notes = [];
    $sql = "SELECT * FROM NotesTable WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iii", $userId, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notes[] = $row;
        }
    }
    return $notes;
}

$currentUserId = $_SESSION['user_id'];
$notesPerPage = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $notesPerPage;

$totalNotes = getTotalNotes($connection, $currentUserId);
$totalPages = ceil($totalNotes / $notesPerPage);
$notes = getNotes($connection, $currentUserId, $notesPerPage, $offset);
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Notes List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Notes</h1>
        <div>
            Logged in as: <?= htmlspecialchars($_SESSION['username']); ?> |
            <a href="logout.php" class="text-danger text-decoration-none">Logout</a>
        </div>
    </div>

    <a href="create.php" class="btn btn-primary mb-3" id="openCreateBtn">Add a new note</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
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
                            <a href="edit.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-warning edit-link">Edit</a>
                            <a href="delete.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No notes found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= max(1, $page - 1) ?>">&laquo;</a>
            </li>

            <?php if ($page > 3): ?>
                <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
                <?php if ($page > 4): ?>
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
                <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages - 2): ?>
                <?php if ($page < $totalPages - 3): ?>
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                <?php endif; ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $totalPages ?>"><?= $totalPages ?></a></li>
            <?php endif; ?>

            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>">&raquo;</a>
            </li>
        </ul>
    </nav>

    <div class="modal fade" id="noteModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Loading...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center p-5">Loading form...</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const modalEl = document.getElementById("noteModal");
        const modal = new bootstrap.Modal(modalEl);

        document.getElementById("openCreateBtn").addEventListener("click", e => {
            e.preventDefault();
            loadForm("create.php", "Add Note");
        });

        document.querySelectorAll(".edit-link").forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                loadForm(e.target.href, "Edit Note");
            });
        });

        function loadForm(url, title) {
            fetch(url)
                .then(res => res.text())
                .then(html => {
                    modalEl.querySelector(".modal-title").textContent = title;
                    modalEl.querySelector(".modal-body").innerHTML = html;
                    modal.show();

                    attachFormSubmit(modalEl.querySelector("form"), url);
                })
                .catch(() => {
                    modalEl.querySelector(".modal-body").innerHTML = "<div class='alert alert-danger'>Error loading form</div>";
                });
        }

        function attachFormSubmit(form, url) {
            if (!form) return;
            form.addEventListener("submit", e => {
                e.preventDefault();
                const formData = new FormData(form);

                fetch(url, { method: "POST", body: formData })
                    .then(res => res.text())
                    .then(response => {
                        if (response.trim() === "OK") {
                            window.location.reload();
                        } else {
                            modalEl.querySelector(".modal-body").innerHTML = response;
                        }
                    })
                    .catch(() => {
                        alert("Error submitting form");
                    });
            });
        }
    });
    </script>
</body>
</html>
