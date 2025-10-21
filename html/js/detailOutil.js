const API_URL = `http://localhost:6080//api/outils/{id}`;

// 🔹 Appel au backend
fetch(API_URL)
  .then(response => {
    if (!response.ok) throw new Error("Erreur lors du chargement des données");
    return response.json();
  })
  .then(data => {
    // 🔹 Affichage dans le HTML
    document.getElementById("nom").textContent = data.nom;
    document.getElementById("description").textContent = data.description;
    document.getElementById("categorie").textContent = data.categorie;
    document.getElementById("image").src = `http://localhost:8000/images/${data.image}`;
  })
  .catch(error => {
    console.error(error);
    document.getElementById("outil-detail").textContent = "Erreur de chargement du détail de l’outil.";
  });