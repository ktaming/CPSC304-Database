--Find all roles who belong to a Family(eg. Stark Family)
SELECT 	ROName
FROM 	ROLE
WHERE 	FAName = 'Stark';

--find all roles who are from a Nation(eg. Iron Islands)
SELECT 	ROName
FROM 	ROLE
WHERE 	NAName= 'Iron Islands';

--find all weapons that a Arsenal (eg. ARId = 1) produces
SELECT 	WEid
FROM 	WEAPON
WHERE 	ARId = '1';

--find all Nations that a Family(eg. Stark Family) manages
SELECT 	NAName
FROM 	NATION
WHERE	FAName = 'Stark';

--find all Families who join an Alliance(eg. Avengers)
SELECT	ALName
FROM 	ALLIANCE
WHERE	ALName = 'Avengers';

--select all from role (eg. select Role(ROName: Ned Stark))
SELECT 	*
FROM	ROLE
WHERE	ROName= 'Ned Stark';

--find the Weapon that has the highest attack value
SELECT 	WEId
FROM 	WEAPON
WHERE 	WEAttack = (SELECT MAX(WEAttack) FROM WEAPON);

--find the Dragon that has the highest attack value
SELECT 	DRName
FROM 	DRAGON
WHERE 	DRAttack = (SELECT MAX(DRAttack) FROM DRAGON);

--find the specific Nation that a Family(eg. Stark Family) manage
SELECT 	NAName
FROM	NATION
WHERE	FAName = 'Stark';

--check how many arsenals a Family (eg. Stark Family) has
select COUNT(*)
FROM 	ARSENAL
WHERE 	FAName = 'Stark';

--check how many dragons a Role (eg. ROName: Daenerys) raises
select COUNT	(*)
FROM 	RAISE
WHERE 	ROName= 'Daenerys';

--UPDATE: increase a Weapons attack 

UPDATE	Weapon
SET 	WEAttack = WEAttack + 1
WHERE 	WEId = 'AK47';


--change the Family that manages the Nation (Iron Islands) into Lucas
UPDATE	NATION
SET 	FAName = 'Lucas'
WHERE 	FAName = 'Iron Islands';


--SOME INTERESTING QUERIES:
--check who will win if 2 Alliances (eg. Avengers & The Frozen Shields) battle
SELECT Winner
FROM battle 
WHERE ALName_1 = 'Avengers' AND ALName_2 = 'The Frozen Shields'
UNION
SELECT Winner
FROM battle 
WHERE ALName_2 = 'Avengers' AND ALName_1 = 'The Frozen Shields';

--check which Role in a Family (eg.  Stark Family) owns more than 1 weapon
SELECT 	ROName
FROM 	ROLE
WHERE 	FAName = 'Stark'
INTERSECT
SELECT 	ROName
FROM 	WEAPON
GROUP BY 	ROName
HAVING 	COUNT(*) >= 2;

--check which Role raises the Dragon whose DRAttack is the highest
SELECT 	D1.ROName
FROM	Dragon_Raise D1
WHERE 	D1.DRAttack = (SELECT MAX(D2.DRAttack) FROM Dragon_Raise D2);
