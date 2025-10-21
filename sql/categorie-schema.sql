-- schema_categorie.sql
CREATE TABLE IF NOT EXISTS categorie (
    id UUID PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT
);
