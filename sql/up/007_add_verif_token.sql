CREATE TABLE IF NOT EXISTS verification_tokens
(
    id         SERIAL PRIMARY KEY,
    user_id    INTEGER REFERENCES utilisateurs (id_utilisateur) ON DELETE CASCADE,
    token      TEXT NOT NULL,
    used       BOOLEAN   DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
