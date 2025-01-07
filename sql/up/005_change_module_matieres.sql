DROP TABLE IF EXISTS module_matieres;
DROP TABLE IF EXISTS modules;
DROP TABLE IF EXISTS matieres;


CREATE TABLE IF NOT EXISTS modules
(
    id_module  SERIAL PRIMARY KEY,
    nom_module VARCHAR(255) NOT NULL
);

CREATE TABLE utilisateurs_modules
(
    id        SERIAL PRIMARY KEY,
    user_id   INT NOT NULL,
    module_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES utilisateurs (id_utilisateur),
    FOREIGN KEY (module_id) REFERENCES modules (id_module)
);

CREATE TABLE IF NOT EXISTS utilisateurs_promotions
(
    id           SERIAL PRIMARY KEY,
    user_id      INT NOT NULL,
    promotion_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES utilisateurs (id_utilisateur),
    FOREIGN KEY (promotion_id) REFERENCES promotions (id_promotion)
);

-- Insertion des modules
INSERT INTO modules (nom_module)
VALUES ('Programmation avancée'),
       ('Les types de commandes SQL');

CREATE OR REPLACE FUNCTION update_utilisateurs_promotions()
    RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.id_promotion <> OLD.id_promotion THEN
        INSERT INTO utilisateurs_promotions (user_id, promotion_id) VALUES (NEW.id_utilisateur, OLD.id_promotion);
    END IF;
    RETURN NEW;
END;
$$
    LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS utilisateurs_promotions_trigger ON utilisateurs;

CREATE TRIGGER utilisateurs_promotions_trigger
    BEFORE UPDATE
    ON utilisateurs
    FOR EACH ROW
EXECUTE PROCEDURE update_utilisateurs_promotions();

