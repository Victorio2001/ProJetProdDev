ALTER TABLE IF EXISTS utilisateur_livres_emprunter
    DROP CONSTRAINT IF EXISTS check_date_emprunt;

ALTER TABLE IF EXISTS utilisateur_livres_emprunter
    DROP CONSTRAINT IF EXISTS utilisateur_livres_emprunter_pkey;

ALTER TABLE IF EXISTS utilisateur_livres_emprunter
    ADD CONSTRAINT utilisateur_livres_emprunter_pkey PRIMARY KEY (id_utilisateur, id_livre, date_emprunt);


ALTER TABLE IF EXISTS utilisateur_livres_emprunter
    ADD COLUMN IF NOT EXISTS validated BOOLEAN DEFAULT FALSE;

ALTER TABLE IF EXISTS utilisateur_livres_emprunter
    ADD COLUMN IF NOT EXISTS quantite INTEGER NOT NULL DEFAULT 1;

ALTER TABLE IF EXISTS utilisateur_livres_emprunter
    DROP CONSTRAINT IF EXISTS utilisateur_livres_quantite_check;

ALTER TABLE IF EXISTS utilisateur_livres_emprunter
    ADD CONSTRAINT utilisateur_livres_quantite_check CHECK (quantite >= 1);

ALTER TABLE IF EXISTS livres
    ADD CONSTRAINT chk_isbn_unique UNIQUE (isbn);
