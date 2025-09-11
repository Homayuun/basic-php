document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    try {
        let res = await fetch("auth_ajax.php?action=login", {
            method: "POST",
            body: formData
        });
        let text = await res.text();

        if (text.trim() === "OK") {
            window.location.href = "index.php";
        } else {
            let errBox = document.getElementById("loginError");
            errBox.textContent = text;
            errBox.classList.remove("d-none");
        }
    } catch {
        let errBox = document.getElementById("loginError");
        errBox.textContent = "Network error. Please try again.";
        errBox.classList.remove("d-none");
    }
});
