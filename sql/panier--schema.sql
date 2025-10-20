-- schema_panier.sql

-- Create the Panier table
CREATE TABLE IF NOT EXISTS panier (
    id SERIAL PRIMARY KEY,  -- Auto-incrementing primary key
    id_utilisateur INT NOT NULL,  -- Foreign key referencing utilisateurs(id)
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)  -- Foreign key constraint
);
