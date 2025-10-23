document.addEventListener("DOMContentLoaded", () => {
  const categoriesContainer = document.getElementById("categories");
  const loader = document.getElementById("loader");

  const showLoader = () => (loader.style.display = "block");
  const hideLoader = () => (loader.style.display = "none");

  const fetchCategoriesAndTools = async () => {
    showLoader();
    try {
      const categoriesResponse = await fetch(
        "http://localhost:6080/api/categories"
      );
      if (!categoriesResponse.ok)
        throw new Error("Erreur lors de la récupération des catégories.");
      const categories = await categoriesResponse.json();

      categoriesContainer.innerHTML = "";

      const fetchToolsPromises = categories.map(async (cat) => {
        const section = document.createElement("section");
        section.classList.add("category");
        section.innerHTML = `
          <h2>${cat.nom}</h2>
          <p class="description">${cat.description || ""}</p>
          <div class="tools-grid" id="cat-${cat.id}"></div>
        `;
        categoriesContainer.appendChild(section);

        const toolsResponse = await fetch(`http://localhost:6080/api/outils`);
        if (!toolsResponse.ok)
          throw new Error(`Erreur pour la catégorie ${cat.nom}`);
        const allOutils = await toolsResponse.json();

        const outilsCat = allOutils.filter((outil) => {
          return true;
        });

        const detailedPromises = outilsCat.map(async (outil) => {
          const res = await fetch(
            `http://localhost:6080/api/outils/${outil.id}`
          );
          if (!res.ok) throw new Error(`Erreur pour l'outil ${outil.id}`);
          return res.json();
        });

        const detailedOutils = await Promise.all(detailedPromises);
        const filteredOutils = detailedOutils.filter(
          (outil) => outil.categorie === cat.nom
        );

        displayTools(cat.id, filteredOutils);
      });

      await Promise.all(fetchToolsPromises);
    } catch (error) {
      console.error(error);
      alert("Impossible de charger les catégories ou les outils.");
    } finally {
      hideLoader();
    }
  };

  const displayTools = (categoryId, outils) => {
    const grid = document.getElementById(`cat-${categoryId}`);
    if (!grid) return;

    grid.innerHTML = "";

    if (outils.length === 0) {
      grid.innerHTML =
        "<p>Aucun outil dans cette catégorie pour le moment.</p>";
      return;
    }

    const limitedOutils = outils.slice(0, 4);

    limitedOutils.forEach((outil) => {
      const article = document.createElement("article");
      const imgSrc = outil.image
        ? `images/${outil.image}`
        : "images/default.jpg";

      article.innerHTML = `
        <img src="${imgSrc}" alt="${outil.nom}" />
        <h3>${outil.nom}</h3>
        <button data-id="${outil.id}">Voir l’outil</button>
      `;
      grid.appendChild(article);

      const button = article.querySelector("button");
      button.addEventListener("click", () => fetchOutilDetail(outil.id));
    });
  };

  const fetchOutilDetail = async (outilId) => {
    try {
      const response = await fetch(
        `http://localhost:6080/api/outils/${outilId}`
      );
      if (!response.ok)
        throw new Error(`Erreur lors du chargement de l'outil ${outilId}`);
      const outil = await response.json();

      alert(
        `Nom: ${outil.nom}\nCatégorie: ${outil.categorie}\nDescription: ${
          outil.description || "Aucune"
        }`
      );
    } catch (error) {
      console.error(error);
      alert("Impossible de charger les informations de l'outil.");
    }
  };

  fetchCategoriesAndTools();
});
