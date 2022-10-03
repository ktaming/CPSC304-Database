<?php 
require_once "oci.php";
$isset = isset($_COOKIE['zyxwuser']) && isset($_COOKIE['zyxwpswd']);
?>



<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DB of Game of Thrones</title>
  <link rel="stylesheet" href="./layout/css/header.css">
  <link rel="stylesheet" href="./layout/css/welcome.css">
  <link rel="stylesheet" href="./layout/css/table.css">
  <link rel="stylesheet" href="./layout/css/footer.css">
 <link rel="stylesheet" href="./layout/css/bar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <section class="header">
      <div class="header_items" id="thebar">
      <a  id="brandlabel">DB_Game_of_Thrones</a>
      <a  href="./index.php">HOME</a> 
        <a href="./about.php">ABOUT</a>
        <a href="./contact.php">CONTACT</a>
      <a href="javascript:void(0);" class="icon" onclick="mobileExpand()">
        <i class="fa fa-bars"></i>
      </a> 
    </div>
  </section>

  <section class="bar">
      <div class="items" id="thebar">
        <a  href="./index.html./clogin.php">ROLE</a> 
        <a  href="./index.html./clogin.php">ARMY</a> 
        <a href="./index.html#about">WEAPON</a>
        <a href="./index.html#contact">FAMILY</a>
        <a  href="./index.html./clogin.php">NATION</a> 
        <a href="./index.html#about">ALLIANCE</a>
        <a  href="./index.html./clogin.php">ARSENAL</a> 
        <a href="./index.html#about">DRAGON</a>
      <a href="javascript:void(0);" class="icon" onclick="mobileExpand()">
        <i class="fa fa-bars"></i>
      </a> 
    </div>
  </section>

  <section class="bar">
      <div class="items" id="thebar">
        <a  href="./index.html./clogin.php">Belong</a> 
        <a  href="./index.html./clogin.php">From</a> 
        <a href="./index.html#about">Manage</a>
        <a href="./index.html#contact">Protect</a>
        <a  href="./index.html./clogin.php">Lead</a> 
        <a href="./index.html#about">Have</a>
        <a  href="./index.html./clogin.php">Own</a> 
        <a href="./index.html#about">Join</a>
        <a href="./index.html#about">Battle</a>
        <a href="./index.html#about">Produce</a>
        <a href="./index.html#about">Raise</a>
      <a href="javascript:void(0);" class="icon" onclick="mobileExpand()">
        <i class="fa fa-bars"></i>
      </a> 
    </div>
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
          <td>Kailun Jin</td>
          <td>ktaming123@gmail.com</td>
        </tr>
        <tr>
          <td>Siwei Zhang</td>
          <td>eleanorzsw@gmail.com</td>
        </tr>
      </table>
    </div>
  </section>

  <section class=map data-position=119>
    <iframe width=100% height=400 frameborder=0 style=border:0 src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC95r60uYUnB7WMJJu308Sz9PM1vo2NR1k&q=ICICS" allowfullscreen>
    </iframe>
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
