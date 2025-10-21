-- schema_panier_outils.sql
CREATE TABLE IF NOT EXISTS panier_outils (
    panier_id UUID NOT NULL,
    outil_id UUID NOT NULL
);
