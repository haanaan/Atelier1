-- data_outils.sql

-- Insert data into Outils table
INSERT INTO outils (nom, description, montant, image, exemplaires, categorie_id) VALUES
('Marteau', 'Marteau en acier avec un manche en bois pour travaux de réparation', 15.00, 'im_4a6_Marteau_image.jpg', 100, 1),
('Tournevis', 'Tournevis avec embouts interchangeables pour vissage et dévissage', 10.00, 'im_4a6_Tournevis_image.jpg', 80, 1),
('Scie à main', 'Scie à main avec une lame en acier pour couper du bois', 25.00, 'im_4a6_Scie_main_image.jpg', 60, 2),
('Perceuse électrique', 'Perceuse sans fil avec plusieurs vitesses et perceuses', 150.00, 'im_4a6_Perceuse_image.jpg', 40, 2),
('Pince coupante', 'Pince coupante en acier inoxydable pour couper des fils', 12.00, 'im_4a6_Pince_coupante_image.jpg', 50, 1),
('Pince multiprise', 'Pince multiprise en acier pour saisir des objets de différentes tailles', 18.00, 'im_4a6_Pince_multiprise_image.jpg', 70, 1);
