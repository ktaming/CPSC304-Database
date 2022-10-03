
DROP TABLE own;
DROP TABLE raise;
DROP TABLE join;
DROP TABLE isFrom;
DROP TABLE produce;
DROP TABLE dragon;
DROP TABLE belong;
DROP TABLE manage;
DROP TABLE protect;
DROP TABLE weapon;
DROP TABLE lead;
DROP TABLE have;
DROP TABLE army;
DROP TABLE role;
DROP TABLE arsenal;
DROP TABLE nation;
DROP TABLE family;
DROP TABLE battle;
DROP TABLE alliance;


CREATE TABLE ALLIANCE
(
   ALName     CHAR(20),
   ALCapacity INTEGER,
   PRIMARY KEY (ALName)
);


CREATE TABLE battle
(
   ALName_1 CHAR(20),
   ALName_2 CHAR(20),
   Winner   CHAR(20),
   PRIMARY KEY (ALName_1, ALName_2),
   FOREIGN KEY (ALName_1) REFERENCES ALLIANCE (ALName),
   FOREIGN KEY (ALName_2) REFERENCES ALLIANCE (ALName)
);


CREATE TABLE FAMILY
(
   FAName CHAR(20),
   Flag   CHAR(20),
   Mascot CHAR(20),
   PRIMARY KEY (FAName)
);


CREATE TABLE ARSENAL
(
   ARId       INTEGER,
   Location   CHAR(20),
   ASCapacity INTEGER,
   FAName     CHAR(20) NOT NULL,
   PRIMARY KEY (ARId),
   FOREIGN KEY (FAName) REFERENCES FAMILY (FAName)
);


CREATE TABLE NATION
(
   NAName    CHAR(20),
   Capital   CHAR(20),
   Longitude INTEGER,
   Latitude  INTEGER,
   PRIMARY KEY (NAName),
   UNIQUE (Latitude, Longitude),
   FAName    CHAR(20),
   FOREIGN KEY (FAName) REFERENCES FAMILY (FAName)
);


CREATE TABLE ROLE
(
   ROName CHAR(20) PRIMARY KEY,
   Age    INTEGER,
   Gender CHAR(20),
   FAName CHAR(20),
   NAName CHAR(20),
   Title  CHAR(20),
   Leader CHAR(20),
   CHECK ( (Title IS NOT NULL ) OR (Leader IS NOT NULL) ),
   FOREIGN KEY (FAName) REFERENCES FAMILY (FAName),
   FOREIGN KEY (NAName) REFERENCES NATION (NAName)
);

CREATE TABLE ARMY
(
   ARName      CHAR(20),
   AMCapacity  INTEGER,
   SoldierType CHAR(20),
   Expenditure INTEGER,
   NAName      CHAR(20),
   ROName      CHAR(20),
   PRIMARY KEY (ARName),
   FOREIGN KEY (NAName) REFERENCES NATION (NAName),
   FOREIGN KEY (ROName) REFERENCES ROLE (ROName),
   UNIQUE (NAName, ROName)
);





CREATE TABLE lead
(
   ROName CHAR(20),
   ARName CHAR(20),
   PRIMARY KEY (ROName, ARName),
   FOREIGN KEY (ROName) REFERENCES ROLE (ROName),
   FOREIGN KEY (ARName) REFERENCES ARMY (ARName)
);

CREATE TABLE protect
(
   ARName CHAR(20),
   NAName CHAR(20),
   PRIMARY KEY (ARName, NAName),
   FOREIGN KEY (ARName) REFERENCES ARMY (ARName),
   FOREIGN KEY (NAName) REFERENCES NATION (NAName)
);

-- weak entity dragon
CREATE TABLE DRAGON
(
   DRName   CHAR(20),
   ROName   CHAR(20) NOT NULL,
   DRAttack INTEGER,
   PRIMARY KEY (DRName),
   FOREIGN KEY (ROName) REFERENCES ROLE (ROName)
);

CREATE TABLE manage
(
   fid CHAR(20),
   nid CHAR(20),
   PRIMARY KEY (nid),
   FOREIGN KEY (fid) REFERENCES FAMILY (FAName),
   FOREIGN KEY (nid) REFERENCES NATION (NAName)
);

CREATE TABLE have
(
   FAName CHAR(20) NOT NULL,
   ARId   INTEGER,
   PRIMARY KEY (ARId),
   FOREIGN KEY (FAName) REFERENCES FAMILY (FAName),
   FOREIGN KEY (ARId) REFERENCES ARSENAL (ARId)
);

CREATE TABLE belong
(
   rid CHAR(20),
   fid CHAR(20),
   PRIMARY KEY (rid),
   FOREIGN KEY (rid) REFERENCES ROLE (ROName),
   FOREIGN KEY (fid) REFERENCES FAMILY (FAName) NOT DEFERRABLE
);


CREATE TABLE WEAPON
(
   WEId     CHAR(20),
   WELevel    INTEGER,
   WEAttack INTEGER,
   Defence  INTEGER,
   ARId     INTEGER,
   ROName   CHAR(20),
   PRIMARY KEY (WEId),
   FOREIGN KEY (ARId) REFERENCES ARSENAL (ARId),
   FOREIGN KEY (ROName) REFERENCES ROLE (ROName)
);

CREATE TABLE produce
(
   ARId INTEGER,
   WEId CHAR(20),
   PRIMARY KEY (WEId),
   FOREIGN KEY (ARId) REFERENCES ARSENAL (ARId) NOT DEFERRABLE,
   FOREIGN KEY (WEId) REFERENCES WEAPON (WEId)
);

CREATE TABLE isFrom
(
   rid CHAR(20),
   nid CHAR(20),
   PRIMARY KEY (rid),
   FOREIGN KEY (nid) REFERENCES NATION (NAName) NOT DEFERRABLE,
   FOREIGN KEY (rid) REFERENCES ROLE (ROName)
);



CREATE TABLE join
(
   ALName CHAR(20),
   FAName CHAR(20),
   Leader CHAR(20),
   PRIMARY KEY (ALName, FAName),
   FOREIGN KEY (FAName) REFERENCES FAMILY (FAName),
   FOREIGN KEY (ALName) REFERENCES ALLIANCE (ALName)

);



CREATE TABLE raise
(
   ROName CHAR(20) NOT NULL,
   DRName CHAR(20),
   PRIMARY KEY (DRName),
   FOREIGN KEY (DRName) REFERENCES DRAGON (DRName),
   CONSTRAINT fk_raise
   FOREIGN KEY (ROName) REFERENCES ROLE (ROName) NOT DEFERRABLE
);



CREATE TABLE own
(
   ROName CHAR(20),
   WEId   CHAR(20),
   PRIMARY KEY (WEId),
   FOREIGN KEY (ROName) REFERENCES ROLE (ROName) NOT DEFERRABLE,
   FOREIGN KEY (WEId) REFERENCES WEAPON (WEId)
);


-------------------------------------------  family  ----------------------------------------------------
INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Stark', 'Black Leopard', 'Leopard');

INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Tyrell', 'Gold Deer Banner', 'Snake');

INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Bla', 'Gold Deer Banner', 'Snake');

INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Targaryen', '3 Head Dragon', 'Dragon');

INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Greyjoy', 'Sigil Banner', 'Monster');

INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Lannister', 'Red Lion Banner', 'Lion');

-------------------------------------------  nation ----------------------------------------------------

INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('Dorne', 'Vaith', '43', '6', 'Bla');

INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('The Westerlands', 'Casterly Rock', '-21.584', '11', 'Lannister');

INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('The North', 'Winterfell', '-5.6', '54.4', 'Stark');

INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('Iron Islands', 'Pyke', '20', '27.8', 'Greyjoy');

INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('Seven Kingdoms', 'Westeros', '60', '16', 'Targaryen');


-------------------------------------------  role  ----------------------------------------------------

INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Ned Stark', '40', 'M', 'Stark', 'The North', 'King', NULL);

INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Daenerys', '25', 'F', 'Targaryen', 'Seven Kingdoms', 'Queen', NULL);

INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Theon', '40', 'M', 'Stark', 'The North', NULL, 'Ned Stark');

INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Alice', '12', 'F', 'Bla', 'Dorne', 'Princess', NULL);

INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Tyrion', '32', 'M', 'Lannister', 'The Westerlands', NULL, 'Tyrion');

-------------------------------------------  army  ----------------------------------------------------

INSERT INTO ARMY (ARName, amcapacity, soldiertype, expenditure, naname, roname)
VALUES ('Red Flame', '777', 'Archer', 80000, 'The Westerlands', 'Tyrion');

INSERT INTO ARMY (ARName, amcapacity, soldiertype, expenditure, naname, roname)
VALUES ('Elite Force', '50', 'Cavalry', 8880000, 'Seven Kingdoms', 'Daenerys');

------------------------------------------- dragon ----------------------------------------------------
INSERT INTO DRAGON (drname, roname, drattack)
VALUES ('Guagua', 'Daenerys', 20000);

INSERT INTO DRAGON (drname, roname, drattack)
VALUES ('Drogon', 'Daenerys', 230000);

INSERT INTO DRAGON (drname, roname, drattack)
VALUES ('Khal', 'Daenerys', 100);

INSERT INTO raise (roname, drname)
VALUES ('Daenerys', 'Guagua');

INSERT INTO raise (roname, drname)
VALUES ('Daenerys', 'Drogon');

INSERT INTO raise (roname, drname)
VALUES ('Daenerys', 'Khal');

------------------------------------------- arsenal ----------------------------------------------------

INSERT INTO ARSENAL (arid, location, ascapacity, faname)
VALUES (1, 'Winterfell', 8000, 'Stark');

INSERT INTO ARSENAL (arid, location, ascapacity, faname)
VALUES (2, 'Westeros', 8000, 'Targaryen');

INSERT INTO ARSENAL (arid, location, ascapacity, faname)
VALUES (3, 'Vancouver', 800000, 'Lannister');


------------------------------------------- weapon ----------------------------------------------------

INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('AK47', 20, 8888, 50, 2, 'Ned Stark');

INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('M416', 50, 8888888, 588880, 1, 'Theon');

INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('Frying Pan', 0, 20, 50000, 1, 'Daenerys');

INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('Vibranium Shield', 20, 8888, 50, 3, 'Ned Stark');

INSERT INTO produce (arid, weid)
VALUES (2, 'AK47');

INSERT INTO produce (arid, weid)
VALUES (1, 'M416');

INSERT INTO produce (arid, weid)
VALUES (1, 'Frying Pan');

INSERT INTO produce (arid, weid)
VALUES (3, 'Vibranium Shield');

------------------------------------------- alliance ----------------------------------------------------

INSERT INTO ALLIANCE (alname, alcapacity)
VALUES ('Avengers', 20);

INSERT INTO ALLIANCE (alname, alcapacity)
VALUES ('The Frozen Shields', 20);


INSERT INTO belong (rid, fid)
VALUES ('Ned Stark', 'Stark');

INSERT INTO belong (rid, fid)
VALUES ('Daenerys', 'Targaryen');

INSERT INTO belong (rid, fid)
VALUES ('Theon', 'Stark');

INSERT INTO belong (rid, fid)
VALUES ('Alice', 'Bla');

INSERT INTO belong (rid, fid)
VALUES ('Tyrion', 'Lannister');

INSERT INTO isfrom (rid, nid)
VALUES ('Ned Stark', 'The North');

INSERT INTO isfrom (rid, nid)
VALUES ('Daenerys', 'Seven Kingdoms');

INSERT INTO isfrom (rid, nid)
VALUES ('Theon', 'The North');

INSERT INTO isfrom (rid, nid)
VALUES ('Alice', 'Dorne');

INSERT INTO isfrom (rid, nid)
VALUES ('Tyrion', 'The Westerlands');

INSERT INTO manage (nid, fid)
VALUES ('The North', 'Stark');

INSERT INTO manage (nid, fid)
VALUES ('Seven Kingdoms', 'Targaryen');

INSERT INTO manage (nid, fid)
VALUES ('Dorne', 'Bla');

INSERT INTO manage (nid, fid)
VALUES ('Iron Islands', 'Greyjoy');

INSERT INTO manage (nid, fid)
VALUES ('The Westerlands', 'Lannister');

INSERT INTO protect (ARName, NAName)
VALUES ('Red Flame', 'The Westerlands');

INSERT INTO protect (ARName, NAName)
VALUES ('Elite Force', 'Seven Kingdoms');

INSERT INTO lead (ROName, ARName)
VALUES ('Tyrion', 'Red Flame');

INSERT INTO lead (ROName, ARName)
VALUES ('Daenerys', 'Elite Force');

INSERT INTO have (FAName, ARId)
VALUES ('Stark', 1);
INSERT INTO have (FAName, ARId)
VALUES ('Targaryen', 2);
INSERT INTO have (FAName, ARId)
VALUES ('Lannister', 3);

INSERT INTO own (ROName, WEId)
VALUES ('Ned Stark', 'AK47');

INSERT INTO own (ROName, WEId)
VALUES ('Theon', 'M416');

INSERT INTO own (ROName, WEId)
VALUES ('Daenerys', 'Frying Pan');

INSERT INTO own (ROName, WEId)
VALUES ('Ned Stark', 'Vibranium Shield');

INSERT INTO join (ALName, FAName, Leader)
VALUES ('Avengers', 'Stark', 'Ned Stark');

INSERT INTO join (ALName, FAName, Leader)
VALUES ('Avengers', 'Targaryen', 'Daenerys');

INSERT INTO join (ALName, FAName, Leader)
VALUES ('The Frozen Shields', 'Tyrell', 'Theon');

INSERT INTO join (ALName, FAName, Leader)
VALUES ('The Frozen Shields', 'Bla', 'Alice');

INSERT INTO battle (alname_1, alname_2, winner)
VALUES ('Avengers', 'The Frozen Shields', 'Avengers');

