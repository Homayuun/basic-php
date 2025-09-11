let currentPage = 1;
let perPage = 5;

async function fetchNotes(page) {
    currentPage = page;
    const res = await fetch(`index_ajax.php?action=load&page=${page}&perPage=${perPage}`);
    return res.json();
}

async function deleteNoteRequest(id) {
    const res = await fetch("index_ajax.php?action=delete&id=" + id, { method: "POST" });
    return res.json();
}

async function fetchForm(url) {
    const res = await fetch(url);
    return res.text();
}

async function submitForm(url, formData) {
    const res = await fetch(url, { method: "POST", body: formData });
    return res.text();
}