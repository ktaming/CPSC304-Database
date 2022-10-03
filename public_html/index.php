<?php
require_once "oci.php";
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False;
// set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

connectToDB();
dropOldTable(); //这两个函数需要填入来自siwei的SQL代码
createNewTable(); //这两个函数需要填入来自siwei的SQL代码
insertDefaultData();
disconnectFromDB();
//OCICommit($db_conn);

function debugAlertMessage($message)
{
  global $show_debug_alert_messages;

  if ($show_debug_alert_messages) {
    echo "<script type='text/javascript'>alert('" . $message . "');</script>";
  }
}

function executePlainSQL($cmdstr)
{ //takes a plain (no bound variables) SQL command and executes it
  //echo "<br>running ".$cmdstr."<br>";
  global $db_conn, $success;

  $statement = OCIParse($db_conn, $cmdstr);
  //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

  if (!$statement) {
    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
    $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
    echo htmlentities($e['message']);
    $success = False;
  }

  $r = OCIExecute($statement, OCI_DEFAULT);
  if (!$r) {
    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
    $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
    echo htmlentities($e['message']);
    $success = False;
  }

  return $statement;
}

function executeBoundSQL($cmdstr, $list)
{
  /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
                In this case you don't need to create the statement several times. Bound variables cause a statement to only be parsed once 
                and you can reuse the statement. This is also very useful in protecting against SQL injection. 
                See the sample code below for how this function is used 
                */

  global $db_conn, $success;
  $statement = OCIParse($db_conn, $cmdstr);

  if (!$statement) {
    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
    $e = OCI_Error($db_conn);
    echo htmlentities($e['message']);
    $success = False;
  }

  foreach ($list as $tuple) {
    foreach ($tuple as $bind => $val) {
      //echo $val;
      //echo "<br>".$bind."<br>";
      OCIBindByName($statement, $bind, $val);
      unset($val);
      //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
      echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
      $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
      echo htmlentities($e['message']);
      echo "<br>";
      $success = False;
    }
  }
}

function connectToDB()
{
  global $db_conn;

  // Your username is ora_(CWL_ID) and the password is a(student number). For example, 
  // ora_platypus is the username and a12345678 is the password.
  $db_conn = OCILogon("ora_siweiz", "a14668461", "dbhost.students.cs.ubc.ca:1522/stu");

  // $db_conn = OCILogon("ora_ktaming", "a15815988", "dbhost.students.cs.ubc.ca:1522/stu");

  // $db_conn = OCILogon("ora_cazhang", "a34466326", "dbhost.students.cs.ubc.ca:1522/stu");

  if ($db_conn) {
    debugAlertMessage("Database is Connected");
    return true;
  } else {
    debugAlertMessage("Cannot connect to Database");
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
    return false;
  }
}

function disconnectFromDB()
{
  global $db_conn;

  debugAlertMessage("Disconnect from Database");
  OCILogoff($db_conn);
}

function dropOldTable()
{
  global $db_conn;
  // executePlainSQL("
  // DROP TABLE battle");

  executePlainSQL("DROP TABLE own");

  executePlainSQL("
    DROP TABLE raise");

  executePlainSQL("
    DROP TABLE join");

  executePlainSQL("
    DROP TABLE isFrom");

  executePlainSQL("
    DROP TABLE produce");

  executePlainSQL("
    DROP TABLE dragon");

  executePlainSQL("
    DROP TABLE belong");

  executePlainSQL("
    DROP TABLE manage");

  executePlainSQL("
    DROP TABLE protect");

  executePlainSQL("
    DROP TABLE weapon");

  executePlainSQL("
    DROP TABLE lead");

  executePlainSQL("
    DROP TABLE have");

  executePlainSQL("
    DROP TABLE army");

  executePlainSQL("
    DROP TABLE role");

  executePlainSQL("
    DROP TABLE arsenal");

  executePlainSQL("
    DROP TABLE nation");

  executePlainSQL("
    DROP TABLE family");

  executePlainSQL("
    DROP TABLE battle");

  executePlainSQL("
    DROP TABLE alliance");

  OCICommit($db_conn);
}

function createNewTable()
{
  global $db_conn;
  // executePlainSQL("	  
  // CREATE TABLE ALLIANCE
  // (
  //    ALName     CHAR(20),
  //    ALCapacity INTEGER,
  //    PRIMARY KEY (ALName)
  // )");

  executePlainSQL("	  
    CREATE TABLE ALLIANCE
    (
       ALName     CHAR(20),
       ALCapacity INTEGER,
       PRIMARY KEY (ALName)
    )");

  executePlainSQL("	  
    CREATE TABLE battle
    (
       ALName_1 CHAR(20),
       ALName_2 CHAR(20),
       Winner   CHAR(20),
       PRIMARY KEY (ALName_1, ALName_2),
       FOREIGN KEY (ALName_1) REFERENCES ALLIANCE (ALName),
       FOREIGN KEY (ALName_2) REFERENCES ALLIANCE (ALName)
    )");

  executePlainSQL("	  
    CREATE TABLE FAMILY
    (
       FAName CHAR(20),
       Flag   CHAR(20),
       Mascot CHAR(20),
       PRIMARY KEY (FAName)
    )");

  executePlainSQL("	  
    CREATE TABLE ARSENAL
    (
       ARId       INTEGER,
       Location   CHAR(20),
       ASCapacity INTEGER,
       FAName     CHAR(20) NOT NULL,
       PRIMARY KEY (ARId),
       FOREIGN KEY (FAName) REFERENCES FAMILY (FAName)
    )");

  executePlainSQL("	  
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
    )");

  executePlainSQL("	  
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
    )");

  executePlainSQL("	  
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
    )");

  executePlainSQL("	  
    CREATE TABLE lead
    (
       ROName CHAR(20),
       ARName CHAR(20),
       PRIMARY KEY (ROName, ARName),
       FOREIGN KEY (ROName) REFERENCES ROLE (ROName),
       FOREIGN KEY (ARName) REFERENCES ARMY (ARName)
    )");

  executePlainSQL("	  
    CREATE TABLE protect
    (
       ARName CHAR(20),
       NAName CHAR(20),
       PRIMARY KEY (ARName, NAName),
       FOREIGN KEY (ARName) REFERENCES ARMY (ARName),
       FOREIGN KEY (NAName) REFERENCES NATION (NAName)
    )");

  // weak entity dragon
  executePlainSQL("	  
    CREATE TABLE DRAGON
    (
       DRName   CHAR(20),
       ROName   CHAR(20) NOT NULL,
       DRAttack INTEGER,
       PRIMARY KEY (DRName),
       FOREIGN KEY (ROName) REFERENCES ROLE (ROName)
    )");

  executePlainSQL("	  
    CREATE TABLE manage
    (
       fid CHAR(20),
       nid CHAR(20),
       PRIMARY KEY (nid),
       FOREIGN KEY (fid) REFERENCES FAMILY (FAName),
       FOREIGN KEY (nid) REFERENCES NATION (NAName)
    )");

  executePlainSQL("	  
    CREATE TABLE have
    (
       FAName CHAR(20) NOT NULL,
       ARId   INTEGER,
       PRIMARY KEY (ARId),
       FOREIGN KEY (FAName) REFERENCES FAMILY (FAName),
       FOREIGN KEY (ARId) REFERENCES ARSENAL (ARId)
    )");

  executePlainSQL("	  
    CREATE TABLE belong
    (
       rid CHAR(20),
       fid CHAR(20),
       PRIMARY KEY (rid),
       FOREIGN KEY (rid) REFERENCES ROLE (ROName),
       FOREIGN KEY (fid) REFERENCES FAMILY (FAName) NOT DEFERRABLE
    )");

  executePlainSQL("	  
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
    )");

  executePlainSQL("	  
    CREATE TABLE produce
    (
       ARId INTEGER,
       WEId CHAR(20),
       PRIMARY KEY (WEId),
       FOREIGN KEY (ARId) REFERENCES ARSENAL (ARId) NOT DEFERRABLE,
       FOREIGN KEY (WEId) REFERENCES WEAPON (WEId)
    )");

  executePlainSQL("	  
    CREATE TABLE isFrom
    (
       rid CHAR(20),
       nid CHAR(20),
       PRIMARY KEY (rid),
       FOREIGN KEY (nid) REFERENCES NATION (NAName) NOT DEFERRABLE,
       FOREIGN KEY (rid) REFERENCES ROLE (ROName)
    )");

  executePlainSQL("	  
    CREATE TABLE join
    (
       ALName CHAR(20),
       FAName CHAR(20),
       Leader CHAR(20),
       PRIMARY KEY (ALName, FAName),
       FOREIGN KEY (FAName) REFERENCES FAMILY (FAName),
       FOREIGN KEY (ALName) REFERENCES ALLIANCE (ALName)
    
    )");

  executePlainSQL("	  
    CREATE TABLE raise
    (
       ROName CHAR(20) NOT NULL,
       DRName CHAR(20),
       PRIMARY KEY (DRName),
       FOREIGN KEY (DRName) REFERENCES DRAGON (DRName),
       CONSTRAINT fk_raise
       FOREIGN KEY (ROName) REFERENCES ROLE (ROName) NOT DEFERRABLE
    )");

  executePlainSQL("	  
    CREATE TABLE own
    (
       ROName CHAR(20),
       WEId   CHAR(20),
       PRIMARY KEY (WEId),
       FOREIGN KEY (ROName) REFERENCES ROLE (ROName) NOT DEFERRABLE,
       FOREIGN KEY (WEId) REFERENCES WEAPON (WEId)
    )");

  OCICommit($db_conn);
}

function insertDefaultData()
{
  global $db_conn;

  executePlainSQL("
      INSERT INTO FAMILY (FAName, Flag, Mascot)
      VALUES ('Stark', 'Black Leopard', 'Leopard')
    ");

  executePlainSQL("
    INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Tyrell', 'Gold Deer Banner', 'Snake')");

  executePlainSQL("
    INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Bla', 'Gold Deer Banner', 'Snake')");


  executePlainSQL("
INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Targaryen', '3 Head Dragon', 'Dragon')");

  executePlainSQL("
INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Greyjoy', 'Sigil Banner', 'Monster')");

  executePlainSQL("
INSERT INTO FAMILY (FAName, Flag, Mascot)
VALUES ('Lannister', 'Red Lion Banner', 'Lion')");

  executePlainSQL("
INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('Dorne', 'Vaith', '43', '6', 'Bla')");

  executePlainSQL("
INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('The Westerlands', 'Casterly Rock', '-21.584', '11', 'Lannister')");

  executePlainSQL("
INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('The North', 'Winterfell', '-5.6', '54.4', 'Stark')");

  executePlainSQL("
INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('Iron Islands', 'Pyke', '20', '27.8', 'Greyjoy')");

  executePlainSQL("
INSERT INTO Nation (NAName, Capital, Longitude, Latitude, FAName)
VALUES ('Seven Kingdoms', 'Westeros', '60', '16', 'Targaryen')");



  // ========== role ===========================
  executePlainSQL("
INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Ned Stark', '40', 'M', 'Stark', 'The North', 'King', NULL)");

  executePlainSQL("
INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Daenerys', '25', 'F', 'Targaryen', 'Seven Kingdoms', 'Queen', NULL)");

  executePlainSQL("
INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Theon', '40', 'M', 'Stark', 'The North', NULL, 'Ned Stark')");

  executePlainSQL("
INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Alice', '12', 'F', 'Bla', 'Dorne', 'Princess', NULL)");

  executePlainSQL("
INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Tyrion', '32', 'M', 'Lannister', 'The Westerlands', NULL, 'Tyrion')");

  executePlainSQL("
INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Jon Snow', '32', 'M', 'Stark', 'The North', NULL, 'Ned Stark')");

  executePlainSQL("
INSERT INTO ROLE (ROName, Age, Gender, FAName, NAName, Title, Leader)
VALUES ('Tony Stark', '120', 'M', 'Stark', 'The North', NULL, 'Ned Stark')");

  // ========== army ===========================
  executePlainSQL("
INSERT INTO ARMY (ARName, amcapacity, soldiertype, expenditure, naname, roname)
VALUES ('Red Flame', '777', 'Archer', 80000, 'The Westerlands', 'Tyrion')");


  executePlainSQL("
INSERT INTO ARMY (ARName, amcapacity, soldiertype, expenditure, naname, roname)
VALUES ('Elite Force', '50', 'Cavalry', 8880000, 'Seven Kingdoms', 'Daenerys')");



  // ========== dragon ===========================
  executePlainSQL("
INSERT INTO DRAGON (drname, roname, drattack)
VALUES ('Guagua', 'Daenerys', 20000)");

  executePlainSQL("
INSERT INTO DRAGON (drname, roname, drattack)
VALUES ('Drogon', 'Daenerys', 230000)");

  executePlainSQL("
INSERT INTO DRAGON (drname, roname, drattack)
VALUES ('Khal', 'Daenerys', 100)");

  executePlainSQL("
INSERT INTO raise (roname, drname)
VALUES ('Daenerys', 'Guagua')");

  executePlainSQL("
INSERT INTO raise (roname, drname)
VALUES ('Daenerys', 'Drogon')");

  executePlainSQL("
INSERT INTO raise (roname, drname)
VALUES ('Daenerys', 'Khal')");

  // ============ arsenal ========================

  executePlainSQL("
INSERT INTO ARSENAL (arid, location, ascapacity, faname)
VALUES (1, 'Winterfell', 8000, 'Stark')");

  executePlainSQL("
INSERT INTO ARSENAL (arid, location, ascapacity, faname)
VALUES (2, 'Westeros', 8000, 'Targaryen')");

  executePlainSQL("
INSERT INTO ARSENAL (arid, location, ascapacity, faname)
VALUES (3, 'Vancouver', 800000, 'Lannister')");

  // ============= WEAPON =====================

  executePlainSQL("
  INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
  VALUES ('Atomic Bomb', 1, 2000, 30, 2, 'Jon Snow')");

  executePlainSQL("
  INSERT INTO produce (arid, weid)
  VALUES (2, 'Atomic Bomb')");

  executePlainSQL("
  INSERT INTO own (ROName, WEId)
  VALUES ('Jon Snow', 'Atomic Bomb')");

  executePlainSQL("
  INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
  VALUES ('Mini Gun', 1, 2000, 30, 2, 'Tony Stark')");

  executePlainSQL("
  INSERT INTO produce (arid, weid)
  VALUES (2, 'Mini Gun')");

  executePlainSQL("
  INSERT INTO own (ROName, WEId)
  VALUES ('Tony Stark', 'Mini Gun')");

  executePlainSQL("
  INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
  VALUES ('Straw', 20, 200000, 0, 2, 'Tony Stark')");

  executePlainSQL("
  INSERT INTO produce (arid, weid)
  VALUES (2, 'Straw')");

  executePlainSQL("
  INSERT INTO own (ROName, WEId)
  VALUES ('Tony Stark', 'Straw')");


  executePlainSQL("
INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('Mug', 20, 1, 1, 2, 'Ned Stark')");

  executePlainSQL("
INSERT INTO produce (arid, weid)
VALUES (2, 'Mug')");

  executePlainSQL("
INSERT INTO own (ROName, WEId)
VALUES ('Ned Stark', 'Mug')");


  executePlainSQL("
INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('Zibra', 20, 3030303, 509999, 2, 'Ned Stark')");

  executePlainSQL("
INSERT INTO produce (arid, weid)
VALUES (2, 'Zibra')");

  executePlainSQL("
INSERT INTO own (ROName, WEId)
VALUES ('Ned Stark', 'Zibra')");

  executePlainSQL("
  INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
  VALUES ('Xiao Gun Zi', 20, 8, 50, 2, 'Ned Stark')");

  executePlainSQL("
  INSERT INTO produce (arid, weid)
  VALUES (2, 'Xiao Gun Zi')");

  executePlainSQL("
  INSERT INTO own (ROName, WEId)
  VALUES ('Ned Stark', 'Xiao Gun Zi')");

  executePlainSQL("
INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('AK47', 20, 8888, 50, 2, 'Ned Stark')");

  executePlainSQL("
INSERT INTO produce (arid, weid)
VALUES (2, 'AK47')");

  executePlainSQL("
INSERT INTO own (ROName, WEId)
VALUES ('Ned Stark', 'AK47')");

  executePlainSQL("
INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('M416', 50, 8888888, 588880, 1, 'Theon')");

  executePlainSQL("
INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('Frying Pan', 0, 20, 50000, 1, 'Daenerys')");

  executePlainSQL("
INSERT INTO WEAPON (weid, welevel, weattack, defence, arid, roname)
VALUES ('Vibranium Shield', 20, 8888, 50, 3, 'Ned Stark')");

  executePlainSQL("
INSERT INTO produce (arid, weid)
VALUES (1, 'M416')");

  executePlainSQL("
INSERT INTO produce (arid, weid)
VALUES (1, 'Frying Pan')");

  executePlainSQL("
INSERT INTO produce (arid, weid)
VALUES (3, 'Vibranium Shield')");

  executePlainSQL("
INSERT INTO own (ROName, WEId)
VALUES ('Theon', 'M416')");

  executePlainSQL("
INSERT INTO own (ROName, WEId)
VALUES ('Daenerys', 'Frying Pan')");

  executePlainSQL("
INSERT INTO own (ROName, WEId)
VALUES ('Ned Stark', 'Vibranium Shield')");

  // ============== alliance ===================

  executePlainSQL("
INSERT INTO ALLIANCE (alname, alcapacity)
VALUES ('Avengers', 20)");

  executePlainSQL("
INSERT INTO ALLIANCE (alname, alcapacity)
VALUES ('The Frozen Shields', 20)");


  executePlainSQL("
INSERT INTO belong (rid, fid)
VALUES ('Ned Stark', 'Stark')");

  executePlainSQL("
INSERT INTO belong (rid, fid)
VALUES ('Daenerys', 'Targaryen')");

  executePlainSQL("
INSERT INTO belong (rid, fid)
VALUES ('Theon', 'Stark')");

  executePlainSQL("
INSERT INTO belong (rid, fid)
VALUES ('Alice', 'Bla')");

  executePlainSQL("
INSERT INTO belong (rid, fid)
VALUES ('Tyrion', 'Lannister')");

  executePlainSQL("
INSERT INTO isfrom (rid, nid)
VALUES ('Ned Stark', 'The North')");

  executePlainSQL("
INSERT INTO isfrom (rid, nid)
VALUES ('Daenerys', 'Seven Kingdoms')");

  executePlainSQL("
INSERT INTO isfrom (rid, nid)
VALUES ('Theon', 'The North')");

  executePlainSQL("
INSERT INTO isfrom (rid, nid)
VALUES ('Alice', 'Dorne')");

  executePlainSQL("
INSERT INTO isfrom (rid, nid)
VALUES ('Tyrion', 'The Westerlands')");

  // ==========manage===============
  executePlainSQL("
INSERT INTO manage (nid, fid)
VALUES ('The North', 'Stark')");

  executePlainSQL("
INSERT INTO manage (nid, fid)
VALUES ('Seven Kingdoms', 'Targaryen')");

  executePlainSQL("
INSERT INTO manage (nid, fid)
VALUES ('Dorne', 'Bla')");

  executePlainSQL("
INSERT INTO manage (nid, fid)
VALUES ('Iron Islands', 'Greyjoy')");

  executePlainSQL("
INSERT INTO manage (nid, fid)
VALUES ('The Westerlands', 'Lannister')");

  executePlainSQL("
INSERT INTO protect (ARName, NAName)
VALUES ('Red Flame', 'The Westerlands')");

  executePlainSQL("
INSERT INTO protect (ARName, NAName)
VALUES ('Elite Force', 'Seven Kingdoms')");

  executePlainSQL("
INSERT INTO lead (ROName, ARName)
VALUES ('Tyrion', 'Red Flame')");

  executePlainSQL("
INSERT INTO lead (ROName, ARName)
VALUES ('Daenerys', 'Elite Force')");

  executePlainSQL("
INSERT INTO have (FAName, ARId)
VALUES ('Stark', 1)");
  executePlainSQL("
INSERT INTO have (FAName, ARId)
VALUES ('Targaryen', 2)");
  executePlainSQL("
INSERT INTO have (FAName, ARId)
VALUES ('Lannister', 3)");

  executePlainSQL("
INSERT INTO join (ALName, FAName, Leader)
VALUES ('Avengers', 'Stark', 'Ned Stark')");

  executePlainSQL("
INSERT INTO join (ALName, FAName, Leader)
VALUES ('Avengers', 'Targaryen', 'Daenerys')");

  executePlainSQL("
INSERT INTO join (ALName, FAName, Leader)
VALUES ('The Frozen Shields', 'Tyrell', 'Theon')");

  executePlainSQL("
INSERT INTO join (ALName, FAName, Leader)
VALUES ('The Frozen Shields', 'Bla', 'Alice')");

  executePlainSQL("
INSERT INTO battle (alname_1, alname_2, winner)
VALUES ('Avengers', 'The Frozen Shields', 'Avengers')");


  OCICommit($db_conn);
}


?>



<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DB of Game of Thrones</title>
  <link rel="stylesheet" href="./layout/css/navbar.css">
  <link rel="stylesheet" href="./layout/css/welcome.css">
  <link rel="stylesheet" href="./layout/css/table.css">
  <link rel="stylesheet" href="./layout/css/footer.css">
  <link rel="stylesheet" href="./layout/css/leftbar.css">
  <link rel="stylesheet" href="./layout/css/rightbar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <section class="bar">
    <div class="items" id="home">
      <a href="./index.php#home" id="brandlabel">Game Of Thrones</a>
      <a href="./index.php#about">ABOUT</a>
      <a href="./index.php#contact">CONTACT</a>
      <a href="javascript:void(0);" class="icon" onclick="mobileExpand()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
  </section>

  <section class="leftbar">
    <div class="left_items" id="contact1">
      <a href="./role.php">ROLE</a>
      <a href="./army.php">ARMY</a>
      <a href="./weapon.php">WEAPON</a>
      <a href="./family.php">FAMILY</a>
      <a href="./nation.php">NATION</a>
      <a href="./alliance.php">ALLIANCE</a>
      <a href="./arsenal.php">ARSENAL</a>
      <a href="./dragon.php">DRAGON</a>
      <a href="javascript:void(0);" class="icon" onclick="mobileExpand()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
  </section>

  <section class="rightbar">
    <div class="right_items" id="contact2">
      <a href="./battle.php">BATTLE</a>
      <a href="./join.php">JOIN</a>
      <a href="./have.php">HAVE</a>
      <a href="./manage.php">MANAGE</a>
      <a href="./produce.php">PRODUCE</a>
      <a href="./belong.php">BELONG</a>
      <a href="./from.php">ISFROM</a>
      <a href="./protect.php">PROTECT</a>
      <a href="./own.php">OWN</a>
      <a href="./lead.php">LEAD</a>
      <a href="./raise.php">RAISE</a>
      <a href="javascript:void(0);" class="icon" onclick="mobileExpand()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
  </section>

  <section>

    <img src="https://user-images.githubusercontent.com/70420687/99182129-de12b180-26e7-11eb-9628-0a29324269e3.jpg" , width="1500">


  </section>




  <section class="tableblock" style="background-color: white;" id="contact">
    <h1>Group Members</h1>
    <div class="thetable">
      <table class="entities" style="width:100%">
        <tr>
          <th>Name</th>
          <th>Email Address</th>
        </tr>
        <tr>
          <td>Alice Zhang</td>
          <td>alicezhang3446@gmail.com</td>
        </tr>
        <tr>
          <td>Siwei Zhang</td>
          <td>siweiz@student.ubc.ca</td>
        </tr>
        <tr>
          <td>Kailun Jin</td>
          <td>ktaming123@gmail.com</td>
        </tr>
      </table>
    </div>
  </section>

  <section class='welcome' , style="background-color: white" , id="about">
    <h1>About</h1>
    <div class="content">
      This project is for CPSC304.

  </section>



  <section class="footer-container">
    <div class="footer">
      <h1>
        Thank you!
      </h1>
    </div>
  </section>

  <script>
    function mobileExpand() {
      var x = document.getElementById("thebar");
      if (x.className === "items") {
        x.className += " responsive";
      } else {
        x.className = "items";
      }
    }
  </script>
</body>

</html>