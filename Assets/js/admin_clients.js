document.querySelectorAll(".delete-client").forEach(btn => {
    btn.addEventListener("click", function () {

        const id = this.dataset.id;

        if (!confirm("Supprimer ce client ?")) return;

        fetch("index.php?page=delete_client&id=" + id)
            .then(res => res.text())
            .then(data => {

                console.log(data);

                if (data === "OK") {
                    document.getElementById("client-" + id).remove();
                } else {
                    alert("Erreur suppression : " + data);
                }
            });
    });
});