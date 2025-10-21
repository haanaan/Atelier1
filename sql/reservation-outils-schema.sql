-- schema_reservation_outils.sql

-- Create the Reservation-Outils relationship table (Many-to-Many)
CREATE TABLE IF NOT EXISTS reservation_outils (
    reservation_id INT NOT NULL,  -- Foreign key referencing reservation(id)
    outil_id INT NOT NULL,  -- Foreign key referencing outils(id)
    FOREIGN KEY (reservation_id) REFERENCES reservation(id),  -- Foreign key constraint to reservation
    FOREIGN KEY (outil_id) REFERENCES outils(id)  -- Foreign key constraint to outils
);
