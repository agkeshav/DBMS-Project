<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Courses</title>

  <link rel="stylesheet" href="student-home.css">
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">

  <link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,600,800&display=swap" rel="stylesheet">
  <style>
    .float {

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

  <?php
  $servname = "localhost";
  $conn = new mysqli($servname, "root", "", "college_db");

  if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
  ?>
  <header>
    <div class="navbar">
      <div onClick="logout()" style="margin-top: 20px;">
        <span class="material-icons-sharp" onClick="logout()"></span>
        <h3>Logout</h3>
      </div>

    </div>
  </header>

  <div class="container" style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">
    <p style='font-size: 24px; margin-top: 10px;'><b style='font-size: 25px;'>HEAD OF DEPARTMENT</b></p>
    <div>
      <?php
      $sql = "SELECT DEPARTMENT.DeptName
            FROM INSTRUCTOR, DEPARTMENT
            WHERE INSTRUCTOR.InstructorID = " . $_SESSION["userid"] .
        " AND INSTRUCTOR.DeptNo = DEPARTMENT.DeptNo";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          echo "<b style='font-size: 18px;'>" . $row['DeptName'] . "</b>";
        }
      }
      ?>
    </div>



    <form class="course-info-container" method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">

      <?php
      $courses = array();

      $sql = "SELECT COURSE.CourseID, COURSE.CourseName, COURSE.InstructorID FROM COURSE
            WHERE COURSE.DeptNo = " . $_SESSION["deptid"] . " ORDER BY COURSE.CourseID";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          array_push(
            $courses,
            array($row['CourseID'], $row['CourseName'], $row['InstructorID'])
          );
        }
      }

      for ($i = 0; $i < COUNT($courses); $i++) {
        if (isset($_POST['insid' . $i]) && isset($_POST['crsnm' . $i]) && isset($_POST['crsid' . $i])) {
          $sql = "UPDATE COURSE SET InstructorID = '";
          $sql .= $_POST['insid' . $i] . "', CourseName = '";
          $sql .= $_POST['crsnm' . $i] . "', CourseID = '";
          $sql .= $_POST['crsid' . $i] . "' WHERE CourseID = '" . $courses[$i][0] . "'";
          $res = $conn->query($sql);
        } else {
          // Handle the case where the required POST data is missing or undefined
          // TODO
        }
      }

      $courses = array();

      $sql = "SELECT COURSE.CourseID, COURSE.CourseName, COURSE.InstructorID FROM COURSE
            WHERE COURSE.DeptNo = " . $_SESSION["deptid"] . " ORDER BY COURSE.CourseID";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          array_push(
            $courses,
            array($row['CourseID'], $row['CourseName'], $row['InstructorID'])
          );
        }
      }

      $inslist = array();

      $sql = "SELECT * FROM INSTRUCTOR WHERE DeptNo = " . $_SESSION["deptid"];
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $sql1 = "SELECT * FROM PERSON WHERE PERSON.PersonID = " . $row['PersonID'];
          $res1 = $conn->query($sql1);

          if ($res1->num_rows > 0) {
            while ($row1 = $res1->fetch_assoc()) {
              $name = $row1['FirstName'] . " ";
              if ($row1['MiddleName'] != "")
                $name .= $row1['MiddleName'] . " ";
              $name .= $row1['LastName'];
            }
          }
          array_push($inslist, array($row['InstructorID'], $row['PersonID'], $name));
        }
      }
      echo '<main style="display: grid; grid-template-columns: auto auto auto;">';
      for ($i = 0; $i < COUNT($courses); $i++) {

        echo '
    <div class="subjects" style="margin: 5px;">
      <div class="eg" style="width: 490px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <input type="text" value="' . $courses[$i][0] . '" name="crsid' . $i . '" maxlength="5" style="background-color:#202528; width:160px;font-size:18px;color:#fff;border-radius:10px;padding:7px;margin-bottom:7px" />
        <input type="text" value="' . $courses[$i][1] . '" name="crsnm' . $i . '" maxlength="31" style="background-color:#202528; width:360px;font-size:18px;color:#fff;border-radius:10px;padding:7px" />
        <select class="rect-round-sm" name="insid' . $i . '" id="insid' . $i . '" style="background-color:#202528; width:360px;font-size:18px;color:#fff;border-radius:10px;padding:7px; margin-top:7px">';
        for ($j = 0; $j < COUNT($inslist); $j++) {
          if ($inslist[$j][0] == $courses[$i][2])
            echo '<option selected="selected" value="' . $inslist[$j][0] . ':-' . $inslist[$j][2] . '">' . $inslist[$j][0] . ':-' . $inslist[$j][2] . '</option>';
          else
            echo '<option value="' . $inslist[$j][0] . ':-' . $inslist[$j][2] . '">' . $inslist[$j][0] . ':-' . $inslist[$j][2] . '</option>';
        }
        echo '</select>
        
      </div>
      
    </div>
    
';
      }
      echo '</main>';
      ?>
      <div style="display: flex;             
          justify-content: center;   
          align-items: center; ">
        <input type="submit" value="SUBMIT" />
      </div>
    </form>


  </div>
  <script>
    function logout() {
      document.cookie = "courseid" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = "loggedin" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = "logout=yes";
      window.location.href = 'index.php';
    }
  </script>

</body>

</html>