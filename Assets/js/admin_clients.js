document.querySelectorAll(".delete-client").forEach(btn => {
    btn.addEventListener("click", function () {

        const id = this.dataset.id;

        if (!confirm("Supprimer ce client ?")) return;

        fetch("index.php?page=delete_client&id=" + id, {
            method: "GET"
        })
            .then(res => res.text())
            .then(data => {
                document.getElementById("client-" + id).remove();
            });
    });
});