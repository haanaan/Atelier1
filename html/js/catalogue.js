document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("calendrierModal");
    const outilTitre = document.getElementById("outilTitre");
    const closeBtn = document.querySelector(".close");
    const confirmerBtn = document.getElementById("confirmerBtn");

    let outilSelectionne = null;

    const showLoader = () => {
        const loader = document.createElement('div');
        loader.classList.add('loader');
        document.body.appendChild(loader); 
    }

    const hideLoader = () => {
        const loader = document.querySelector('.loader');
        if (loader) loader.remove(); 
    }

    const fetchOutils = async () => {
        showLoader();
        try {
            const response = await fetch('http://localhost:6080/api/outils');
            if (!response.ok) {
                throw new Error("Erreur dans la récupération des outils.");
            }
            const outils = await response.json();
            console.log(outils); 
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
              <h2>${outil.nom}</h2>
              <p>${outil.description}</p>
              <p class="prix">${outil.montant} € / heure</p>
              <p class="stock">Exemplaires disponibles : ${outil.nombreExemplaires}</p>
              <button class="btn-reserver" data-outil="${outil.nom}">Réserver</button>
            `;

            catalogueSection.appendChild(article);
        });

        document.querySelectorAll('.btn-reserver').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const article = e.target.closest(".outil");

                outilSelectionne = {
                    nom: article.querySelector("h2").textContent.trim(),
                    image: article.querySelector("img").getAttribute("src"),
                    prix: article.querySelector(".prix").textContent.trim(),
                };

                outilTitre.textContent = "Réserver : " + outilSelectionne.nom;
                modal.style.display = "flex";
            });
        });
    };

    const fp = flatpickr("#datePicker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        locale: flatpickr.l10ns.fr,
        time_24hr: true
    });

    closeBtn.onclick = () => (modal.style.display = "none");
    window.onclick = (e) => { if (e.target === modal) modal.style.display = "none"; };

    confirmerBtn.onclick = () => {
        const date = fp.input.value;
        if (!date) return alert("Choisis une date d’abord 😅");

        const reservation = {
            nom: outilSelectionne.nom,
            image: outilSelectionne.image,
            prix: outilSelectionne.prix,
            date: date,
            statut: "En attente ⏳",
        };

        let panier = JSON.parse(localStorage.getItem("panier")) || [];
        panier.push(reservation);
        localStorage.setItem("panier", JSON.stringify(panier));

        console.log("✅ Panier actuel :", panier);

        alert(`✅ ${outilSelectionne.nom} ajouté au panier !`);

        modal.style.display = "none";
        fp.clear();

        setTimeout(() => {
            window.location.href = "panier.html";
        }, 500);
    };

    fetchOutils();
});
