<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DB of Game of Thrones</title>
  <link rel="stylesheet" href="./layout/css/navbar.css">
  <!--   <link rel="stylesheet" href="./layout/css/table.css"> -->
  <link rel="stylesheet" href="./layout/css/footer.css">
  <link rel="stylesheet" href="./layout/css/leftbar.css">
  <link rel="stylesheet" href="./layout/css/rightbar.css">
  <link rel="stylesheet" href="./layout/css/command.css">
  <!--  <link rel="stylesheet" href="./layout/css/tabletest.css"> -->
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
      <a href="./family.php">FAMILY</a>
      <a href="./weapon.php">WEAPON</a>
      <a href="./army.php">ARMY</a>
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

  <section>

    <body>

      <div style="text-align: center;">

        <hr />
        <h2>Insert Tuple into Battle</h2>
        <form method="POST" action="battle.php">
          <!--refresh page when submitted-->
          <input type="hidden" id="insertbattleRequest" name="insertbattleRequest">
          ALNAME_1: <input type="text" name="insALNAME_1"> <br /><br />
          ALNAME_2: <input type="text" name="insALNAME_2"> <br /><br />
          Winner: <input type="text" name="insWinner"> <br /><br />
          <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>
        <hr />


  </section>

  <section>

    <body>
      <!-- <style type="text/css">
  h2{color:red}
  p{color:blue}
  widh{}
</style> -->


      <!--  <div class="command">
  <div class="center"> -->
      <div style="text-align: center;">
        <hr />

        <h2>Select/List/Display tuples from battle</h2>
        <form method="GET" action="battle.php">
          <!--refresh page when submitted-->
          <input type="hidden" id="displaybattleRequest" name="displaybattleRequest">

          <input type="submit" name="displaybattleTuples"></p>
        </form>

        <hr />
        <!-- </div>
  </div> -->


  </section>

</body>
</table>
</div>
</section>


<?php
//this tells the system that it's no longer just parsing html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False;
// set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())


if (isset($_POST['insertbattleRequest'])) {
  handlePOSTRequest();
} else if (isset($_GET['countTupleRequest']) || isset($_GET['displaybattleRequest'])) {
  handleGETRequest();
}


function handleDisplaybattleList()
{
  global $db_conn;
  $result1 = executePlainSQL("SELECT COUNT(*) FROM battle");

  if (($row = oci_fetch_row($result1)) != false) {
    echo "<br> The number of tuples in battle: " . $row[0] . "<br>";
  }

  $result2 = executePlainSQL("SELECT * from battle");
  echo "<br>Retrieved data from table battle:<br>";
  echo "<table>";
  echo "<tr>
            <th>ALName_1</th>
            <th>ALName_2</th>
            <th>Winner</th>
            </tr>";

  echo "</table>";

  // while ($row = oci_fetch_array($result2, OCI_BOTH)) {
  //    // echo "<tr><td>" . $row["FAName"] . "</td><td>" . $row["Flag"] . "</td><td>" . $row["Mascot"] . "</td></tr>"; 
  //   echo $row[0];
  // }
  while (($row = oci_fetch_row($result2)) != false) {
    echo $row[0] . "          " . $row[1] . "          " . $row[2] . "          " . $row[3] . "          " . $row[4] . "          " . $row[5] . "<br>\n";
  }
}


function tempDropbattle()
{
  global $db_conn;
  executePlainSQL("DROP TABLE battle");
  echo "<br> Dropped old battle table <br>";
}

function tempCreatebattle()
{
  global $db_conn;
  executePlainSQL("CREATE TABLE battle
      (
         ALName_1 CHAR(20),
         ALName_2 CHAR(20),
         Winner   CHAR(20),
         PRIMARY KEY (ALName_1, ALName_2),
         FOREIGN KEY (ALName_1) REFERENCES ALLIANCE (ALName),
         FOREIGN KEY (ALName_2) REFERENCES ALLIANCE (ALName)
      )");
  echo "<br> Created a new battle table <br>";
}



function handleInsertbattleRequest()
{
  global $db_conn;

  //Getting the values from user and insert data into the table
  $tuple = array(
    ":bind1" => $_POST['insALName_1'],
    ":bind2" => $_POST['insALName_2'],
    ":bind3" => $_POST['insWinner'],
  );

  $alltuples = array(
    $tuple
  );

  executeBoundSQL("insert into battle values (:bind1, :bind2, :bind3)", $alltuples);
  OCICommit($db_conn);
}



function debugAlertMessage($message)
{
  global $show_debug_alert_messages;

  if ($show_debug_alert_messages) {
    echo "<script type='text/javascript'>alert('" . $message . "');</script>";
  }
}

function executePlainSQL($cmdstr)
{
  //takes a plain (no bound variables) SQL command and executes it
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
  // $db_conn = OCILogon("ora_cazhang", "a34466326", "dbhost.students.cs.ubc.ca:1522/stu");

  // $db_conn = OCILogon("ora_ktaming", "a15815988", "dbhost.students.cs.ubc.ca:1522/stu");

  $db_conn = OCILogon("ora_siweiz", "a14668461", "dbhost.students.cs.ubc.ca:1522/stu");

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

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest()
{
  if (connectToDB()) {
    if (array_key_exists('insertbattleRequest', $_POST)) {
      // tempDropbattle();
      // 	tempCreatebattle();
      handleInsertbattleRequest();
      // This line is used for demonstration how Generic_battle_Example works. 你得先有个battle table，才能insert。
      //                } else if (array_key_exists('updateQueryRequest', $_POST)) {
      //                    handleUpdateRequest();
      //                } else if (array_key_exists('insertQueryRequest', $_POST)) {
      //                    handleInsertRequest();
    }

    disconnectFromDB();
  }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest()
{
  if (connectToDB()) {
    if (array_key_exists('displaybattleTuples', $_GET)) {
      handleDisplaybattleList();
      //                } else if (array_key_exists('countTuples', $_GET)) {
      //                    handleDisRequest();
    }

    disconnectFromDB();
  }
}

?>




//
<!--  <section class="footer-container">
 //    <div class="footer">
 //      <h1>
 //          Thank you!
 //      </h1>
 //    </div>
 //  </section> -->

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