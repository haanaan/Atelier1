document.addEventListener("DOMContentLoaded", () => {
  const categoriesContainer = document.getElementById("categories");
  const loader = document.getElementById("loader");

  const showLoader = () => (loader.style.display = "block");
  const hideLoader = () => (loader.style.display = "none");

  const fetchCategories = async () => {
    if (!categoriesContainer) {
      console.error("L'élément #categories n'existe pas dans le DOM !");
      return;
    }

    showLoader();
    try {
      const response = await fetch("http://localhost:6080/api/categories");
      if (!response.ok)
        throw new Error("Erreur lors de la récupération des catégories.");
      const categories = await response.json();
      displayCategories(categories);
    } catch (error) {
      console.error(error);
      alert("Impossible de charger les catégories.");
    } finally {
      hideLoader();
    }
  };

  const displayCategories = (categories) => {
    categoriesContainer.innerHTML = "";

    const fetchPromises = categories.map(async (cat) => {
      const section = document.createElement("section");
      section.classList.add("category");

      section.innerHTML = `
        <h2>${cat.nom}</h2>
        <p class="description">${cat.description}</p>
        <div class="tools-grid" id="cat-${cat.id}"></div>
      `;

      categoriesContainer.appendChild(section);

      await fetchToolsForCategory(cat.id);
    });

    // Charger toutes les catégories en parallèle
    return Promise.all(fetchPromises);
  };

  const fetchToolsForCategory = async (categoryId) => {
    try {
      const response = await fetch(
        `http://localhost:6080/api/outils?categorie=${categoryId}`
      );
      if (!response.ok)
        throw new Error(
          `Erreur lors du chargement des outils de la catégorie ${categoryId}.`
        );
      const outils = await response.json();
      displayTools(categoryId, outils);
    } catch (error) {
      console.error(error);
    }
  };

  const displayTools = (categoryId, outils) => {
    const grid = document.getElementById(`cat-${categoryId}`);
    if (!grid) return;
    grid.innerHTML = "";

    const limitedOutils = outils.slice(0, 4);

    limitedOutils.forEach((outil) => {
      const article = document.createElement("article");
      article.innerHTML = `
        <img src="images/${outil.image || "default.jpg"}" alt="${outil.nom}" />
        <h3>${outil.nom}</h3>
        <button onclick="window.location.href='catalogue.html?outil=${encodeURIComponent(
          outil.nom
        )}'">
          Voir l’outil
        </button>
      `;
      grid.appendChild(article);
    });
  };

  // Lancer le chargement
  fetchCategories();
});
