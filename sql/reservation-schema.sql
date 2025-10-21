-- schema_reservation.sql
CREATE TABLE IF NOT EXISTS reservation (
    id UUID PRIMARY KEY,
    utilisateur_id UUID NOT NULL,
    datedebut TIMESTAMP NOT NULL,
    datefin TIMESTAMP NOT NULL,
    montanttotal DECIMAL(10, 2) NOT NULL,
    statut VARCHAR(50) NOT NULL
);
