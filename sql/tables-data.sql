-- data.sql

-- Insert data into Categorie table (using the categories from the image)
INSERT INTO categorie (nom, description) VALUES
('Petit outillage', 'Petit outillage pour diverses réparations et travaux manuels dans la maison'),
('Menuiserie et charpente', 'Outils de menuiserie et de charpente pour travailler le bois et effectuer des constructions légères'),
('Peinture & décoration', 'Outils et accessoires pour les travaux de peinture et la décoration intérieure et extérieure'),
('Nettoyage et entretien', 'Outils pour le nettoyage et l entretien domestique, tels que les balais, brosses, et nettoyeurs'),
('Jardinage & espaces verts', 'Outils et équipements pour le jardinage, le paysagisme et l entretien des espaces extérieurs'),
('Carrelage et revêtement de sol', 'Outils pour poser des carreaux et effectuer des travaux de revêtement de sol dans les maisons et locaux');

-- Insert data into Outils table (realistic tool entries)
INSERT INTO outils (nom, description, montant, image, exemplaires, categorie_id) VALUES
('Marteau', 'Marteau en acier avec un manche en bois pour travaux de réparation', 15.00, 'im_4a6_Marteau_image.jpg', 100, 1),
('Tournevis', 'Tournevis avec embouts interchangeables pour vissage et dévissage', 10.00, 'im_4a6_Tournevis_image.jpg', 80, 1),
('Scie à main', 'Scie à main avec une lame en acier pour couper du bois', 25.00, 'im_4a6_Scie_main_image.jpg', 60, 2);

-- Insert data into Utilisateurs table
INSERT INTO utilisateurs (nom, prenom, email, password) VALUES
('Jean', 'Dupont', 'jean.dupont@example.com', 'password123'),
('Marie', 'Lemoine', 'marie.lemoine@example.com', 'password123'),
('Paul', 'Durand', 'paul.durand@example.com', 'password123');

-- Insert data into Panier table (with utilisateur ids)
INSERT INTO panier (id_utilisateur) VALUES
(1), (2), (3);

-- Insert data into Panier-Outils table (to relate Panier and Outils)
INSERT INTO panier_outils (panier_id, outil_id) VALUES
(1, 1), (1, 2), (2, 3);

-- Insert data into Reservation table
INSERT INTO reservation (utilisateur_id, datedebut, datefin, montanttotal, statut) VALUES
(1, '2023-01-01', '2023-01-10', 500.00, 'Confirmed'),
(2, '2023-02-01', '2023-02-15', 450.00, 'Pending'),
(3, '2023-03-01', '2023-03-10', 600.00, 'Cancelled');

-- Insert data into Reservation-Outils table (to relate Reservation and Outils)
INSERT INTO reservation_outils (reservation_id, outil_id) VALUES
(1, 1), (1, 2), (2, 3);
