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