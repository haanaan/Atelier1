-- Adminer 5.3.0 PostgreSQL 17.6 dump

DROP TABLE IF EXISTS "categorie";
CREATE TABLE "public"."categorie" (
    "id" uuid NOT NULL,
    "nom" character varying(255) NOT NULL,
    "description" text,
    CONSTRAINT "categorie_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "categorie" ("id", "nom", "description") VALUES
('11111111-1111-1111-1111-111111111111',	'Petit outillage',	'Petit outillage pour diverses réparations et travaux manuels dans la maison et l atelier'),
('22222222-2222-2222-2222-222222222222',	'Menuiserie et charpente',	'Outils de menuiserie et de charpente pour travailler le bois et effectuer des constructions légères'),
('33333333-3333-3333-3333-333333333333',	'Peinture & décoration',	'Outils et accessoires pour les travaux de peinture et la décoration intérieure et extérieure'),
('44444444-4444-4444-4444-444444444444',	'Nettoyage et entretien',	'Outils pour le nettoyage et l entretien domestique, tels que les balais, brosses, et nettoyeurs'),
('55555555-5555-5555-5555-555555555555',	'Jardinage & espaces verts',	'Outils et équipements pour le jardinage, le paysagisme et l entretien des espaces extérieurs'),
('66666666-6666-6666-6666-666666666666',	'Carrelage et revêtement de sol',	'Outils pour poser des carreaux et effectuer des travaux de revêtement de sol dans les maisons et locaux');

DROP TABLE IF EXISTS "outils";
CREATE TABLE "public"."outils" (
    "id" uuid NOT NULL,
    "nom" character varying(255) NOT NULL,
    "description" text,
    "montant" numeric(10,2) NOT NULL,
    "image" character varying(255),
    "exemplaires" integer,
    "categorie_id" uuid,
    CONSTRAINT "outils_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "outils" ("id", "nom", "description", "montant", "image", "exemplaires", "categorie_id") VALUES
('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',	'Marteau',	'Marteau en acier avec un manche en bois pour travaux de réparation',	15.00,	'im_4a6_Marteau_image.jpg',	1,	'11111111-1111-1111-1111-111111111111'),
('bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb',	'Tournevis',	'Tournevis avec embouts interchangeables pour vissage et dévissage',	10.00,	'im_4a6_Tournevis_image.jpg',	1,	'11111111-1111-1111-1111-111111111111'),
('cccccccc-cccc-cccc-cccc-cccccccccccc',	'Scie à main',	'Scie à main avec une lame en acier pour couper du bois',	25.00,	'im_4a6_Scie_main_image.jpg',	1,	'22222222-2222-2222-2222-222222222222'),
('dddddddd-dddd-dddd-dddd-dddddddddddd',	'Perceuse électrique',	'Perceuse sans fil avec plusieurs vitesses et perceuses',	150.00,	'im_4a6_Perceuse_image.jpg',	1,	'22222222-2222-2222-2222-222222222222'),
('eeeeeeee-eeee-eeee-eeee-eeeeeeeeeeee',	'Pince coupante',	'Pince coupante en acier inoxydable pour couper des fils',	12.00,	'im_4a6_Pince_coupante_image.jpg',	1,	'11111111-1111-1111-1111-111111111111'),
('ffffffff-ffff-ffff-ffff-ffffffffffff',	'Pince multiprise',	'Pince multiprise en acier pour saisir des objets de différentes tailles',	18.00,	'im_4a6_Pince_multiprise_image.jpg',	1,	'11111111-1111-1111-1111-111111111111');

DROP TABLE IF EXISTS "panier";
CREATE TABLE "public"."panier" (
    "id" uuid NOT NULL,
    "id_utilisateur" uuid NOT NULL,
    CONSTRAINT "panier_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "panier" ("id", "id_utilisateur") VALUES
('d4d4d4d4-d4d4-d4d4-d4d4-d4d4d4d4d4d4',	'a1a1a1a1-a1a1-a1a1-a1a1-a1a1a1a1a1a1'),
('e5e5e5e5-e5e5-e5e5-e5e5-e5e5e5e5e5e5',	'b2b2b2b2-b2b2-b2b2-b2b2-b2b2b2b2b2b2'),
('f6f6f6f6-f6f6-f6f6-f6f6-f6f6f6f6f6f6',	'c3c3c3c3-c3c3-c3c3-c3c3-c3c3c3c3c3c3');

DROP TABLE IF EXISTS "panier_outils";
CREATE TABLE "public"."panier_outils" (
    "panier_id" uuid NOT NULL,
    "outil_id" uuid NOT NULL
)
WITH (oids = false);

INSERT INTO "panier_outils" ("panier_id", "outil_id") VALUES
('d4d4d4d4-d4d4-d4d4-d4d4-d4d4d4d4d4d4',	'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa'),
('d4d4d4d4-d4d4-d4d4-d4d4-d4d4d4d4d4d4',	'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb'),
('e5e5e5e5-e5e5-e5e5-e5e5-e5e5e5e5e5e5',	'cccccccc-cccc-cccc-cccc-cccccccccccc');

DROP TABLE IF EXISTS "reservation";
CREATE TABLE "public"."reservation" (
    "id" uuid NOT NULL,
    "utilisateur_id" uuid NOT NULL,
    "datedebut" timestamp NOT NULL,
    "datefin" timestamp NOT NULL,
    "montanttotal" numeric(10,2) NOT NULL,
    "statut" character varying(50) NOT NULL,
    CONSTRAINT "reservation_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "reservation" ("id", "utilisateur_id", "datedebut", "datefin", "montanttotal", "statut") VALUES
('a1b2c3d4-e5f6-a1b2-c3d4-e5f6a1b2c3d4',	'a1a1a1a1-a1a1-a1a1-a1a1-a1a1a1a1a1a1',	'2023-01-01 10:00:00',	'2023-01-10 18:00:00',	500.00,	'Confirmed'),
('b2c3d4e5-f6a1-b2c3-d4e5-f6a1b2c3d4e5',	'b2b2b2b2-b2b2-b2b2-b2b2-b2b2b2b2b2b2',	'2023-02-01 12:00:00',	'2023-02-15 14:00:00',	450.00,	'Pending'),
('c3d4e5f6-a1b2-c3d4-e5f6-a1b2c3d4e5f6',	'c3c3c3c3-c3c3-c3c3-c3c3-c3c3c3c3c3c3',	'2023-03-01 09:00:00',	'2023-03-10 17:00:00',	600.00,	'Cancelled');

DROP TABLE IF EXISTS "reservation_outils";
CREATE TABLE "public"."reservation_outils" (
    "reservation_id" uuid NOT NULL,
    "outil_id" uuid NOT NULL
)
WITH (oids = false);

INSERT INTO "reservation_outils" ("reservation_id", "outil_id") VALUES
('a1b2c3d4-e5f6-a1b2-c3d4-e5f6a1b2c3d4',	'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa'),
('a1b2c3d4-e5f6-a1b2-c3d4-e5f6a1b2c3d4',	'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb'),
('b2c3d4e5-f6a1-b2c3-d4e5-f6a1b2c3d4e5',	'cccccccc-cccc-cccc-cccc-cccccccccccc');

DROP TABLE IF EXISTS "utilisateurs";
CREATE TABLE "public"."utilisateurs" (
    "id" uuid NOT NULL,
    "nom" character varying(255) NOT NULL,
    "prenom" character varying(255) NOT NULL,
    "email" character varying(255) NOT NULL,
    "password" character varying(255) NOT NULL,
    CONSTRAINT "utilisateurs_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE UNIQUE INDEX utilisateurs_email_key ON public.utilisateurs USING btree (email);

INSERT INTO "utilisateurs" ("id", "nom", "prenom", "email", "password") VALUES
('a1a1a1a1-a1a1-a1a1-a1a1-a1a1a1a1a1a1',	'Jean',	'Dupont',	'jean.dupont@example.com',	'password123'),
('b2b2b2b2-b2b2-b2b2-b2b2-b2b2b2b2b2b2',	'Marie',	'Lemoine',	'marie.lemoine@example.com',	'password123'),
('c3c3c3c3-c3c3-c3c3-c3c3-c3c3c3c3c3c3',	'Paul',	'Durand',	'paul.durand@example.com',	'password123');
