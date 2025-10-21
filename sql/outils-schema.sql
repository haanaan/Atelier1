-- schema_outils.sql

-- Create the Outils table
CREATE TABLE IF NOT EXISTS outils (
    id SERIAL PRIMARY KEY,  -- Auto-incrementing primary key
    nom VARCHAR(255) NOT NULL,  -- Name of the tool
    description TEXT,  -- Description of the tool
    montant DECIMAL(10, 2) NOT NULL,  -- Price of the tool
    image VARCHAR(255),  -- Image file name
    exemplaires INT,  -- Number of available tools
    categorie_id INT,  -- Foreign key referencing categorie(id)
    FOREIGN KEY (categorie_id) REFERENCES categorie(id)  -- Foreign key constraint
);
