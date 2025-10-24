document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");
  const nomCategorie = document.getElementById("categorie-nom");
  const descCategorie = document.getElementById("categorie-description");
  const outilsGrid = document.getElementById("outils-grid");

  const chargerCategorieEtOutils = async () => {
    if (!id) {
      nomCategorie.textContent = "Catégorie inconnue";
      outilsGrid.innerHTML = "<p>Impossible de charger les outils.</p>";
      return;
    }

    try {
      const catRes = await fetch(`http://localhost:6080/api/categories`);
      if (!catRes.ok)
        throw new Error("Erreur lors du chargement des catégories");
      const categories = await catRes.json();
      const cat = categories.find((c) => c.id === id);
      if (!cat) throw new Error("Catégorie non trouvée");

      nomCategorie.textContent = cat.nom;
      descCategorie.textContent = cat.description || "";

      const outilsRes = await fetch(
        `http://localhost:6080/api/categories/${id}/outils`
      );
      if (!outilsRes.ok)
        throw new Error("Erreur lors du chargement des outils");
      const outils = await outilsRes.json();

      if (outils.length === 0) {
        outilsGrid.innerHTML =
          "<p>Aucun outil disponible dans cette catégorie.</p>";
        return;
      }

      outilsGrid.innerHTML = "";
      outils.forEach((outil) => {
        const article = document.createElement("article");
        const imgSrc = outil.image
          ? `images/${outil.image}`
          : "images/default.jpg";
        article.innerHTML = `
          <img src="${imgSrc}" alt="${outil.nom}" />
          <h3>${outil.nom}</h3>
          <p>${outil.description || "Aucune description"}</p>
        `;
        outilsGrid.appendChild(article);
      });
    } catch (err) {
      console.error(err);
      nomCategorie.textContent = "Erreur lors du chargement de la catégorie.";
      outilsGrid.innerHTML = "<p>Impossible de charger les outils.</p>";
    }
  };

  chargerCategorieEtOutils();
});
