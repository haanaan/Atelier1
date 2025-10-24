document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("calendrierModal");
    const outilTitre = document.getElementById("outilTitre");
    const closeBtn = document.querySelector(".close");
    const confirmerBtn = document.getElementById("confirmerBtn");
    const API_BASE_URL = 'http://localhost:24789/api';

    let outilSelectionne = null;

    const showLoader = () => {
        const loader = document.getElementById('loader');
        if (loader) loader.style.display = 'block';
    }

    const hideLoader = () => {
        const loader = document.getElementById('loader');
        if (loader) loader.style.display = 'none';
    }
    
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
            article.classList.add('outil');
            
            article.innerHTML = `
              <img src="images/${outil.image}" alt="${outil.nom}" onerror="this.src='images/default-tool.jpg'">
              <a href="detailOutil.html?id=${outil.id}"><h2>${outil.nom}</h2></a>
              <p class="stock">Exemplaires disponibles : ${outil.nombreExemplaires}</p>
              <button class="btn-reserver" data-id="${outil.id}">Réserver</button>
            `;

            catalogueSection.appendChild(article);
        });

        document.querySelectorAll('.btn-reserver').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const id = e.target.getAttribute('data-id');
                
                const user = JSON.parse(localStorage.getItem('user'));
                if (!user || !user.id) {
                    showMessage("Vous devez être connecté pour ajouter au panier", "error");
                    setTimeout(() => {
                        window.location.href = 'auth.html';
                    }, 1500);
                    return;
                }
                
                try {
                    addToPanier(id);
                } catch (error) {
                    fetchOutilDetails(id);
                }
            });
        });
    };

    const fetchOutilDetails = async (id) => {
        try {
            const response = await fetch(`${API_BASE_URL}/outils/${id}`);
            if (!response.ok) {
                throw new Error("Erreur dans la récupération des détails de l'outil.");
            }
            const outil = await response.json();
            showModal(outil); 
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
            categorie: outil.categorie
        };

        outilTitre.textContent = "Réserver : " + outilSelectionne.nom;
        document.getElementById("outilTitre").innerHTML = `
            <h3>${outilSelectionne.nom}</h3>
            <p>${outilSelectionne.description}</p>
            <p><strong>Catégorie:</strong> ${outilSelectionne.categorie}</p>
            <p><strong>Prix:</strong> ${outilSelectionne.montant} €/heure</p>
        `;
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
            
            const result = await response.json();
            
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

    if (closeBtn) {
        closeBtn.onclick = () => (modal.style.display = "none");
    }
    
    if (window.onclick) {
        window.onclick = (e) => { 
            if (modal && e.target === modal) modal.style.display = "none"; 
        };
    }

    if (confirmerBtn) {
        confirmerBtn.onclick = () => {
            const date = fp && fp.input ? fp.input.value : null;
            if (!date) return showMessage("Veuillez choisir une date", "error");
            
            addToPanier(outilSelectionne.id);
            
            if (modal) modal.style.display = "none";
            if (fp) fp.clear();
        };
    }

    fetchOutils(); 
});