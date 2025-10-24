document.addEventListener("DOMContentLoaded", () => {
    const API_BASE_URL = 'http://localhost:24789/api';
    const reservationsContainer = document.getElementById("reservations-container");
    
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
    
    const formatDate = (dateStr) => {
        if (!dateStr) return 'N/A';
        
        const date = new Date(dateStr);
        
        if (isNaN(date.getTime())) {
            return dateStr;
        }
        
        return date.toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };
    
    const getStatusLabel = (status) => {
        switch(status.toLowerCase()) {
            case 'pending':
                return 'En attente';
            case 'confirmed':
                return 'Confirmée';
            case 'cancelled':
                return 'Annulée';
            case 'completed':
                return 'Terminée';
            default:
                return status;
        }
    };
    
    const fetchUserReservations = async () => {
        const user = JSON.parse(localStorage.getItem('user'));
        const userId = user && user.id ? user.id : 'a1a1a1a1-a1a1-a1a1-a1a1-a1a1a1a1a1a1';
        
        toggleLoader(true);
        
        try {
            const token = localStorage.getItem('access_token');
            const headers = {};
            
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
            
            const response = await fetch(`${API_BASE_URL}/users/${userId}/reservations`, {
                headers: headers
            });
            
            if (!response.ok) {
                throw new Error(`Erreur ${response.status}`);
            }
            
            const reservations = await response.json();
            
            if (!reservations || reservations.length === 0) {
                displayEmptyReservations();
                return;
            }
            
            displayReservations(reservations);
            
        } catch (error) {
            console.error('Erreur lors de la récupération des réservations:', error);
            reservationsContainer.innerHTML = `
                <div id="empty-reservations">
                    <p>Une erreur est survenue lors du chargement de vos réservations.</p>
                    <button onclick="window.location.href='catalogue.html'">
                        Retour au catalogue
                    </button>
                </div>
            `;
        } finally {
            toggleLoader(false);
        }
    };
    
    const displayEmptyReservations = () => {
        reservationsContainer.innerHTML = `
            <div id="empty-reservations">
                <img src="images/empty-reservations.svg" alt="Aucune réservation" onerror="this.style.display='none'">
                <h2>Vous n'avez pas encore de réservations</h2>
                <p>Parcourez notre catalogue d'outils et commencez à réserver dès maintenant pour votre prochain projet.</p>
                <button onclick="window.location.href='catalogue.html'">
                    Explorer le catalogue
                </button>
            </div>
        `;
    };
    
    const displayReservations = (reservations) => {
        reservationsContainer.innerHTML = '';
        
        for (const reservation of reservations) {
            const reservationCard = document.createElement('div');
            reservationCard.className = 'reservation-card';
            
            const statusClass = `status-${reservation.statut.toLowerCase()}`;
            
            const outilsHtml = generateOutilsHtml(reservation.outils);
            
            reservationCard.innerHTML = `
                <div class="reservation-header">
                    <h2>Réservation du ${formatDate(reservation.datedebut)}</h2>
                    <span class="reservation-status ${statusClass}">${getStatusLabel(reservation.statut)}</span>
                </div>
                <div class="reservation-details">
                    <div class="detail-item">
                        <p class="label">Code de réservation</p>
                        <p class="value">${reservation.id}</p>
                    </div>
                    <div class="detail-item">
                        <p class="label">Dates de réservation</p>
                        <p class="value">Du ${formatDate(reservation.datedebut)} au ${formatDate(reservation.datefin)}</p>
                    </div>
                    <div class="detail-item">
                        <p class="label">Statut</p>
                        <p class="value">${getStatusLabel(reservation.statut)}</p>
                    </div>
                    <div class="detail-item">
                        <p class="label">Montant total</p>
                        <p class="value montant">${parseFloat(reservation.montanttotal).toFixed(2)} €</p>
                    </div>
                </div>
                <div class="outils-list">
                    <h3>Outils réservés</h3>
                    <div class="outil-items">
                        ${outilsHtml}
                    </div>
                </div>
            `;
            
            reservationsContainer.appendChild(reservationCard);
        }
        
        document.querySelectorAll('.btn-cancel').forEach(button => {
            button.addEventListener('click', (e) => {
                const reservationId = e.target.getAttribute('data-id');
                if (confirm("Êtes-vous sûr de vouloir annuler cette réservation ?")) {
                    cancelReservation(reservationId);
                }
            });
        });
        
        document.querySelectorAll('.btn-duplicate').forEach(button => {
            button.addEventListener('click', (e) => {
                const reservationId = e.target.getAttribute('data-id');
                duplicateReservation(reservationId);
            });
        });
    };
    
    const generateOutilsHtml = (outils) => {
        if (!outils || outils.length === 0) {
            return '<p>Aucun outil dans cette réservation</p>';
        }
        
        let outilsHtml = '';
        
        for (const outil of outils) {
            if (!outil || typeof outil !== 'object') {
                outilsHtml += `
                    <div class="outil-item">
                        <div class="outil-info">
                            <h4>Outil non disponible</h4>
                            <p>Informations non disponibles</p>
                        </div>
                    </div>
                `;
                continue;
            }
            
            const imageUrl = `images/${outil.image}`;
            
            outilsHtml += `
                <div class="outil-item">
                    <div class="outil-image">
                        <img src="${imageUrl}" alt="${outil.nom}">
                    </div>
                    <div class="outil-info">
                        <h4>${outil.nom}</h4>
                        <p>${outil.categorie && outil.categorie.nom ? outil.categorie.nom : 'Catégorie non spécifiée'}</p>
                        <p class="price">${parseFloat(outil.montant).toFixed(2)} € / jour</p>
                    </div>
                </div>
            `;
        }
        
        return outilsHtml;
    };
    
    const cancelReservation = async (reservationId) => {
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
            
            const response = await fetch(`${API_BASE_URL}/users/${userId}/reservations/${reservationId}`, {
                method: 'PUT',
                headers: headers,
                body: JSON.stringify({
                    statut: 'cancelled'
                })
            });
            
            if (!response.ok) {
                throw new Error(`Erreur lors de l'annulation: ${response.status}`);
            }
            
            showMessage("Réservation annulée avec succès", "success");
            fetchUserReservations();
            
        } catch (error) {
            console.error('Erreur lors de l\'annulation de la réservation:', error);
            showMessage("Erreur lors de l'annulation de la réservation", "error");
        } finally {
            toggleLoader(false);
        }
    };
    
    const duplicateReservation = async (reservationId) => {
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
            
            const reservationResponse = await fetch(`${API_BASE_URL}/reservations/${reservationId}`, {
                headers: headers
            });
            
            if (!reservationResponse.ok) {
                throw new Error(`Erreur lors de la récupération des détails: ${reservationResponse.status}`);
            }
            
            const reservationDetails = await reservationResponse.json();
            
            const outilsIds = reservationDetails.outils.map(outil => outil.id);
            
            const currentDate = new Date();
            const futureDate = new Date();
            futureDate.setDate(currentDate.getDate() + 7);
            
            const newReservation = {
                datedebut: currentDate.toISOString().split('T')[0] + ' 10:00:00',
                datefin: futureDate.toISOString().split('T')[0] + ' 18:00:00',
                montanttotal: reservationDetails.montanttotal,
                statut: 'pending',
                outils: outilsIds.join(',')
            };
            
            const createResponse = await fetch(`${API_BASE_URL}/users/${userId}/reservations`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(newReservation)
            });
            
            if (!createResponse.ok) {
                throw new Error(`Erreur lors de la création: ${createResponse.status}`);
            }
            
            showMessage("Réservation dupliquée avec succès", "success");
            fetchUserReservations();
            
        } catch (error) {
            console.error('Erreur lors de la duplication de la réservation:', error);
            showMessage("Erreur lors de la duplication de la réservation", "error");
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
    
    fetchUserReservations();
});