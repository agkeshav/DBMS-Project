<?php
session_start();
?>
<?php
$servname = "localhost";
$conn = new mysqli($servname, "root", "", "college_db");

$utype = "inv";
$sql = "SELECT * FROM STUDENT";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
  while ($row = $res->fetch_assoc()) {
    if (isset($_POST['userid'])) {
      if ($row['StudentID'] == $_POST['userid']) {
        $utype = "student";
        break;
      }
    }
  }
}

if ($utype == "inv") {
  $sql = "SELECT * FROM INSTRUCTOR";
  $res = $conn->query($sql);

  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      if (isset($_POST['userid'])) {
        if ($row['InstructorID'] == $_POST['userid']) {
          $utype = "instructor";
          break;
        }
      }
    }
  }
}

if ($utype == "inv") {
  echo '<script>
      document.getElementById("invalid-user").innerHTML = "Invalid User ID";
    </script>';
} else {
  echo
    '<script>
      document.getElementById("invalid-user").innerHTML = "";
    </script>';

  $_SESSION["phchanged"] = "false";

  $_SESSION["userid"] = $_POST["userid"];

  if ($utype == "student")
    $_SESSION["usertype"] = "student";
  elseif ($utype == "instructor")
    $_SESSION["usertype"] = "instructor";

  header("Location: admin-edit.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Administrator</title>

  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="student-home.css">
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">

  <link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,600,800&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #222;
      /* Background color for the entire page in dark mode */
      color: #fff;
      /* Default text color in dark mode */
    }

    .card {
      width: 300px;
      background-color: #333;
      /* Card background color in dark mode */
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      margin: 20px auto;
      transition: 0.3s;
    }

    .card img {
      width: 100%;
      height: auto;
    }

    .card-content {
      padding: 20px;
    }

    .card-content h2 {
      font-size: 24px;
      margin: 0;
      color: #fff;
      /* Card title text color in dark mode */
    }

    .card-content p {
      font-size: 16px;
      margin: 10px 0;
    }

    .card-content a {
      display: block;
      text-align: center;
      text-decoration: none;
      background-color: #00a8e8;
      /* Link background color in dark mode */
      color: #fff;
      /* Link text color in dark mode */
      padding: 10px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .card-content a:hover {
      background-color: #007
    }

    <>.float {

      width: 30px;
      height: 30px;
      background-color: #0C9;
      color: #FFF;
      border-radius: 50px;
      text-align: center;
    }

    .my-float {
      margin-top: 10px;
    }

    input[type="submit"] {
      background-color: #333;
      /* Dark background color */
      color: #fff;
      /* White text color */
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      margin-top: 20px;
      margin-bottom: 20px;
    }

    /* Hover effect */
    input[type="submit"]:hover {
      background-color: #555;
      /* Darker background color on hover */
    }
  </style>
</head>

<body class="dark-theme">

  <header style="background-color:#222222">
    <div class="navbar">
      <div onClick="logout()" style="margin-top: 20px;">
        <span class="material-icons-sharp" onClick="logout()"></span>
        <h3>Logout</h3>
      </div>

    </div>
  </header>

  <div class="container" style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">
    <p style='font-size: 24px; margin-top: 10px;'><b style='font-size: 25px;'>ADMINISTRATOR</b></p>
  </div>

  <?php
  $meinwho = "";
  ?>
  <div class="container" style="display: flex; flex-direction: row;  justify-content: center; align-items: center;">
    <main>
      <div class="subjects">
        <div class="eg" style="align-items:center;display:flex;flex-direction:column; justify-content:center">
          <h2>STUDENT</h2>
          <div style="display:flex;flex-direction:row">
            <div style="display: flex;             
          justify-content: center;   
          align-items: center; justify-content: space-around; margin: 5px; ">
              <input type="submit" value="CREATE" />
            </div>
            <div style="display: flex;             
          justify-content: center;   
          align-items: center; justify-content: space-around; margin: 5px; ">
              <?php
              echo '<input type="submit" value="VIEW" onclick=send("student") />';
              ?>
            </div>
            <div style="display: flex;             
          justify-content: center;   
          align-items: center; ">
              <?php
              echo '<input type="submit" value="EDIT" onclick=sendedit("student") />';
              ?>
            </div>
          </div>
        </div>
      </div>
    </main>
    <main>
      <div class="subjects">
        <div class="eg" style="align-items:center;display:flex;flex-direction:column; justify-content:center">
          <h2>INSTRUCTOR</h2>
          <div style="display:flex;flex-direction:row">
            <div style="display: flex;             
          justify-content: center;   
          align-items: center; justify-content: space-around; margin: 5px; ">
              <input type="submit" value="CREATE" />
            </div>
            <div style="display: flex;             
          justify-content: center;   
          align-items: center; justify-content: space-around; margin: 5px; ">
              <?php
              echo '<input type="submit" value="VIEW" onclick=send("instructor") />';
              ?>
            </div>
            <div style="display: flex;             
          justify-content: center;   
          align-items: center; ">
              <?php
              echo '<input type="submit" value="EDIT" onclick=sendedit("instructor") />';
              ?>
            </div>
          </div>
        </div>
      </div>
    </main>
    <main>
      <div class="subjects">
        <div class="eg" style="align-items:center;display:flex;flex-direction:column; justify-content:center">
          <h2>DEPARTMENT</h2>
          <div style="display:flex;flex-direction:row">
            <div style="display: flex;             
          justify-content: center;   
          align-items: center; justify-content: space-around; margin: 5px; ">
              <input type="submit" value="CREATE" />
            </div>
            <div style="display: flex;             
          justify-content: center;   
          align-items: center; justify-content: space-around; margin: 5px; ">
              <?php
              echo '<input type="submit" value="VIEW" onclick=send("department") />';
              ?>
            </div>
            <div style="display: flex;             
          justify-content: center;   
          align-items: center; ">
              <?php

              echo '<input type="submit" value="EDIT" onclick=sendthis("department") />';

              ?>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>


  <?php


  echo '<div class="container"  style="display:none" id="toggle">
    <p style="margin-left:580px"><b style="font-size: 25px;">MODIFY DETAILS</b></p>
  </div>
  <form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" style="display:none" id="toggle1">
    <div style=" flex-direction:column;display:flex; margin-left:700px;margin-right:700px">
      <input type="text" name="userid" value="" placeholder="USER ID" maxlength="5" style="background-color:#202528;height:30px;width:150px;font-size:18px" />
      <input type="submit" value="SUBMIT" />
    </div>
  </form>';
  ?>

  <!-- <div class="container" style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">

    <div class="page-container">

      <div class="content-wrap">

        <p style='font-size: 24px; margin-top: 10px;'><b style='font-size: 25px;'>ADMINISTRATOR</b></p>


        <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="admin-buttons">
          <a href="admin-dept.php" class="btn-admin rect-circ">
            <span>EDIT HEADS</span>
          </a>

          <input type="text" class="rect-round-sm admin-uid" name="userid" value="" placeholder="USER ID" maxlength="5">

          <input type="submit" class="btn-admin rect-circ" value="EDIT USER" />

          <span id="invalid-user"></span>
  </form>

  

  </div>

  </div> -->

  <script>
    function logout() {
      document.cookie = "courseid" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = "loggedin" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = "logout=yes";
      window.location.href = 'index.php';
    }
    function send(str) {
      document.cookie = "name=" + str;
      window.location.href = "admin-view.php";
    }
    function sendedit(str) {
      document.cookie = "name=" + str;
      document.getElementById("toggle").style.display = "block";
      document.getElementById("toggle1").style.display = "block";
      // window.location.href = "admin-edit.php";
    }
    function sendthis(str) {
      // document.cookie = "name=" + str;
      // document.getElementById("toggle").style.display = "block";
      // document.getElementById("toggle1").style.display = "block";
      window.location.href = "admin-dept-edit.php";
    }
  </script>

</body>

</html>