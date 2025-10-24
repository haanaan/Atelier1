document.addEventListener("DOMContentLoaded", () => {
  const sectionOutil = document.querySelector(".outil-detail");

  const params = new URLSearchParams(window.location.search);
  const outilId = params.get("id");

  if (!outilId) {
    sectionOutil.innerHTML = "<p>ID d'outil manquant.</p>";
    return;
  }

  fetch(`http://localhost:24789/api/outils/${outilId}`)
    .then((res) => {
      if (!res.ok) throw new Error("Outil non trouvé");
      return res.json();
    })
    .then((outil) => afficherOutil(outil))
    .catch((err) => {
      sectionOutil.innerHTML = `<p>Erreur : ${err.message}</p>`;
    });

  function afficherOutil(outil) {
    const imgSrc = outil.image
      ? `images/${outil.image}`
      : "images/default-tool.jpg";

    const categorieNom =
      typeof outil.categorie === "object"
        ? outil.categorie.nom
        : outil.categorie;

    const categorieId =
      typeof outil.categorie === "object"
        ? outil.categorie.id
        : outil.categorie_id ?? ""; // fallback si API renvoie juste l'id

    const exemplaires = parseInt(outil.exemplaires ?? 1);
    const dispoTexte =
      exemplaires > 0
        ? `${exemplaires} exemplaire${exemplaires > 1 ? "s" : ""} disponible${
            exemplaires > 1 ? "s" : ""
          }`
        : "Aucun exemplaire disponible";

    sectionOutil.innerHTML = `
      <div class="outil-card">
        <img src="${imgSrc}" alt="${outil.nom}" />
        <div class="outil-info">
          <h2>${outil.nom}</h2>
          <p><strong>Catégorie :</strong> ${categorieNom}</p>
          <p><strong>Description :</strong> ${outil.description}</p>
          <p><strong>Montant :</strong> ${outil.montant} €</p>
          <p class="disponibilite"><strong>Disponibilité :</strong> ${dispoTexte}</p>
          <button id="btn-retour" class="btn-retour">← Retour</button>
        </div>
      </div>
    `;

    // Écouteur pour le bouton retour
    const btnRetour = document.getElementById("btn-retour");
    if (btnRetour) {
      btnRetour.addEventListener("click", () => {
        if (categorieId) {
          window.location.href = `categorie.html?id=${categorieId}`;
        } else {
          window.history.back();
        }
      });
    }
  }
});
