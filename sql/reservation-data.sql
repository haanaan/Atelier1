-- data_reservation.sql
INSERT INTO reservation (id, utilisateur_id, datedebut, datefin, montanttotal, statut) VALUES
('a1b2c3d4-e5f6-a1b2-c3d4-e5f6a1b2c3d4', 'a1a1a1a1-a1a1-a1a1-a1a1-a1a1a1a1a1a1', '2023-01-01 10:00:00', '2023-01-10 18:00:00', 500.00, 'Confirmed'),
('b2c3d4e5-f6a1-b2c3-d4e5-f6a1b2c3d4e5', 'b2b2b2b2-b2b2-b2b2-b2b2-b2b2b2b2b2b2', '2023-02-01 12:00:00', '2023-02-15 14:00:00', 450.00, 'Pending'),
('c3d4e5f6-a1b2-c3d4-e5f6-a1b2c3d4e5f6', 'c3c3c3c3-c3c3-c3c3-c3c3-c3c3c3c3c3c3', '2023-03-01 09:00:00', '2023-03-10 17:00:00', 600.00, 'Cancelled');
