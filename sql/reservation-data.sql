-- data_reservation.sql

-- Insert data into Reservation table
INSERT INTO reservation (utilisateur_id, datedebut, datefin, montanttotal, statut) VALUES
(1, '2023-01-01 10:00:00', '2023-01-10 18:00:00', 500.00, 'Confirmed'),
(2, '2023-02-01 12:00:00', '2023-02-15 14:00:00', 450.00, 'Pending'),
(3, '2023-03-01 09:00:00', '2023-03-10 17:00:00', 600.00, 'Cancelled');
