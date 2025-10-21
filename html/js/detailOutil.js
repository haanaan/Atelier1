// 🔹 Récupération de l'ID depuis l'URL
const params = new URLSearchParams(window.location.search);
const id = params.get('id');

if (!id) {
  document.getElementById("outil-detail").textContent = "Aucun ID d'outil fourni dans l'URL.";
} else {
  const API_URL = `http://localhost:6080/api/outils/${id}`; 
  console.log(id);

  // 🔹 Appel au backend pour récupérer les détails de l'outil
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
      document.getElementById("montant").textContent = data.montant;
    })
    .catch(error => {
      console.error(error);
      document.getElementById("outil-detail").textContent = "Erreur de chargement du détail de l’outil.";
    });
}
