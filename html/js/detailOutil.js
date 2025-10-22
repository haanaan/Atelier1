const params = new URLSearchParams(window.location.search);
const id = params.get('id');

if (!id) {
  document.getElementById("outil-detail").textContent = "Aucun ID d'outil fourni dans l'URL.";
} else {
  const API_URL = `http://localhost:6080/api/outils/${id}`; 
  console.log(id);

  fetch(API_URL)
    .then(response => {
      if (!response.ok) throw new Error("Erreur lors du chargement des données");
      return response.json();
    })
    .then(data => {
      document.getElementById("nom").textContent = data.nom;
      document.getElementById("description").textContent = data.description;
      document.getElementById("categorie").textContent = data.categorie;
      document.getElementById("montant").textContent = `${data.montant} €`;

      const imageElement = document.createElement('img');
      imageElement.src = `images/${data.image}`;
      imageElement.alt = data.nom; 
      imageElement.style.maxWidth = '100%'; 

      document.getElementById("image").innerHTML = '';
      document.getElementById("image").appendChild(imageElement); 
    })
    .catch(error => {
      console.error(error);
      document.getElementById("outil-detail").textContent = "Erreur de chargement du détail de l’outil.";
    });
}
