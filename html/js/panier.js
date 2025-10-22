document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("liste-panier");
    const panier = JSON.parse(localStorage.getItem("panier")) || [];

    if (!panier || panier.length === 0) {
        container.innerHTML = `<p style="text-align:center; color:#555;">Aucune réservation pour le moment.</p>`;
        return;
    }

    panier.forEach(item => {
        const article = document.createElement("article");
        article.className = "outil";
        
        const date = new Date(item.date);
        const formattedDate = date.toISOString().split('T')[0]; // Date
        const formattedTime = date.toTimeString().split(' ')[0].slice(0, 5); //  Heure + min

        article.innerHTML = `
            <div><img src="images/${item.image}" alt="${item.nom}"></div>
            <h2>${item.nom}</h2>
            <p>Date de réservation : <strong>${formattedDate} | ${formattedTime}</strong></p>
            <p class="prix">${item.prix} €/heure</p>
            <p class="stock">${item.statut}</p>
        `;
        container.appendChild(article);
    });

    document.getElementById("confirmerPanier").addEventListener("click", () => {
        if (panier.length === 0) return alert("Panier vide 😅");
        alert("✅ Panier confirmé !");
        localStorage.removeItem("panier");
        window.location.reload();
    });

    document.getElementById("viderPanier").addEventListener("click", () => {
        if (confirm("Vider le panier ?")) {
            localStorage.removeItem("panier");
            window.location.reload();
        }
    });
});
