CREATE DATABASE BuyMatch
USE BuyMatch

CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_role VARCHAR(50) NOT NULL
);

CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    actif BOOLEAN NOT NULL DEFAULT 1,
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role(id)
);

CREATE TABLE match_sportif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipeA_nom VARCHAR(100) NOT NULL,
    equipeA_logo VARCHAR(255),
    equipeB_nom VARCHAR(100) NOT NULL,
    equipeB_logo VARCHAR(255),
    date_heure DATETIME NOT NULL,
    lieu VARCHAR(100) NOT NULL,
    duree INT NOT NULL DEFAULT 90,
    statut ENUM('en_attente', 'valide', 'refuse') DEFAULT 'en_attente',
    organisateur_id INT NOT NULL,
    FOREIGN KEY (organisateur_id) REFERENCES utilisateur(id)
);


CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    nb_places INT NOT NULL,
    match_id INT NOT NULL,
    FOREIGN KEY (match_id) REFERENCES match_sportif(id)
);

CREATE TABLE billet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_place VARCHAR(50) NOT NULL,
    qr_code VARCHAR(255) NOT NULL,
    match_id INT NOT NULL,
    categorie_id INT NOT NULL,
    acheteur_id INT NOT NULL,
    FOREIGN KEY (match_id) REFERENCES match_sportif(id),
    FOREIGN KEY (categorie_id) REFERENCES categorie(id),
    FOREIGN KEY (acheteur_id) REFERENCES utilisateur(id)
);


CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    match_id INT NOT NULL,
    acheteur_id INT NOT NULL,
    FOREIGN KEY (match_id) REFERENCES match_sportif(id),
    FOREIGN KEY (acheteur_id) REFERENCES utilisateur(id)
);

INSERT INTO role (nom_role) VALUES ('Acheteur'), ('Organisateur'), ('Administrateur');
