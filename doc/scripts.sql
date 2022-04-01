-- Maj des admin des operations
INSERT INTO droit_compte
SELECT "cleforestier", id, "admin"
FROM operations
WHERE id_user="clf";

-- Suppression des operations de test personnelles
DELETE FROM operations WHERE id_user="ab";

-- Récupération des sujets sans aucun depot
SELECT *
FROM sujet_handicape
WHERE NOT EXISTS (
    SELECT depot.id
    FROM depot
    WHERE depot.id=id_depot
)

-- Dépôts sans sujets
SELECT *
FROM depot
WHERE NOT EXISTS (
    SELECT sujet.id
    FROM sujet_handicape AS sujet
    WHERE sujet.id_depot=depot.id
)

-- Sujets ayant un depot en commun avec au moins un autre sujet
SELECT *
FROM sujet_handicape AS original
WHERE EXISTS(
    SELECT id
    FROM sujet_handicape AS copy
    WHERE copy.id_depot = original.id_depot
    AND copy.id != original.id
);

-- Sujets sans aucun groupe
SELECT *
FROM sujet_handicape
WHERE NOT EXISTS (
    SELECT groupe.id
    FROM groupe_sujets AS groupe
    WHERE groupe.id=id_groupe_sujets
)

-- Groupes sans sujets
SELECT *
FROM groupe_sujets
WHERE NOT EXISTS (
    SELECT sujet.id
    FROM sujet_handicape AS sujet
    WHERE sujet.id_groupe_sujets=groupe_sujets.id
)

-- Sujets ayant un groupe en commun avec au moins un autre sujet
SELECT *
FROM sujet_handicape AS original
WHERE EXISTS(
    SELECT id
    FROM sujet_handicape AS copy
    WHERE copy.id_groupe_sujets = original.id_groupe_sujets
    AND copy.id != original.id
);

-- Opérations doublons
SELECT *
FROM operations AS original
WHERE EXISTS(
    SELECT *
    FROM operations AS copy
    WHERE copy.id!=original.id
    AND copy.X=original.X
    AND copy.Y=original.Y
    AND copy.X IS NOT NULL
    AND copy.Y IS NOT NULL
);

-- Sujet handicape ayant les 5 pathologies indiqués
SELECT *
FROM sujet_handicape
WHERE (
    SELECT COUNT(copy.id)
    FROM sujet_handicape AS copy
    JOIN atteinte_pathologie AS ap
    ON ap.id_sujet=copy.id
    WHERE copy.id=sujet_handicape.id
    AND (ap.id_pathologie=1
        OR ap.id_pathologie=2
        OR ap.id_pathologie=3
        OR ap.id_pathologie=4
        OR ap.id_pathologie=5
        )
)=5

-- Images sans sujets existants
SELECT *
FROM sujet_image
WHERE NOT EXISTS(
    SELECT id
    FROM sujet_handicape
    WHERE sujet_handicape.id=id_sujet
)

-- Commune sans numéro INSEE
SELECT
    COUNT(insee) AS restant,
    (SELECT COUNT(insee) FROM commune) AS total
FROM `commune` WHERE insee=0

-- Selection droit de compte sans operations
SELECT * 
FROM droit_compte
LEFT JOIN operations
ON id_operation=operations.id
WHERE operations.id IS NULL

DELETE
FROM droit_compte
WHERE NOT EXISTS(
    SELECT id
    FROM operations
    WHERE operations.id=id_operation
)

-- Suppression de toutes les personnes
TRUNCATE etre_responsable;
TRUNCATE etre_paleopathologiste;
TRUNCATE etre_anthropologue;
TRUNCATE personne;
