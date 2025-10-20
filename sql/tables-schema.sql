-- Categorie table
CREATE TABLE IF NOT EXISTS categorie (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT
);

-- Outils table
CREATE TABLE IF NOT EXISTS outils (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    montant DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    exemplaires INT,
    categorie_id INT,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id)
);

-- Utilisateurs table
CREATE TABLE IF NOT EXISTS utilisateurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Panier table
CREATE TABLE IF NOT EXISTS panier (
    id SERIAL PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
);

-- Panier-Outils 
CREATE TABLE IF NOT EXISTS panier_outils (
    panier_id INT NOT NULL,
    outil_id INT NOT NULL,
    FOREIGN KEY (panier_id) REFERENCES panier(id),
    FOREIGN KEY (outil_id) REFERENCES outils(id)
);

-- Reservation table
CREATE TABLE IF NOT EXISTS reservation (
    id SERIAL PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    datedebut DATE NOT NULL,
    datefin DATE NOT NULL,
    montanttotal DECIMAL(10, 2) NOT NULL,
    statut VARCHAR(50) NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

-- Reservation-Outils
CREATE TABLE IF NOT EXISTS reservation_outils (
    reservation_id INT NOT NULL,
    outil_id INT NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservation(id),
    FOREIGN KEY (outil_id) REFERENCES outils(id)
);
