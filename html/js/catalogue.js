document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("calendrierModal");
  const outilTitre = document.getElementById("outilTitre");
  const closeBtn = document.querySelector(".close");
  const confirmerBtn = document.getElementById("confirmerBtn");

  let outilSelectionne = null;

  // Initialisation calendrier
  const fp = flatpickr("#datePicker", {
  enableTime: true,
  dateFormat: "Y-m-d H:i",
  minDate: "today",
  locale: flatpickr.l10ns.fr,
  time_24hr: true
});

  // Quand on clique sur Réserver
  document.querySelectorAll(".btn-reserver").forEach((btn) => {
    btn.addEventListener("click", () => {
      const article = btn.closest(".outil");

      outilSelectionne = {
        nom: article.querySelector("h2").textContent.trim(),
        image: article.querySelector("img").getAttribute("src"),
        prix: article.querySelector(".prix").textContent.trim(),
      };

      outilTitre.textContent = "Réserver : " + outilSelectionne.nom;
      modal.style.display = "flex";
    });
  });

  // Fermer modal
  closeBtn.onclick = () => (modal.style.display = "none");
  window.onclick = (e) => { if (e.target === modal) modal.style.display = "none"; };

  // Confirmer la réservation
  confirmerBtn.onclick = () => {
    const date = fp.input.value;
    if (!date) return alert("Choisis une date d’abord 😅");

    const reservation = {
      nom: outilSelectionne.nom,
      image: outilSelectionne.image,
      prix: outilSelectionne.prix,
      date: date,
      statut: "En attente ⏳",
    };

    let panier = JSON.parse(localStorage.getItem("panier")) || [];
    panier.push(reservation);
    localStorage.setItem("panier", JSON.stringify(panier));

    console.log("✅ Panier actuel :", panier);

    alert(`✅ ${outilSelectionne.nom} ajouté au panier !`);

    modal.style.display = "none";
    fp.clear();

    // délai court pour que le localStorage s’enregistre
    setTimeout(() => {
      window.location.href = "panier.html";
    }, 500);
  };

  // ==================== PAGE PANIER ====================
  if (document.body.classList.contains("page-panier")) {
    const container = document.getElementById("liste-panier");
    const panier = JSON.parse(localStorage.getItem("panier")) || [];

    console.log("📦 Contenu panier :", panier);

    if (!panier || panier.length === 0) {
      container.innerHTML = `<p style="text-align:center; color:#555;">Aucune réservation pour le moment.</p>`;
      return;
    }

    panier.forEach((item, index) => {
      const article = document.createElement("article");
      article.className = "outil";
      article.innerHTML = `
        <img src="${item.image}" alt="${item.nom}">
        <h2>${item.nom}</h2>
        <p>Date de réservation : <strong>${new Date(item.date).toLocaleString()}</strong></p>
        <p class="prix">${item.prix}</p>
        <p class="stock">${item.statut}</p>
        <button class="btn-supprimer" data-index="${index}">🗑️ Supprimer</button>
      `;
      container.appendChild(article);
    });

    container.addEventListener("click", (e) => {
      if (e.target.classList.contains("btn-supprimer")) {
        const i = e.target.dataset.index;
        panier.splice(i, 1);
        localStorage.setItem("panier", JSON.stringify(panier));
        window.location.reload();
      }
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
  }
});
