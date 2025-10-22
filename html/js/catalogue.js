document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("calendrierModal");
    const outilTitre = document.getElementById("outilTitre");
    const closeBtn = document.querySelector(".close");
    const confirmerBtn = document.getElementById("confirmerBtn");

    let outilSelectionne = null;

    const showLoader = () => {
        const loader = document.getElementById('loader');
        loader.style.display = 'block';
    }

    const hideLoader = () => {
        const loader = document.getElementById('loader');
        if (loader) loader.style.display = 'none';
    }

    const fetchOutils = async () => {
        showLoader();
        try {
            const response = await fetch('http://localhost:6080/api/outils');
            if (!response.ok) {
                throw new Error("Erreur dans la récupération des outils.");
            }
            const outils = await response.json();
            displayOutils(outils);
        } catch (error) {
            console.error('Erreur lors de la récupération des outils:', error);
            alert("Une erreur est survenue lors du chargement des outils.");
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
              <img src="images/${outil.image}" alt="${outil.nom}">
              <a style='text-decoration:none;' href="detailOutil.html?id=${outil.id}"><h2>${outil.nom}</h2></a>
              <p class="stock">Exemplaires disponibles : ${outil.nombreExemplaires}</p>
              <button class="btn-reserver" data-id="${outil.id}">Réserver</button>
            `;

            catalogueSection.appendChild(article);
        });

        document.querySelectorAll('.btn-reserver').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.getAttribute('data-id');
                fetchOutilDetails(id); 
            });
        });
    };

    const fetchOutilDetails = async (id) => {
        try {
            const response = await fetch(`http://localhost:6080/api/outils/${id}`);
            if (!response.ok) {
                throw new Error("Erreur dans la récupération des détails de l'outil.");
            }
            const outil = await response.json();
            showModal(outil); 
        } catch (error) {
            console.error('Erreur lors de la récupération des détails de l\'outil:', error);
            alert("Une erreur est survenue lors du chargement des détails de l'outil.");
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



    closeBtn.onclick = () => (modal.style.display = "none");
    window.onclick = (e) => { if (e.target === modal) modal.style.display = "none"; };

    confirmerBtn.onclick = () => {
        const date = fp.input.value;
        if (!date) return alert("Choisis une date d’abord 😅");

        const reservation = {
            id: outilSelectionne.id,
            nom: outilSelectionne.nom,
            image: outilSelectionne.image,
            prix: outilSelectionne.montant,
            date: date,
            statut: "En attente ⏳",
        };

        let panier = JSON.parse(localStorage.getItem("panier")) || [];
        panier.push(reservation);
        localStorage.setItem("panier", JSON.stringify(panier));


        modal.style.display = "none";
        fp.clear();

        setTimeout(() => {
            window.location.href = "panier.html";
        }, 500);
    };

    fetchOutils(); 
});
