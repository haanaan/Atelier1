-- schema_categorie.sql

-- Create the Categorie table
CREATE TABLE IF NOT EXISTS categorie (
    id SERIAL PRIMARY KEY,  -- Auto-incrementing primary key
    nom VARCHAR(255) NOT NULL,  -- Name of the category
    description TEXT  -- Description of the category
);
