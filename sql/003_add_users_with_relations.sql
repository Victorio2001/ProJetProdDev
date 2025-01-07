-- Insertion des modules
INSERT INTO modules (nom_module)
VALUES ('Programmation avancée'),
       ('Les types de commandes SQL')
ON CONFLICT (nom_module) DO NOTHING;

-- Insertion des matières
INSERT INTO matieres (nom_matiere)
VALUES ('Informatique'),
       ('Base de données')
ON CONFLICT (nom_matiere) DO NOTHING;

-- Insertion des promotions
INSERT INTO promotions (nom_promotion)
VALUES ('Promo 2022'),
       ('Promo 2023'),
       ('Promo 2024')
ON CONFLICT (nom_promotion) DO NOTHING;

INSERT INTO roles (nom_role)
VALUES ('Gestionnaire'),
       ('Formateur'),
       ('Étudiant'),
       ('Guest')
ON CONFLICT (nom_role) DO NOTHING;

INSERT INTO utilisateurs (nom_utilisateur, prenom_utilisateur, email_utilisateur, mdp, lecture_seule, id_promotion,
                          id_role)
VALUES ('Gonzalez', 'Anthony', 'a.gonzalez@lyon.ort.asso.fr',
        '$2y$10$e1gpIU.1NBr4g0huqRlnju4oyyYvmFIJdUYsENldsgJIrDXvrR49.', false,
        (SELECT id_promotion FROM promotions WHERE nom_promotion = 'Promo 2022'),
        (SELECT id_role FROM roles WHERE nom_role = 'Gestionnaire')),
       ('Gourlez', 'Benjamin', 'b.gourlez@lyon.ort.asso.fr',
        '$2y$10$5ZgFaanJGmyIHycqSjv.Mu/OxpfB/YO/8nPe4TX7ZMD6BiqsLihE6', false,
        (SELECT id_promotion FROM promotions WHERE nom_promotion = 'Promo 2023'),
        (SELECT id_role FROM roles WHERE nom_role = 'Formateur')),
       ('Lavazais', 'Esteban', 'e.lavazais@lyon.ort.asso.fr',
        '$2y$10$oiNahtGOmw.dA.spvz/cm.f/7fne5iP.k7iBviSOwWtZstoCACBJK', false,
        (SELECT id_promotion FROM promotions WHERE nom_promotion = 'Promo 2024'),
        (SELECT id_role FROM roles WHERE nom_role = 'Étudiant')),
       ('Jeannin', 'Eliott', 'e.jeannin@lyon.ort.asso.fr',
        '$2y$10$WMZz2teslcis2q/d/mrhueBhGoIka9YB1r/xOmyU1RFJXULQghSzi', false,
        (SELECT id_promotion FROM promotions WHERE nom_promotion = 'Promo 2022'),
        (SELECT id_role FROM roles WHERE nom_role = 'Gestionnaire'))
ON CONFLICT (email_utilisateur) DO NOTHING;

-- Insertion des matières des modules
INSERT INTO module_matieres (id_utilisateur, id_module, id_matiere)
VALUES ((SELECT id_utilisateur FROM utilisateurs WHERE nom_utilisateur = 'Gourlez'),
        (SELECT id_module FROM modules WHERE nom_module = 'Programmation avancée'),
        (SELECT id_matiere FROM matieres WHERE nom_matiere = 'Informatique')),
       ((SELECT id_utilisateur FROM utilisateurs WHERE nom_utilisateur = 'Lavazais'),
        (SELECT id_module FROM modules WHERE nom_module = 'Les types de commandes SQL'),
        (SELECT id_matiere FROM matieres WHERE nom_matiere = 'Base de données'))
ON CONFLICT (id_utilisateur, id_module, id_matiere) DO NOTHING;
