DROP Table IF EXISTS projet;

CREATE TABLE projet (
	Ref_Cindoc varchar(255),
	Serie varchar(255),
	Article integer,
	Discriminant varchar(255),
	Ville text,
	Sujet text,
	Description text,
	Date_prise varchar(255),
	Notes_bas_page text,
	Index_personnes text,
	Fichier_numérique text,
	Index_iconographique text,
	Nb_cliches varchar(255),
	Taille_cliche text,
	Neg_Inv text,
	Couleur_NB text,
	Remarques text
);

\i /Users/benjaminparpaillon/Downloads/parpaillon_fouetillou_pouilloux/rendu3/projetBDD_public_projet.sql -- Modifier avec le chemin vers la base 1FN

CREATE OR REPLACE FUNCTION convertDate(dateText text)
RETURNS date AS $$
  DECLARE
    jour text;
    mois text;
    annee text;
    newText text;
    compDate text;
  BEGIN
    IF dateText IS NULL OR dateText = '' THEN
      RETURN null;
    END IF ;
    jour = '01';
    newText = LOWER(dateText);
    annee = RIGHT(newText, 4);

    IF newText ~ '^(\d{1,2}) [[:alpha:]]* (\d{4})$' THEN
      jour = split_part(newText, ' ', 1);
      mois = split_part(newText, ' ', 2);

    ELSIF newText ~ '[[:alpha:]]* \d{4}' THEN
      mois = split_part(newText, ' ', 1);

    ELSE
      mois = 'janvier';
    END IF ;

    mois = replace(mois, 'é', 'e');

    CASE mois
      WHEN 'janvier' THEN mois='01';
      WHEN 'fevrier' THEN mois='02';
      WHEN 'mars' THEN mois='03';
      WHEN 'avril' THEN mois='04';
      WHEN 'mai' THEN mois='05';
      WHEN 'juin' THEN mois='06';
      WHEN 'juillet' THEN mois='07';
      WHEN 'aout' THEN mois='08';
      WHEN 'septembre' THEN mois='09';
      WHEN 'octobre' THEN mois='10';
      WHEN 'novembre' THEN mois='11';
      WHEN 'decembre' THEN mois='12';
      ELSE mois = '01';
      END CASE ;

    compDate = concat_ws('-', jour, mois, annee);

    RETURN to_date(compDate, 'DD MM YYYY');
  end;
  $$
LANGUAGE 'plpgsql';


/* Split les lignes contenant plusieurs clichés */


CREATE OR REPLACE FUNCTION splitCliche() 
RETURNS VOID AS $$
DECLARE
    -- ALL ARTICLE
    curs CURSOR FOR select * from projet; --WHERE article = 21653; -- ATT
    t RECORD; 
    l INTEGER;
    nbCliche INTEGER;
    taille VARCHAR(255);
    couleur VARCHAR(255);
    typ VARCHAR(255);
BEGIN
    OPEN curs; LOOP
        FETCH curs INTO t;
        IF NOT FOUND THEN 
            EXIT; 
        END IF; 
        SELECT length(t.nb_cliches) - length(REPLACE(t.nb_cliches,',', '')) INTO l;
        IF l > 0 THEN
            FOR i IN 1 ..l+1 LOOP
                nbCliche = split_part(t.nb_cliches, ',', i);
                taille = split_part(t.taille_cliche, ', ', i);
                couleur = split_part(t.couleur_nb, ', ', i);
                typ = split_part(t.neg_inv, ', ', i);
                --RAISE NOTICE '% % % %', nbCliche, taille, couleur, typ;
                -- INSERER
                INSERT INTO projet VALUES 
                (t.ref_cindoc,t.serie,0,t.discriminant,t.ville,t.sujet,t.description,t.date_prise,t.notes_bas_page,t.index_personnes,t.fichier_numérique,t.index_iconographique,nbCliche,taille,typ,couleur,t.remarques);
                -- SUPPRIMER LIGNE ACTUEL
                DELETE FROM projet p WHERE p.article = t.article;
            END LOOP;
        END IF;
    END LOOP;
    CLOSE curs;
END; $$ LANGUAGE plpgsql;

/* Transforme les dates */

CREATE OR REPLACE FUNCTION dateModif() 
RETURNS VOID AS $$
DECLARE
    -- ALL ARTICLE
    curs CURSOR FOR select * from projet; --WHERE article = 21653; -- ATT
    t RECORD;
    d DATE; 
    tmp VARCHAR(255);
    tiret INTEGER;
    nb INTEGER;
BEGIN
    OPEN curs; LOOP
        FETCH curs INTO t;
        IF NOT FOUND THEN 
            EXIT; 
        END IF; 
        tmp = REPLACE(t.date_prise,'Prise de vue : ', '');
        SELECT length(tmp) - length(REPLACE(tmp,'-', '')) INTO tiret;
        IF tiret > 0 THEN
        tmp = regexp_replace(tmp, '^.*-', '', 'g');
        END IF;
        UPDATE projet SET date_prise = tmp WHERE CURRENT OF curs;
    END LOOP;
    CLOSE curs;
END; $$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION sujetClean() 
RETURNS VOID AS $$
DECLARE
    -- ALL ARTICLE
    curs CURSOR FOR select * from projet; --WHERE article = 2672; -- ATT
    t RECORD; 
    s VARCHAR;
    sujets VARCHAR[];
BEGIN
    OPEN curs; LOOP
        FETCH curs INTO t;
        IF NOT FOUND THEN 
            EXIT; 
        END IF;
        SELECT regexp_replace(t.sujet,'^ *','') INTO s;
        SELECT regexp_replace(s,'^,','') INTO s;
        SELECT regexp_replace(s,',$','') INTO s;
        SELECT array(SELECT DISTINCT UNNEST(regexp_split_to_array(s, ' *, *'))) INTO sujets;
        SELECT array_to_string(sujets,', ') INTO s;
        --RAISE NOTICE '%',s;
        UPDATE projet SET sujet = s WHERE CURRENT OF curs;
    END LOOP;
    CLOSE curs;
END; $$ LANGUAGE plpgsql;



-- MANQUE GESTION PARATHESE
CREATE OR REPLACE FUNCTION villeClean() 
RETURNS VOID AS $$
DECLARE
    -- ALL ARTICLE
    curs CURSOR FOR select * from projet; --WHERE article = 200000; -- ATT
    t RECORD; 
    l INTEGER;
    v text;
    villes VARCHAR[];
BEGIN
    OPEN curs; LOOP
        FETCH curs INTO t;
        IF NOT FOUND THEN 
            EXIT; 
        END IF;
        SELECT regexp_replace(t.ville,'^ *','') INTO v;
        SELECT regexp_replace(v,'^,','') INTO v;
        SELECT regexp_replace(v,',$','') INTO v;
        SELECT regexp_replace(v,' *--.*$','') INTO v;
        SELECT regexp_replace(v,' *--.*,',',') INTO v;
        SELECT regexp_replace(v,',,',',') INTO v;
        SELECT regexp_replace(v,'(\((.*?)\))','','g') INTO v;
        SELECT array(SELECT DISTINCT UNNEST(regexp_split_to_array(v, ' *, *'))) INTO villes;
        SELECT array_to_string(villes,', ') INTO v;
        --RAISE NOTICE '%',v;
        UPDATE projet SET ville = v WHERE CURRENT OF curs;
    END LOOP;
    CLOSE curs;
END; $$ LANGUAGE plpgsql;

DROP TABLE IF EXISTS CIndoc CASCADE;
DROP TABLE IF EXISTS Cliches CASCADE;
DROP TABLE IF EXISTS Cliches_IndexPersonnes CASCADE;
DROP TABLE IF EXISTS Cliches_Sujets CASCADE;
DROP TABLE IF EXISTS IndexIconographiques CASCADE;
DROP TABLE IF EXISTS IndexIconographiques_Cliches CASCADE;
DROP TABLE IF EXISTS IndexPersonnes CASCADE;
DROP TABLE IF EXISTS Series CASCADE;
DROP TABLE IF EXISTS Sujets CASCADE;
DROP TABLE IF EXISTS Tailles CASCADE;
DROP TABLE IF EXISTS Villes CASCADE;
DROP TABLE IF EXISTS Villes_Cliches CASCADE;


CREATE TABLE Cindoc (
  id_cindoc  INTEGER NULL, 
  id_article INTEGER NULL, 
  PRIMARY KEY (id_cindoc, 
  id_article));
CREATE TABLE Cliches (
  id_article      SERIAL, 
  description     TEXT NULL, 
  date_de_prise          date NULL, 
  Fichier         TEXT NULL, 
  Support         VARCHAR(255) NULL, 
  Chroma          varchar(255) NULL, 
  Discriminant    varchar(255) NULL, 
  Nb_Cliche        INTEGER NULL,
  Note_BasDePage TEXT NULL,
  id_taille       INTEGER NULL, 
  id_serie        INTEGER NULL,
  remarque        TEXT NULL, 
  PRIMARY KEY (id_article));
CREATE TABLE Cliches_IndexPersonnes (
  id_article  INTEGER NULL, 
  id_indexPer INTEGER NULL, 
  PRIMARY KEY (id_article, 
  id_indexPer));
CREATE TABLE Cliches_Sujets (
  id_article INTEGER NULL, 
  id_sujet   INTEGER NULL, 
  PRIMARY KEY (id_article, 
  id_sujet));
CREATE TABLE IndexIconographiques (
  id_indexIco SERIAL, 
  indexIco    TEXT NULL, 
  PRIMARY KEY (id_indexIco));
CREATE TABLE IndexIconographiques_Cliches (
  id_indexIco INTEGER NULL, 
  id_article  INTEGER NULL, 
  PRIMARY KEY (id_indexIco, 
  id_article));
CREATE TABLE IndexPersonnes (
  id_indexPer   SERIAL, 
  indexPersonne TEXT NULL, 
  PRIMARY KEY (id_indexPer));
CREATE TABLE Series (
  id_serie SERIAL, 
  serie    char(5) NULL, 
  PRIMARY KEY (id_serie));
CREATE TABLE Sujets (
  id_sujet SERIAL, 
  sujet    varchar(255), 
  PRIMARY KEY (id_sujet));
CREATE TABLE Tailles (
  id_taille  SERIAL, 
  hauteur_cm FLOAT NULL, 
  largeur_cm FLOAT NULL, 
  PRIMARY KEY (id_taille));
CREATE TABLE Villes (
  id_ville SERIAL, 
  nom      varchar(255) NULL, 
  lat      FLOAT DEFAULT NULL, 
  long     FLOAT DEFAULT NULL, 
  PRIMARY KEY (id_ville));
CREATE TABLE Villes_Cliches (
  id_ville   INTEGER NULL, 
  id_article INTEGER NULL, 
  PRIMARY KEY (id_ville, 
  id_article));
ALTER TABLE Cliches ADD FOREIGN KEY (id_taille) REFERENCES Tailles (id_taille);
ALTER TABLE Cliches ADD FOREIGN KEY (id_serie) REFERENCES Series (id_serie);
ALTER TABLE Cliches_IndexPersonnes ADD FOREIGN KEY (id_article) REFERENCES Cliches (id_article);
ALTER TABLE Cliches_IndexPersonnes ADD FOREIGN KEY (id_indexPer) REFERENCES IndexPersonnes (id_indexPer);
ALTER TABLE IndexIconographiques_Cliches ADD FOREIGN KEY (id_indexIco) REFERENCES IndexIconographiques (id_indexIco);
ALTER TABLE IndexIconographiques_Cliches ADD FOREIGN KEY (id_article) REFERENCES Cliches (id_article);
ALTER TABLE Cliches_Sujets ADD FOREIGN KEY (id_article) REFERENCES Cliches (id_article);
ALTER TABLE Cliches_Sujets ADD FOREIGN KEY (id_sujet) REFERENCES Sujets (id_sujet);
ALTER TABLE Villes_Cliches ADD FOREIGN KEY (id_ville) REFERENCES Villes (id_ville);
ALTER TABLE Villes_Cliches ADD FOREIGN KEY (id_article) REFERENCES Cliches (id_article);
ALTER TABLE CIndoc ADD FOREIGN KEY (id_article) REFERENCES Cliches (id_article);


DROP TRIGGER IF EXISTS uniqueSujet ON sujets;
--- Trigger Sujet
CREATE OR REPLACE FUNCTION insertUniqueSujet()
  RETURNS TRIGGER AS $$
  BEGIN
    IF new.sujet IN
        (SELECT sujet FROM Sujets)
      THEN return null;
    END IF;
    RETURN new;
  END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER uniqueSujet
  BEFORE INSERT ON Sujets
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueSujet();


--- Trigger Cindoc
CREATE OR REPLACE FUNCTION insertUniqueCindoc()
  RETURNS TRIGGER AS $$
  BEGIN
    IF new.id_cindoc IN
        (SELECT id_cindoc FROM Cindoc)
      THEN return null;
    END IF;
    RETURN new;
  END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER uniqueCindoc
  BEFORE INSERT ON Cindoc
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueCindoc();


--- Trigger Index Ico
CREATE OR REPLACE FUNCTION insertUniqueIndexIconographique()
  RETURNS TRIGGER AS $$
  BEGIN
    IF new.indexIco IN
        (SELECT indexIco FROM IndexIconographiques)
      THEN return null;
    END IF;
    RETURN new;
  END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER uniqueIndexIconographique
  BEFORE INSERT ON IndexIconographiques
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueIndexIconographique();


--- Trigger Index Personnes
CREATE OR REPLACE FUNCTION insertUniqueIndexpersonne()
  RETURNS TRIGGER AS $$
  BEGIN
    IF new.indexpersonne IN
        (SELECT indexpersonne FROM Indexpersonnes)
      THEN return null;
    END IF;
    RETURN new;
END; $$ LANGUAGE plpgsql;

CREATE TRIGGER uniqueIndexpersonne
  BEFORE INSERT ON Indexpersonnes
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueIndexpersonne();

--- Trigger Serie
CREATE OR REPLACE FUNCTION insertUniqueSerie()
  RETURNS TRIGGER AS $$
  BEGIN
    IF new.serie IN
        (SELECT serie FROM series)
      THEN return null;
    END IF;
    RETURN new;
  END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER uniqueSerie
  BEFORE INSERT ON series
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueSerie();

--- Trigger Ville
CREATE OR REPLACE FUNCTION insertUniqueVille()
  RETURNS TRIGGER AS $$
  BEGIN
    IF new.nom IN
        (SELECT nom FROM villes)
      THEN return null;
    END IF;
    RETURN new;
  END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER uniqueVille
  BEFORE INSERT ON villes
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueVille();


--- Trigger Taille
CREATE OR REPLACE FUNCTION insertUniqueTaille()
  RETURNS TRIGGER AS $$
  BEGIN
    IF (new.hauteur_cm, new.largeur_cm) IN
        (SELECT hauteur_cm, largeur_cm FROM tailles
        WHERE hauteur_cm = new.hauteur_cm
          AND largeur_cm = new.largeur_cm)
      THEN return null;
    END IF;
    RETURN new;
  END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER uniqueTaille
  BEFORE INSERT ON tailles
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueTaille();

--- Trigger Ville_Cliche
CREATE OR REPLACE FUNCTION insertUniqueVilleCliche()
  RETURNS TRIGGER AS $$
  BEGIN
    IF (new.id_ville,new.id_article) IN
        (SELECT id_ville,id_article FROM Villes_Cliches)
      THEN return null;
    END IF;
    RETURN new;
  END;
$$ LANGUAGE 'plpgsql';

DROP TRIGGER IF EXISTS insertUniqueVilleCliche ON Villes_Cliches;
CREATE TRIGGER insertUniqueVilleCliche
  BEFORE INSERT ON Villes_Cliches
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueVilleCliche();


--- Trigger IndexIconographique_Cliche
CREATE OR REPLACE FUNCTION insertUniqueIndexIcoCliche()
  RETURNS TRIGGER AS $$
  BEGIN
    IF (new.id_indexIco,new.id_article) IN
        (SELECT id_indexIco,id_article FROM IndexIconographiques_Cliches)
      THEN return null;
    END IF;
    RETURN new;
  END;
$$ LANGUAGE 'plpgsql';

DROP TRIGGER IF EXISTS uniqueIndexIcoCliche ON IndexIconographiques_Cliches;
CREATE TRIGGER uniqueIndexIcoCliche
  BEFORE INSERT ON IndexIconographiques_Cliches
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueIndexIcoCliche();


--- Trigger Cliches_IndexPersonnes
CREATE OR REPLACE FUNCTION insertUniqueCliches_IndexPersonnes()
  RETURNS TRIGGER AS $$
  BEGIN
    IF (new.id_article,new.id_indexPer) IN
        (SELECT id_indexPer,id_article FROM Cliches_IndexPersonnes)
      THEN return null;
    END IF;
    RETURN new;
  END;
$$ LANGUAGE 'plpgsql';

DROP TRIGGER IF EXISTS uniqueIndexPersoCliche ON Cliches_IndexPersonnes;
CREATE TRIGGER uniqueIndexPersoCliche
  BEFORE INSERT ON Cliches_IndexPersonnes
  FOR EACH ROW
    EXECUTE PROCEDURE insertUniqueCliches_IndexPersonnes();



CREATE OR REPLACE FUNCTION indexPersonnesClean() 
RETURNS VOID AS $$
DECLARE
    -- ALL ARTICLE
    curs CURSOR FOR select * from projet;
    t RECORD; 
    s VARCHAR;
BEGIN
    OPEN curs; LOOP
        FETCH curs INTO t;
        IF NOT FOUND THEN 
            EXIT; 
        END IF;
        SELECT regexp_replace(t.index_personnes,'(\((.*?)\))','','g') INTO s;
        SELECT regexp_replace(s,',','','g') INTO s;
        --RAISE NOTICE '%',s;
        UPDATE projet SET index_personnes = s WHERE CURRENT OF curs;
    END LOOP;
    CLOSE curs;
END; $$ LANGUAGE plpgsql;



CREATE OR REPLACE FUNCTION cleanNegInv() 
RETURNS VOID AS $$
DECLARE
    -- ALL ARTICLE
    curs CURSOR FOR select * from projet; --WHERE article = 21653; -- ATT
    t RECORD; 
BEGIN
    OPEN curs; LOOP
        FETCH curs INTO t;
        IF NOT FOUND THEN 
            EXIT; 
        END IF; 
        IF t.neg_inv ~ 'négatif' THEN
            UPDATE projet SET neg_inv = 'négatif' WHERE CURRENT OF curs;
        ELSE
            UPDATE projet SET neg_inv = 'inversible' WHERE CURRENT OF curs;
        END IF;
    END LOOP;
    CLOSE curs;
END; $$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION cleanTaille() 
RETURNS VOID AS $$
DECLARE
    -- ALL ARTICLE
    curs CURSOR FOR select * from projet; --WHERE article = 21653; -- ATT
    t RECORD; 
BEGIN
    OPEN curs; LOOP
        FETCH curs INTO t;
        IF NOT FOUND THEN 
            EXIT; 
        END IF; 
        t.taille_cliche = regexp_replace(t.taille_cliche, ',', '.', 'g');
        IF t.taille_cliche ~ '[ ](.*)' THEN
            t.taille_cliche = regexp_replace(t.taille_cliche, '[ ](.*)', '', 'g');
        END IF;
        UPDATE projet SET taille_cliche = t.taille_cliche WHERE CURRENT OF curs;
    END LOOP;
    CLOSE curs;
END; $$ LANGUAGE plpgsql;

SELECT splitCliche(); 
SELECT dateModif(); 
SELECT sujetClean(); 
SELECT villeClean(); 
SELECT indexPersonnesClean(); 
SELECT cleanNegInv(); 
SELECT cleanTaille();


CREATE OR REPLACE FUNCTION insertFinal() 
RETURNS VOID AS $$
DECLARE
    curs CURSOR FOR select * from projet; --WHERE article = 2000;
    l INTEGER;
    t RECORD;
    taille_x VARcHAR;
    taille_y VARCHAR;
    taille_xx FLOAT;
    taille_yy FLOAT;
    serie VARCHAR;
    indexPerso TEXT;
    indexIcono TEXT;
    s TEXT;
    nomVille VARCHAR;
    cindoc INTEGER;
    i INTEGER;
    id_cliches INTEGER;
    id_other INTEGER;
    date_prise DATE;
BEGIN
    OPEN curs; LOOP

        FETCH curs INTO t;

        IF NOT FOUND THEN 
            EXIT; 
        END IF;
        RAISE NOTICE '%',t.article;
        SELECT LENGTH(t.Nb_cliches) INTO l;
        IF l > 0 THEN
            SELECT CAST(t.Nb_cliches AS INTEGER) INTO l;
        END IF;
        
        SELECT convertdate(t.date_prise) INTO date_prise;

        INSERT INTO cliches 
        (discriminant,description,Note_BasDePage,Fichier,Nb_Cliche,Chroma,remarque,date_de_prise) 
        VALUES (t.discriminant,t.description,t.notes_bas_page,t.fichier_numérique,l,t.Couleur_NB,t.remarques,date_prise);
        
        -- Ville, Sujet, IndexIco, IndexPerso, tailleCliche, Serie, Cindoc
        IF t.ville != '' THEN
            SELECT length(t.ville) - length(REPLACE(t.ville,',', '')) INTO l;
            IF l > 0 THEN
                FOR i IN 1 ..l+1 LOOP
                    nomVille = split_part(t.ville, ', ', i);
                    INSERT INTO villes (nom) VALUES (nomVille);
                    SELECT MAX(cliches.id_article) FROM cliches INTO id_cliches;
                    SELECT id_ville FROM villes WHERE nom = nomVille INTO id_other;
                    INSERT INTO Villes_Cliches VALUES (id_other,id_cliches);
                END LOOP;
            ELSE
                INSERT INTO villes (nom) VALUES (t.ville);
                SELECT MAX(id_article) FROM cliches INTO id_cliches;
                SELECT id_ville FROM villes WHERE nom = t.ville INTO id_other;
                INSERT INTO Villes_Cliches VALUES (id_other,id_cliches);
            END IF;
        END IF;
        -- Sujet
        IF t.sujet != '' THEN
            SELECT length(t.sujet) - length(REPLACE(t.sujet,',', '')) INTO l;
            IF l > 0 THEN
                FOR i IN 1 ..l+1 LOOP
                    s = split_part(t.sujet, ', ', i);
                    INSERT INTO sujets (sujet) VALUES (s);
                    SELECT MAX(id_article) FROM cliches INTO id_cliches;
                    SELECT id_sujet FROM sujets WHERE sujets.sujet = s INTO id_other;
                    INSERT INTO Cliches_Sujets VALUES (id_cliches,id_other);
                END LOOP;
            ELSE
                INSERT INTO sujets (sujet) VALUES (t.sujet);
                SELECT MAX(id_article) FROM cliches INTO id_cliches;
                SELECT id_sujet FROM sujets WHERE sujets.sujet = t.sujet INTO id_other;
                INSERT INTO Cliches_Sujets VALUES (id_cliches,id_other);
            END IF;
        END IF;
        -- tailleCliche
        IF t.taille_cliche != '' THEN
            taille_y = split_part(t.taille_cliche, 'x', 1);
            taille_x = split_part(t.taille_cliche, 'x', 2);
            SELECT CAST(taille_y AS FLOAT) INTO taille_yy;
            SELECT CAST(taille_x AS FLOAT) INTO taille_xx;
            INSERT INTO tailles (hauteur_cm, largeur_cm) VALUES (taille_yy,taille_xx);
            SELECT MAX(id_article) FROM cliches INTO id_cliches;
            SELECT id_taille FROM tailles WHERE hauteur_cm = taille_yy AND largeur_cm = taille_xx INTO id_other;
            UPDATE cliches SET id_taille = id_other WHERE id_article = id_cliches;
        END IF;
        -- Serie
        IF t.serie != '' THEN
            INSERT INTO series (serie) VALUES (t.serie);
            SELECT MAX(id_article) FROM cliches INTO id_cliches;
            SELECT series.id_serie FROM series WHERE series.serie = t.serie INTO id_other;
            UPDATE cliches SET id_serie = id_other WHERE id_article = id_cliches;
        END IF;
        -- IndexIco
        IF t.index_iconographique != '' THEN
            SELECT length(t.index_iconographique) - length(REPLACE(t.index_iconographique,'/', '')) INTO l;
            IF l > 0 THEN
                FOR i IN 1 ..l+1 LOOP
                    indexIcono = split_part(t.index_iconographique, '/ ', i);
                    INSERT INTO IndexIconographiques (indexIco) VALUES (indexIcono);
                    SELECT MAX(id_article) FROM cliches INTO id_cliches;
                    SELECT id_indexIco FROM IndexIconographiques WHERE indexIco = indexIcono INTO id_other;
                    INSERT INTO IndexIconographiques_Cliches VALUES (id_other,id_cliches);
                END LOOP;
            ELSE
                INSERT INTO IndexIconographiques (indexIco) VALUES (t.index_iconographique);
                SELECT MAX(id_article) FROM cliches INTO id_cliches;
                SELECT id_indexIco FROM IndexIconographiques WHERE indexIco = t.index_iconographique INTO id_other;
                INSERT INTO IndexIconographiques_Cliches VALUES (id_other,id_cliches);
            END IF;
        END IF;
        -- indexPerso
        IF t.index_personnes != '' THEN
            SELECT length(t.index_personnes) - length(REPLACE(t.index_personnes,'/', '')) INTO l;
            IF l > 0 THEN
                FOR i IN 1 ..l+1 LOOP
                    indexPerso = split_part(t.index_personnes, '/ ', i);
                    INSERT INTO IndexPersonnes (indexPersonne) VALUES (indexPerso);
                    SELECT MAX(id_article) FROM cliches INTO id_cliches;
                    SELECT id_indexPer FROM IndexPersonnes WHERE indexPersonne = indexPerso INTO id_other;
                    INSERT INTO Cliches_IndexPersonnes VALUES (id_cliches,id_other);
                END LOOP;
            ELSE
                INSERT INTO IndexPersonnes (indexPersonne) VALUES (t.index_personnes);
                SELECT MAX(id_article) FROM cliches INTO id_cliches;
                SELECT id_indexPer FROM IndexPersonnes WHERE indexPersonne = t.index_personnes INTO id_other;
                INSERT INTO Cliches_IndexPersonnes VALUES (id_cliches,id_other);
            END IF;
        END IF;
        -- Cindoc
        IF t.ref_cindoc != '' THEN
            SELECT length(t.ref_cindoc) - length(REPLACE(t.ref_cindoc,'|', '')) INTO l;
            IF l > 0 THEN
                FOR i IN 1 ..l+1 LOOP
                    cindoc = split_part(t.ref_cindoc, ' | ', i);
                    SELECT CAST(cindoc AS INTEGER) INTO cindoc;
                    SELECT MAX(id_article) FROM cliches INTO id_cliches;
                    INSERT INTO cindoc (id_article,id_cindoc) VALUES (id_cliches,cindoc);
                END LOOP;
            ELSE
                SELECT MAX(id_article) FROM cliches INTO id_cliches;
                SELECT CAST(t.ref_cindoc AS INTEGER) INTO cindoc;
                INSERT INTO cindoc (id_article,id_cindoc) VALUES (id_cliches,cindoc);
            END IF;
        END IF;
    END LOOP;
    CLOSE curs;     
END; $$ LANGUAGE 'plpgsql';

SELECT insertFinal();