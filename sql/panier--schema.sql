-- schema_panier.sql
CREATE TABLE IF NOT EXISTS panier (
    id UUID PRIMARY KEY,
    id_utilisateur UUID NOT NULL
);