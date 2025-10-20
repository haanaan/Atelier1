-- schema_utilisateurs.sql

-- Create the Utilisateurs table
CREATE TABLE IF NOT EXISTS utilisateurs (
    id SERIAL PRIMARY KEY,  -- Auto-incrementing primary key
    nom VARCHAR(255) NOT NULL,  -- Last name of the user
    prenom VARCHAR(255) NOT NULL,  -- First name of the user
    email VARCHAR(255) UNIQUE NOT NULL,  -- User's email, must be unique
    password VARCHAR(255) NOT NULL  -- User's password (stored as plain text here, but ideally hashed)
);
