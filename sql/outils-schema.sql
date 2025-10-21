-- schema_outils.sql
CREATE TABLE IF NOT EXISTS outils (
    id UUID PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    montant DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    exemplaires INT,
    categorie_id UUID
);
