document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("calendrierModal");
  const outilTitre = document.getElementById("outilTitre");
  const closeBtn = document.querySelector(".close");
  const logoutBtn = document.querySelector("#logoutbtn");
  const confirmerBtn = document.getElementById("confirmerBtn");
  const API_BASE_URL = 'http://localhost:24789/api';

  let outilSelectionne = null;

  // Initialiser les champs de date avec des valeurs par défaut
  const datePicker = document.getElementById("datePicker");
  const timePicker = document.getElementById("timePicker");
  
  // Définir la date d'aujourd'hui comme valeur par défaut pour le datePicker
  const today = new Date();
  const formattedDate = today.toISOString().split('T')[0];
  if (datePicker) {
    datePicker.value = formattedDate;
    datePicker.min = formattedDate; // Empêcher la sélection de dates passées
  }
  
  // Définir une heure par défaut (10:00)
  if (timePicker) {
    timePicker.value = "10:00";
  }

  const showLoader = () => {
    const loader = document.getElementById('loader');
    if (loader) loader.style.display = 'block';
  };

  const hideLoader = () => {
    const loader = document.getElementById('loader');
    if (loader) loader.style.display = 'none';
  };
  
  const showMessage = (message, type = 'info') => {
    let messageContainer = document.getElementById('message-container');
    
    if (!messageContainer) {
      messageContainer = document.createElement('div');
      messageContainer.id = 'message-container';
      messageContainer.style.position = 'fixed';
      messageContainer.style.top = '20px';
      messageContainer.style.left = '50%';
      messageContainer.style.transform = 'translateX(-50%)';
      messageContainer.style.padding = '10px 20px';
      messageContainer.style.borderRadius = '4px';
      messageContainer.style.fontWeight = '500';
      messageContainer.style.zIndex = '1000';
      document.body.appendChild(messageContainer);
    }
    
    switch (type) {
      case 'success':
        messageContainer.style.backgroundColor = '#27ae60';
        messageContainer.style.color = 'white';
        break;
      case 'error':
        messageContainer.style.backgroundColor = '#e74c3c';
        messageContainer.style.color = 'white';
        break;
      default:
        messageContainer.style.backgroundColor = '#3498db';
        messageContainer.style.color = 'white';
    }
    
    messageContainer.textContent = message;
    messageContainer.style.display = 'block';
    
    setTimeout(() => {
      messageContainer.style.display = 'none';
    }, 3000);
  };

  const fetchOutils = async () => {
    showLoader();
    try {
      const response = await fetch(`${API_BASE_URL}/outils`);
      if (!response.ok) {
        throw new Error("Erreur dans la récupération des outils.");
      }
      const outils = await response.json();
      displayOutils(outils);
    } catch (error) {
      console.error('Erreur lors de la récupération des outils:', error);
      showMessage("Une erreur est survenue lors du chargement des outils.", "error");
    } finally {
      hideLoader();
    }
  };

  const displayOutils = (outils) => {
    const catalogueSection = document.getElementById('catalogue');
    catalogueSection.innerHTML = '';

    outils.forEach(outil => {
      const article = document.createElement('article');
      article.classList.add('outil', 'm3', 's12', 'l3');
      
      // Utiliser une image par défaut si l'image n'existe pas
      const imageUrl = outil.image ? `images/${outil.image}` : 'images/tool-placeholder.jpg';
      
      article.innerHTML = `
        <div class="img-container">
          <img src="${imageUrl}" alt="${outil.nom}" onerror="this.src='images/tool-placeholder.jpg'">
        </div>
        <a href="detailOutil.html?id=${outil.id}"><h2>${outil.nom}</h2></a>
        <p class="stock">Exemplaires disponibles : ${outil.nombreExemplaires}</p>
        <div class="btn-container">
          <button class="btn-reserver" data-id="${outil.id}">Réserver</button>
          <button class="btn-ajouter-panier" data-id="${outil.id}">Ajouter au panier</button>
        </div>
      `;
      catalogueSection.appendChild(article);
    });

    // Écouteur pour le bouton réserver (ouvrir modal avec date)
    document.querySelectorAll('.btn-reserver').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const id = e.target.getAttribute('data-id');
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user || !user.id) {
          showMessage("Vous devez être connecté pour réserver", "error");
          setTimeout(() => {
            window.location.href = 'auth.html';
          }, 1500);
          return;
        }
        fetchOutilDetails(id, 'reserver');
      });
    });
    
    // Écouteur pour le bouton ajouter au panier (direct sans modal)
    document.querySelectorAll('.btn-ajouter-panier').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const id = e.target.getAttribute('data-id');
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user || !user.id) {
          showMessage("Vous devez être connecté pour ajouter au panier", "error");
          setTimeout(() => {
            window.location.href = 'auth.html';
          }, 1500);
          return;
        }
        addToPanier(id);
      });
    });
  };

  const fetchOutilDetails = async (id, action = 'reserver') => {
    try {
      const response = await fetch(`${API_BASE_URL}/outils/${id}`);
      if (!response.ok) {
        throw new Error("Erreur dans la récupération des détails de l'outil.");
      }
      const outil = await response.json();
      
      if (action === 'reserver') {
        showModal(outil);
      } else {
        addToPanier(id);
      }
    } catch (error) {
      console.error('Erreur lors de la récupération des détails de l\'outil:', error);
      showMessage("Une erreur est survenue lors du chargement des détails de l'outil.", "error");
    }
  };

  const showModal = (outil) => {
    outilSelectionne = {
      id: outil.id,
      nom: outil.nom,
      image: outil.image,
      description: outil.description,
      montant: outil.montant,
      categorie: outil.categorie_nom || outil.categorie
    };

    // Générer la date de fin par défaut (7 jours après la date de début)
    const endDate = new Date(datePicker.value);
    endDate.setDate(endDate.getDate() + 7);
    const formattedEndDate = endDate.toISOString().split('T')[0];
    
    // Calculer le prix total par défaut (pour 7 jours)
    const prixJournalier = parseFloat(outilSelectionne.montant);
    const totalEstime = (prixJournalier * 7).toFixed(2);

    outilTitre.innerHTML = `
      <h3>${outilSelectionne.nom}</h3>
      <p>${outilSelectionne.description || 'Aucune description disponible'}</p>
      <p><strong>Catégorie:</strong> ${outilSelectionne.categorie || 'Non catégorisé'}</p>
      <p><strong>Prix:</strong> ${prixJournalier.toFixed(2)} €/jour</p>
      <div class="dates-reservation">
        <div class="date-field">
          <label for="date-debut">Date de début:</label>
          <input type="date" id="date-debut" value="${datePicker.value}" min="${datePicker.value}">
        </div>
        <div class="date-field">
          <label for="date-fin">Date de fin:</label>
          <input type="date" id="date-fin" value="${formattedEndDate}" min="${datePicker.value}">
        </div>
      </div>
      <div class="prix-total">
        <p>Total estimé: <span>${totalEstime}</span> €</p>
      </div>
    `;
    
    // Mettre à jour le total lorsque les dates changent
    const dateDebut = document.getElementById('date-debut');
    const dateFin = document.getElementById('date-fin');
    
    if (dateDebut && dateFin) {
      const updateTotal = () => {
        const start = new Date(dateDebut.value);
        const end = new Date(dateFin.value);
        
        // S'assurer que la date de fin est après la date de début
        if (end < start) {
          end.setDate(start.getDate() + 1);
          dateFin.value = end.toISOString().split('T')[0];
        }
        
        // Calculer le nombre de jours
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        // Mettre à jour le total
        const newTotal = (prixJournalier * diffDays).toFixed(2);
        document.querySelector('.prix-total span').textContent = newTotal;
      };
      
      dateDebut.addEventListener('change', updateTotal);
      dateFin.addEventListener('change', updateTotal);
    }
    
    modal.style.display = "flex";
  };
  
  const addToPanier = async (outilId) => {
    showLoader();
    
    try {
      const user = JSON.parse(localStorage.getItem('user'));
      const userId = user && user.id ? user.id : 'a1a1a1a1-a1a1-a1a1-a1a1-a1a1a1a1a1a1';
      
      const token = localStorage.getItem('access_token');
      const headers = {
        'Content-Type': 'application/json'
      };
      
      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }
      
      const response = await fetch(`${API_BASE_URL}/users/${userId}/panier/outils/${outilId}`, {
        method: 'POST',
        headers: headers
      });
      
      if (!response.ok) {
        throw new Error(`Erreur lors de l'ajout au panier: ${response.status}`);
      }
      
      await response.json();
      
      // Stocker les dates sélectionnées dans localStorage si disponibles
      if (modal.style.display === "flex") {
        const dateDebut = document.getElementById('date-debut');
        const dateFin = document.getElementById('date-fin');
        
        if (dateDebut && dateFin) {
          // Sauvegarder les dates pour les utiliser dans la page panier
          localStorage.setItem('reservation_date_debut', dateDebut.value);
          localStorage.setItem('reservation_date_fin', dateFin.value);
          localStorage.setItem('reservation_total', document.querySelector('.prix-total span').textContent);
        }
      }
      
      showMessage("Outil ajouté au panier avec succès", "success");
      
      setTimeout(() => {
        window.location.href = 'panier.html';
      }, 1000);
      
    } catch (error) {
      console.error('Erreur lors de l\'ajout au panier:', error);
      showMessage("Erreur lors de l'ajout de l'outil au panier", "error");
    } finally {
      hideLoader();
    }
  };

  if (logoutBtn) {
    logoutBtn.onclick = () => {
      localStorage.removeItem('access_token');
      localStorage.removeItem('refresh_token');
      localStorage.removeItem('user');
      window.location.href = 'auth.html';
    };
  }

  if (closeBtn) {
    closeBtn.onclick = () => {
      modal.style.display = "none";
    };
  }

  window.onclick = (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  };

  if (confirmerBtn) {
    confirmerBtn.onclick = () => {
      if (modal.style.display === "flex") {
        const dateDebut = document.getElementById('date-debut');
        const dateFin = document.getElementById('date-fin');
        
        if (!dateDebut || !dateFin || !dateDebut.value || !dateFin.value) {
          showMessage("Veuillez choisir une date de début et de fin", "error");
          return;
        }
        
        const start = new Date(dateDebut.value);
        const end = new Date(dateFin.value);
        
        if (end < start) {
          showMessage("La date de fin doit être après la date de début", "error");
          return;
        }
        
        addToPanier(outilSelectionne.id);
      } else {
        if (!datePicker || !timePicker || !datePicker.value || !timePicker.value) {
          showMessage("Veuillez choisir une date et une heure", "error");
          return;
        }
        
        addToPanier(outilSelectionne.id, `${datePicker.value} ${timePicker.value}`);
      }
      
      modal.style.display = "none";
    };
  }

  fetchOutils();
});