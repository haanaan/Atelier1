document.addEventListener("DOMContentLoaded", () => {
  const categoriesSection = document.getElementById("categories");
  const loader = document.getElementById("loader");

  const showLoader = () => {
    loader.style.display = "block";
  };

  const hideLoader = () => {
    loader.style.display = "none";
  };

  const fetchCategories = async () => {
    showLoader();
    try {
      const response = await fetch("http://localhost:6080/api/outils");
      if (!response.ok) {
        throw new Error("Erreur lors de la récupération des catégories.");
      }
      const categories = await response.json();
      displayCategories(categories);
    } catch (error) {
      console.error("Erreur lors du chargement des catégories :", error);
      alert("Une erreur est survenue lors du chargement des catégories.");
    } finally {
      hideLoader();
    }
  };

  const displayCategories = (categories) => {
    categoriesSection.innerHTML = "";

    categories.forEach((cat) => {
      const article = document.createElement("article");
      article.classList.add("categorie");

      article.innerHTML = `
        <div>
          <img src="images/${cat.image || "default.jpg"}" alt="${cat.nom}" />
        </div>
        <a href="catalogue.html?categorie=${encodeURIComponent(cat.nom)}">
          <h2>${cat.nom}</h2>
        </a>
        <p>${
          cat.description || "Découvrez tous nos outils dans cette catégorie."
        }</p>
      `;

      categoriesSection.appendChild(article);
    });
  };

  fetchCategories();
});
