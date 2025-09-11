function renderNotes(data) {
    let tbody = document.getElementById("notesTable");
    tbody.innerHTML = "";

    if (!data.success || data.notes.length === 0) {
        tbody.innerHTML = "<tr><td colspan='4'>No notes found</td></tr>";
        document.getElementById("pagination").innerHTML = "";
        return;
    }

    data.notes.forEach(note => {
        let tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${note.title}</td>
            <td>${note.content}</td>
            <td>${note.created_at}</td>
            <td>
                <button class="btn btn-sm btn-warning me-1" onclick="openEdit(${note.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteNote(${note.id})">Delete</button>
            </td>
        `;
        tbody.appendChild(tr);
    });

    buildPagination(data.totalPages, data.currentPage);
}

function buildPagination(totalPages, page) {
    let pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    if (page > 1) {
        pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadNotes(${page-1})">Prev</a></li>`;
    }

    for (let i = 1; i <= totalPages; i++) {
        let active = (i === page) ? "active" : "";
        pagination.innerHTML += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="loadNotes(${i})">${i}</a></li>`;
    }

    if (page < totalPages) {
        pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadNotes(${page+1})">Next</a></li>`;
    }
}

async function loadNotes(page) {
    document.getElementById("notesTable").innerHTML = "<tr><td colspan='4'>Loading...</td></tr>";
    try {
        let data = await fetchNotes(page);
        renderNotes(data);
    } catch {
        document.getElementById("notesTable").innerHTML = "<tr><td colspan='4'>Error loading notes</td></tr>";
    }
}

async function deleteNote(id) {
    if (!confirm("Are you sure?")) return;
    try {
        let data = await deleteNoteRequest(id);
        if (data.success) loadNotes(currentPage);
        else alert("Delete failed");
    } catch {
        alert("Network error");
    }
}

function openEdit(id) {
    openForm("edit.php?id=" + id, "Edit Note");
}

document.getElementById("addBtn").addEventListener("click", () => {
    openForm("create.php", "Add Note");
});

async function openForm(url, title) {
    try {
        let html = await fetchForm(url);
        document.querySelector("#noteModal .modal-title").textContent = title;
        document.querySelector("#noteModal .modal-body").innerHTML = html;

        let modal = new bootstrap.Modal(document.getElementById("noteModal"));
        modal.show();

        let form = document.querySelector("#noteModal form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            let formData = new FormData(form);
            let response = await submitForm(url, formData);

            if (response.trim().toLowerCase() === "ok") {
                modal.hide();
                loadNotes(currentPage);
            } else {
                document.querySelector("#noteModal .modal-body").innerHTML = response;
            }
        });
    } catch {
        alert("Error loading form");
    }
}

loadNotes(1);