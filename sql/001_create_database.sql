CREATE TABLE IF NOT EXISTS etat_reservation
(
    id_etat_reservation  SERIAL,
    nom_etat_reservation VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_etat_reservation),
    UNIQUE (nom_etat_reservation)
);

CREATE TABLE IF NOT EXISTS roles
(
    id_role  SERIAL,
    nom_role VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_role),
    UNIQUE (nom_role)
);

CREATE TABLE IF NOT EXISTS mots_cles
(
    id_mot_cle SERIAL,
    mot_cle    VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_mot_cle),
    UNIQUE (mot_cle)
);

CREATE TABLE IF NOT EXISTS auteurs
(
    id_auteur     SERIAL,
    nom_auteur    VARCHAR(255) NOT NULL,
    prenom_auteur VARCHAR(255),
    PRIMARY KEY (id_auteur)
);

CREATE TABLE IF NOT EXISTS editeurs
(
    id_editeur  SERIAL,
    nom_editeur VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_editeur),
    UNIQUE (nom_editeur)
);

CREATE TABLE IF NOT EXISTS modules
(
    id_module  SERIAL,
    nom_module VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_module),
    UNIQUE (nom_module)
);

CREATE TABLE IF NOT EXISTS matieres
(
    id_matiere  SERIAL,
    nom_matiere VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_matiere),
    UNIQUE (nom_matiere)
);

CREATE TABLE IF NOT EXISTS promotions
(
    id_promotion  SERIAL,
    nom_promotion VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_promotion),
    UNIQUE (nom_promotion)
);

CREATE TABLE IF NOT EXISTS livres
(
    id_livre           SERIAL,
    titre_livre        VARCHAR(255) NOT NULL,
    resume_livre       TEXT         NOT NULL,
    isbn               VARCHAR(14)  NOT NULL,
    annee_publication  INTEGER      NOT NULL,
    image_couverture   TEXT         NOT NULL,
    nombre_exemplaires INTEGER      NOT NULL,
    id_editeur         INTEGER      NOT NULL,
    created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_livre),
    FOREIGN KEY (id_editeur) REFERENCES editeurs (id_editeur)
);

CREATE TABLE IF NOT EXISTS utilisateurs
(
    id_utilisateur     SERIAL,
    nom_utilisateur    VARCHAR(255) NOT NULL,
    prenom_utilisateur VARCHAR(255) NOT NULL,
    email_utilisateur  VARCHAR(255) NOT NULL,
    mdp                VARCHAR(255),
    lecture_seule      BOOLEAN,
    id_promotion       INTEGER      NOT NULL,
    id_role            INTEGER      NOT NULL,
    PRIMARY KEY (id_utilisateur),
    UNIQUE (email_utilisateur),
    FOREIGN KEY (id_promotion) REFERENCES promotions (id_promotion),
    FOREIGN KEY (id_role) REFERENCES roles (id_role)
);

CREATE TABLE IF NOT EXISTS transactions
(
    id_transaction   SERIAL,
    date_transaction DATE    NOT NULL,
    nb_ex_ajoute     INTEGER,
    nb_ex_retire     INTEGER NOT NULL,
    id_utilisateur   INTEGER NOT NULL,
    id_livre         INTEGER NOT NULL,
    PRIMARY KEY (id_transaction),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id_utilisateur),
    FOREIGN KEY (id_livre) REFERENCES livres (id_livre)
);

CREATE TABLE IF NOT EXISTS utilisateur_livres_emprunter
(
    id_livre           INTEGER,
    id_utilisateur     INTEGER,
    date_emprunt       DATE,
    date_retour_reel   DATE    DEFAULT NULL,
    date_retour_limite DATE    DEFAULT NULL,
    validated          BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (id_livre, id_utilisateur),
    FOREIGN KEY (id_livre) REFERENCES livres (id_livre),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id_utilisateur)
);

CREATE TABLE IF NOT EXISTS utilisateur_livres_reserver
(
    id_livre            INTEGER,
    id_utilisateur      INTEGER,
    id_etat_reservation INTEGER,
    date_reservation    DATE NOT NULL,
    PRIMARY KEY (id_livre, id_utilisateur, id_etat_reservation),
    FOREIGN KEY (id_livre) REFERENCES livres (id_livre),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id_utilisateur),
    FOREIGN KEY (id_etat_reservation) REFERENCES etat_reservation (id_etat_reservation)
);

CREATE TABLE IF NOT EXISTS livre_mots_cles
(
    id_livre   INTEGER,
    id_mot_cle INTEGER,
    PRIMARY KEY (id_livre, id_mot_cle),
    FOREIGN KEY (id_livre) REFERENCES livres (id_livre),
    FOREIGN KEY (id_mot_cle) REFERENCES mots_cles (id_mot_cle)
);

CREATE TABLE IF NOT EXISTS livre_auteurs
(
    id_livre  INTEGER,
    id_auteur INTEGER,
    PRIMARY KEY (id_livre, id_auteur),
    FOREIGN KEY (id_livre) REFERENCES livres (id_livre),
    FOREIGN KEY (id_auteur) REFERENCES auteurs (id_auteur)
);

CREATE TABLE IF NOT EXISTS module_matieres
(
    id_utilisateur INTEGER,
    id_module      INTEGER,
    id_matiere     INTEGER,
    PRIMARY KEY (id_utilisateur, id_module, id_matiere),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id_utilisateur),
    FOREIGN KEY (id_module) REFERENCES modules (id_module),
    FOREIGN KEY (id_matiere) REFERENCES matieres (id_matiere)
);


-- Constraints for tables
create or replace function create_constraint_if_not_exists(
    t_name text, c_name text, constraint_sql text
)
    returns void AS
$$
begin
    -- Look for our constraint
    if not exists (select constraint_name
                   from information_schema.constraint_column_usage
                   where table_name = t_name
                     and constraint_name = c_name) then
        execute constraint_sql;
    end if;
end;
$$ language 'plpgsql';

SELECT create_constraint_if_not_exists(
               'etat_reservation',
               'chk_etat_reservation_nom',
               'ALTER TABLE etat_reservation ADD CONSTRAINT chk_etat_reservation_nom CHECK (LENGTH(nom_etat_reservation) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'roles',
               'chk_roles_nom',
               'ALTER TABLE roles ADD CONSTRAINT chk_roles_nom CHECK (LENGTH(nom_role) > 0)'
       );


SELECT create_constraint_if_not_exists(
               'mots_cles',
               'chk_mot_cle_not_empty',
               'ALTER TABLE mots_cles ADD CONSTRAINT chk_mot_cle_not_empty CHECK (LENGTH(mot_cle) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'auteurs',
               'chk_auteurs_nom',
               'ALTER TABLE auteurs ADD CONSTRAINT chk_auteurs_nom CHECK (LENGTH(nom_auteur) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'editeurs',
               'chk_editeurs_nom',
               'ALTER TABLE editeurs ADD CONSTRAINT chk_editeurs_nom CHECK (LENGTH(nom_editeur) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'modules',
               'chk_modules_nom',
               'ALTER TABLE modules ADD CONSTRAINT chk_modules_nom CHECK (LENGTH(nom_module) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'matieres',
               'chk_matieres_nom',
               'ALTER TABLE matieres ADD CONSTRAINT chk_matieres_nom CHECK (LENGTH(nom_matiere) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'promotions',
               'chk_promotions_nom',
               'ALTER TABLE promotions ADD CONSTRAINT chk_promotions_nom CHECK (LENGTH(nom_promotion) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'livres',
               'chk_livres_titre',
               'ALTER TABLE livres ADD CONSTRAINT chk_livres_titre CHECK (LENGTH(titre_livre) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'livres',
               'chk_livres_resume',
               'ALTER TABLE livres ADD CONSTRAINT chk_livres_resume CHECK (LENGTH(resume_livre) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'livres',
               'chk_livres_isbn',
               'ALTER TABLE livres ADD CONSTRAINT chk_livres_isbn CHECK (LENGTH(isbn) > 0 AND LENGTH(isbn) <= 14)'
       );

SELECT create_constraint_if_not_exists(
               'livres',
               'chk_livres_image',
               'ALTER TABLE livres ADD CONSTRAINT chk_livres_image CHECK (LENGTH(image_couverture) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'utilisateurs',
               'chk_utilisateurs_nom',
               'ALTER TABLE utilisateurs ADD CONSTRAINT chk_utilisateurs_nom CHECK (LENGTH(nom_utilisateur) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'utilisateurs',
               'chk_utilisateurs_prenom',
               'ALTER TABLE utilisateurs ADD CONSTRAINT chk_utilisateurs_prenom CHECK (LENGTH(prenom_utilisateur) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'utilisateurs',
               'chk_utilisateurs_email',
               'ALTER TABLE utilisateurs ADD CONSTRAINT chk_utilisateurs_email CHECK (LENGTH(email_utilisateur) > 0)'
       );

SELECT create_constraint_if_not_exists(
               'utilisateur_livres_emprunter',
               'check_date_emprunt',
               'ALTER TABLE utilisateur_livres_emprunter ADD CONSTRAINT check_date_emprunt CHECK (date_emprunt <= CURRENT_DATE)'
       );

SELECT create_constraint_if_not_exists(
               'utilisateur_livres_emprunter',
               'check_date_retour_reel',
               'ALTER TABLE utilisateur_livres_emprunter ADD CONSTRAINT check_date_retour_reel CHECK (date_retour_reel >= date_emprunt)'
       );

SELECT create_constraint_if_not_exists(
               'utilisateur_livres_emprunter',
               'check_date_retour_limite',
               'ALTER TABLE utilisateur_livres_emprunter ADD CONSTRAINT check_date_retour_limite CHECK (date_retour_limite >= date_emprunt)'
       );


SELECT create_constraint_if_not_exists(
               'utilisateur_livres_reserver',
               'check_date_reservation',
               'ALTER TABLE utilisateur_livres_reserver ADD CONSTRAINT check_date_reservation CHECK (date_reservation <= CURRENT_DATE);'
       );


DROP TRIGGER IF EXISTS update_book_copies_trigger ON transactions;
