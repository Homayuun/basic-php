<?php
include 'auth.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notes List (AJAX)</title>
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

    <button class="btn btn-primary mb-3" id="addBtn">Add Note</button>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="notesTable"></tbody>
    </table>

    <ul class="pagination" id="pagination"></ul>

    <div class="modal fade" id="noteModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Loading...</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="text-center p-5">Loading...</div>
          </div>
        </div>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="notes.js"></script>
<script src="index.js"></script>
</body>
</html>