<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("<div class='alert alert-danger'>Invalid ID</div>");
}
?>

<form method="post" id="editForm" data-id="<?= $id ?>">
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input 
            type="text" 
            name="title" 
            value="" 
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
        ></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
