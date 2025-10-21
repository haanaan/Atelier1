-- schema_panier_outils.sql

-- Create the Panier-Outils relationship table (Many-to-Many)
CREATE TABLE IF NOT EXISTS panier_outils (
    panier_id INT NOT NULL,  -- Foreign key referencing panier(id)
    outil_id INT NOT NULL,  -- Foreign key referencing outils(id)
    FOREIGN KEY (panier_id) REFERENCES panier(id),  -- Foreign key constraint to panier
    FOREIGN KEY (outil_id) REFERENCES outils(id)  -- Foreign key constraint to outils
);
