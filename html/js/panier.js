document.addEventListener("DOMContentLoaded", () => {
    const API_BASE_URL = 'http://localhost:24789/api';
    const panierContainer = document.getElementById("liste-panier");
    const summaryContainer = document.getElementById("panier-summary");
    
    const toggleLoader = (show = true) => {
        let loader = document.getElementById('api-loader');
        
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'api-loader';
            
            const overlay = document.createElement('div');
            overlay.id = 'loader-overlay';
            
            document.body.appendChild(overlay);
            document.body.appendChild(loader);
        }
        
        const overlay = document.getElementById('loader-overlay');
        
        if (show) {
            loader.style.display = 'block';
            overlay.style.display = 'block';
        } else {
            loader.style.display = 'none';
            overlay.style.display = 'none';
        }
    };
    
    const showMessage = (message, type = 'info') => {
        let messageContainer = document.getElementById('message-container');
        
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.id = 'message-container';
            document.body.appendChild(messageContainer);
        }
        
        messageContainer.className = `message ${type}`;
        messageContainer.textContent = message;
        messageContainer.style.display = 'block';
        
        setTimeout(() => {
            messageContainer.style.display = 'none';
        }, 3000);
    };
    
    const fetchUserPanier = async () => {
        const user = JSON.parse(localStorage.getItem('user'));
        const userId = user && user.id ? user.id : 'a1a1a1a1-a1a1-a1a1-a1a1-a1a1a1a1a1a1';
        
        toggleLoader(true);
        
        try {
            const token = localStorage.getItem('access_token');
            const headers = {};
            
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
            
            const response = await fetch(`${API_BASE_URL}/users/${userId}/panier`, {
                headers: headers
            });
            
            if (!response.ok) {
                throw new Error(`Erreur ${response.status}`);
            }
            
            const panierData = await response.json();
            
            await fetchOutilsDetails(panierData);
            
        } catch (error) {
            console.error('Erreur lors de la récupération du panier:', error);
            panierContainer.innerHTML = `
                <div id="empty-panier">
                    <p>Une erreur est survenue lors du chargement de votre panier.</p>
                    <button onclick="window.location.href='catalogue.html'">
                        Retour au catalogue
                    </button>
                </div>
            `;
            summaryContainer.innerHTML = '';
        } finally {
            toggleLoader(false);
        }
    };
    
    const fetchOutilsDetails = async (panierData) => {
        const outils = panierData.items || [];
        
        if (!outils || outils.length === 0) {
            displayEmptyCart();
            return;
        }
        
        const outilsWithDetails = [];
        
        for (const outil of outils) {
            try {
                const outilId = outil.outilID || outil.id || outil.outil_id;
                const response = await fetch(`${API_BASE_URL}/outils/${outilId}`);
                
                if (response.ok) {
                    const outilDetails = await response.json();
                    outilsWithDetails.push({
                        ...outil,
                        ...outilDetails
                    });
                } else {
                    outilsWithDetails.push(outil);
                }
            } catch (error) {
                console.error(`Erreur lors de la récupération des détails de l'outil:`, error);
                outilsWithDetails.push(outil);
            }
        }
        
        displayPanier({
            ...panierData,
            items: outilsWithDetails
        });
    };
    
    const displayEmptyCart = () => {
        panierContainer.innerHTML = `
            <div id="empty-panier">
                <img src="images/empty-cart.svg" alt="Panier vide" onerror="this.style.display='none'">
                <h2>Votre panier est vide</h2>
                <p>Découvrez notre sélection d'outils et commencez à créer votre projet dès maintenant.</p>
                <button onclick="window.location.href='catalogue.html'">
                    Découvrir nos outils
                </button>
            </div>
        `;
        
        summaryContainer.innerHTML = '';
    };
    
    const displayPanier = (panierData) => {
        const outils = panierData.items || [];
        
        if (!outils || outils.length === 0) {
            displayEmptyCart();
            return;
        }
        
        panierContainer.innerHTML = '';
        
        outils.forEach(outil => {
            const item = document.createElement('article');
            
            const imageUrl = outil.image ? `images/${outil.image}` : `images/${outil.nom.toLowerCase().replace(/\s+/g, '-')}.jpg`;
            const outilId = outil.id || outil.outilID || outil.outil_id;
            
            item.innerHTML = `
                <div class="outil-image">
                    <img src="${imageUrl}" alt="${outil.nom}" >
                </div>
                <div class="outil-info">
                    <h2>${outil.nom}</h2>
                    <p>${outil.description || 'Aucune description disponible'}</p>
                    <div class="outil-categorie">${outil.categorie_nom || outil.categorie || 'Non catégorisé'}</div>
                </div>
                <div class="outil-prix">${parseFloat(outil.montant || outil.prix).toFixed(2)} €/jour</div>
                <div class="outil-actions">
                    <button class="btn-remove" id="remove-${outilId}" data-id="${outilId}">Supprimer</button>
                </div>
            `;
            
            panierContainer.appendChild(item);
            
            document.getElementById(`remove-${outilId}`).addEventListener('click', (e) => {
                const outilID = e.target.getAttribute('data-id');
                if (confirm("Êtes-vous sûr de vouloir retirer cet outil du panier ?")) {
                    removeFromPanier(outilID);
                }
            });
        });
        
        const currentDate = new Date();
        const futureDate = new Date();
        futureDate.setDate(currentDate.getDate() + 7);
        
        const formatDateForInput = (date) => {
            return date.toISOString().split('T')[0];
        };
        
        summaryContainer.innerHTML = `
            <div id="summary-content">
                <h2>Résumé de votre commande</h2>
                <div id="summary-items">
                    <span>${outils.length} article${outils.length > 1 ? 's' : ''}</span>
                </div>

                <div id="summary-total">
                    <span>Total estimé:</span>
                    <span>${parseFloat(panierData.total || 0).toFixed(2)} €</span>
                </div>
                <button id="checkout-button">Confirmer la réservation</button>
                <button id="continue-shopping">Continuer mes réservations</button>
                <button id="clear-cart">Vider mon panier</button>
            </div>
        `;
        
        document.getElementById('checkout-button').addEventListener('click', () => {
            createReservation(outils);
        });
        
        document.getElementById('continue-shopping').addEventListener('click', () => {
            window.location.href = 'catalogue.html';
        });
        
        document.getElementById('clear-cart').addEventListener('click', () => {
            if (confirm("Êtes-vous sûr de vouloir vider votre panier ?")) {
                clearPanier();
            }
        });
        
        const dateDebut = document.getElementById('date-debut');
        const dateFin = document.getElementById('date-fin');
        
        if (dateDebut && dateFin) {
            const updateDateRange = () => {
                const start = new Date(dateDebut.value);
                const end = new Date(dateFin.value);
                
                if (end < start) {
                    end.setDate(start.getDate() + 1);
                    dateFin.value = formatDateForInput(end);
                }
                
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                const dailyTotal = panierData.total || 0;
                const newTotal = dailyTotal * diffDays;
                
                document.querySelector('#summary-total span:last-child').textContent = 
                    `${parseFloat(newTotal).toFixed(2)} € (${diffDays} jour${diffDays > 1 ? 's' : ''})`;
            };
            
            dateDebut.addEventListener('change', updateDateRange);
            dateFin.addEventListener('change', updateDateRange);
        }
    };
    
    const removeFromPanier = async (outilID) => {
        toggleLoader(true);
        
        try {
            const user = JSON.parse(localStorage.getItem('user'));
            const userId = user && user.id ? user.id : 'a1a1a1a1-a1a1-a1a1-a1a1-a1a1a1a1a1a1';
            
            const token = localStorage.getItem('access_token');
            const headers = {};
            
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
            
            const response = await fetch(`${API_BASE_URL}/users/${userId}/panier/outils/${outilID}`, {
                method: 'DELETE',
                headers: headers
            });
            
            if (!response.ok) {
                throw new Error(`Erreur lors de la suppression: ${response.status}`);
            }
            
            showMessage("Outil retiré du panier", "success");
            fetchUserPanier();
            
        } catch (error) {
            console.error('Erreur lors de la suppression de l\'outil:', error);
            showMessage("Erreur lors de la suppression de l'outil", "error");
        } finally {
            toggleLoader(false);
        }
    };
    
    const clearPanier = async () => {
        toggleLoader(true);
        
        try {
            const user = JSON.parse(localStorage.getItem('user'));
            const userId = user && user.id ? user.id : 'a1a1a1a1-a1a1-a1a1-a1a1-a1a1a1a1a1a1';
            
            const token = localStorage.getItem('access_token');
            const headers = {};
            
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
            
            const response = await fetch(`${API_BASE_URL}/users/${userId}/panier/clear`, {
                method: 'DELETE',
                headers: headers
            });
            
            if (!response.ok) {
                throw new Error(`Erreur lors du vidage du panier: ${response.status}`);
            }
            
            showMessage('Votre panier a été vidé', 'info');
            fetchUserPanier();
            
        } catch (error) {
            console.error('Erreur lors du vidage du panier:', error);
            showMessage("Erreur lors du vidage du panier", "error");
        } finally {
            toggleLoader(false);
        }
    };
    
    const createReservation = async (outils) => {
        toggleLoader(true);
        
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
            
            const dateDebut = document.getElementById('date-debut').value;
            const dateFin = document.getElementById('date-fin').value;
            const start = new Date(dateDebut);
            const end = new Date(dateFin);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            const outilsTotal = outils.reduce((total, outil) => {
                return total + (parseFloat(outil.montant || outil.prix) || 0);
            }, 0);
            
            const totalAmount = outilsTotal * diffDays;
            
            const outilIds = outils.map(outil => {
                return outil.id || outil.outilID || outil.outil_id;
            });
            
            const reservation = {
                datedebut: `${dateDebut} 10:00:00`,
                datefin: `${dateFin} 18:00:00`,
                montanttotal: totalAmount,
                statut: "pending",
                outils: outilIds.join(',')
            };
            
            console.log("Reservation payload:", reservation);
            
            // Créer la réservation
            const response = await fetch(`${API_BASE_URL}/users/${userId}/reservations`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(reservation)
            });
            
            if (!response.ok) {
                throw new Error(`Erreur lors de la création de la réservation: ${response.status}`);
            }
            
            const reservationResult = await response.json();
            
            await clearPanier();
            
            showMessage("Réservation confirmée avec succès !", "success");
            
            setTimeout(() => {
                window.location.href = 'reservations.html';
            }, 1500);
            
        } catch (error) {
            console.error('Erreur lors de la création de la réservation:', error);
            showMessage("Erreur lors de la création de la réservation", "error");
        } finally {
            toggleLoader(false);
        }
    };
    
    const logoutBtn = document.querySelector("#logoutbtn");
    if (logoutBtn) {
        logoutBtn.onclick = () => {
            localStorage.removeItem('access_token');
            localStorage.removeItem('refresh_token');
            localStorage.removeItem('user');
            window.location.href = 'auth.html';
        };
    }
    
    fetchUserPanier();
});