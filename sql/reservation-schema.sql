-- schema_reservation.sql

-- Create the Reservation table
CREATE TABLE IF NOT EXISTS reservation (
    id SERIAL PRIMARY KEY,  -- Auto-incrementing primary key
    utilisateur_id INT NOT NULL,  -- Foreign key referencing utilisateurs(id)
    datedebut TIMESTAMP NOT NULL,  -- Start date and time of the reservation
    datefin TIMESTAMP NOT NULL,  -- End date and time of the reservation
    montanttotal DECIMAL(10, 2) NOT NULL,  -- Total amount for the reservation
    statut VARCHAR(50) NOT NULL,  -- Status of the reservation (e.g., confirmed, pending)
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)  -- Foreign key constraint
);
