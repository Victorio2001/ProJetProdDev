ALTER TABLE IF EXISTS livres
    ADD COLUMN IF NOT EXISTS archived BOOLEAN DEFAULT FALSE;

SELECT create_constraint_if_not_exists(
               'livres',
               'ct_non_neg',
               'ALTER TABLE IF EXISTS livres ADD CONSTRAINT ct_non_neg CHECK (nombre_exemplaires >= 0)'
       );

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

CREATE OR REPLACE FUNCTION refuse_book_logical_deletion()
    RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.archived = TRUE AND NEW.nombre_exemplaires > 0 THEN
        RAISE EXCEPTION 'La suppression d''un livre avec des exemplaires en stock est interdite';
    END IF;
    RETURN NEW;
END
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS refuse_logical_deletion ON utilisateurs;

CREATE OR REPLACE TRIGGER refuse_logical_deletion
    BEFORE UPDATE OF archived
    ON livres
    FOR EACH ROW
EXECUTE FUNCTION refuse_book_logical_deletion();

