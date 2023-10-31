<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Instructor</title>

  <!-- <link rel="stylesheet" href="style.css"> -->
  <link rel="stylesheet" href="student-home.css">
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">

  <link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,600,800&display=swap" rel="stylesheet">
  <style>
    /* Style for the container div */
    .dark-mode-container {
      background-color: #353942;
      cursor: pointer;
      padding: 10px;
      border-radius: 10px;
      margin-top: 10px;
    }

    /* Style for the text span inside the container */
    .dark-mode-text {
      font-weight: bold;
      font-size: 14px;
      /* Make the text bold for emphasis */
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
  <?php
  $de = false;
  $sql = "SELECT * FROM HEAD WHERE Head = '";
  $sql .= $_SESSION["userid"] . "'";
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    $de = true;

  }
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
    <p style='font-size: 24px; margin-top: 10px;'><b style='font-size: 25px;'>Instructor</b></p>
    <div class="profile">
      <div class="top">
        <div class="info" style="text-align: center;">
          <?php
          $sql = "SELECT InstructorID, PersonID FROM INSTRUCTOR
            WHERE INSTRUCTOR.InstructorID = " . $_SESSION["userid"];
          $res = $conn->query($sql);

          $pid = -1;
          if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              $pid = $row['PersonID'];
            }
          }

          $sql = "SELECT * FROM PERSON WHERE PERSON.PersonID = " . $pid;
          $res = $conn->query($sql);

          if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              $name = $row['FirstName'] . " ";
              if ($row['MiddleName'] != "")
                $name .= $row['MiddleName'] . " ";
              $name .= $row['LastName'];
            }
          }

          echo "<p style='font-size: 24px;'>Hey, <b style='font-size: 28px;'>$name</b>";
          if ($de == true)
            echo "<b>(HOD)</b>";
          echo "</p>";
          echo '<small style="font-size: 18px;"> ID :- ';
          echo $_SESSION["userid"];
          echo "</small>";
          ?>
          <div>
            <?php
            $sql = "SELECT DEPARTMENT.DeptNo, DEPARTMENT.DeptName
            FROM INSTRUCTOR, DEPARTMENT
            WHERE INSTRUCTOR.InstructorID = " . $_SESSION["userid"] .
              " AND INSTRUCTOR.DeptNo = DEPARTMENT.DeptNo";
            $res = $conn->query($sql);

            if ($res->num_rows > 0) {
              while ($row = $res->fetch_assoc()) {
                echo "<b style='font-size: 18px;'>" . $row['DeptName'] . "</b>";

                // echo "<span>" . $row['DeptName'] . "</span>";
                $_SESSION["deptid"] = $row['DeptNo'];
              }
            }
            if ($de == true) {
              echo
                '<div class="dark-mode-container" onclick="location.href= ' . "'course-info.php'" . '">
  <span class="dark-mode-text">EDIT COURSES</span>
</div>';

            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>





  <?php
  $courses = array();

  $sql = "SELECT COURSE.CourseID, COURSE.CourseName, CCOUNT.Cnt, COURSE.ClassesTaken
          FROM COURSE, (
                        SELECT UNDERTAKES.CourseID, COUNT(UNDERTAKES.StudentID) AS Cnt
                        FROM COURSE, UNDERTAKES
                        WHERE COURSE.InstructorID = " . $_SESSION["userid"] .
    " AND COURSE.CourseID = UNDERTAKES.CourseID
                        GROUP BY UNDERTAKES.CourseID) CCOUNT
          WHERE COURSE.CourseID = CCOUNT.CourseID";
  $res = $conn->query($sql);

  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      array_push(
        $courses,
        array($row['CourseID'], $row['CourseName'], $row['Cnt'], $row['ClassesTaken'])
      );
    }
  }

  echo '<main style="display: flex; justify-content: space-around;">'; // This sets up a flex container for the cards
  
  for ($i = 0; $i < count($courses); $i++) {
    echo '
    <div class="subjects">
      <div class="eg" style="width: 400px; display: flex; flex-direction: column; justify-content: center; align-items: center;" id="' . $courses[$i][0] . '" onclick="send(' . $courses[$i][0] . ')">
        <h2>' . $courses[$i][0] . '</h2>
        <h2>' . $courses[$i][1] . '</h2>
        <h2>Total Students :- ' . $courses[$i][2] . '</h2>
        <h2>Total Classes :- ' . $courses[$i][3] . '</h2>
      </div>
    </div>
  ';
  }

  echo '</main>';


  ?>



  <script>
    function logout() {
      document.cookie = "courseid" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = "loggedin" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = "logout=yes";
      window.location.href = 'index.php';
    }

    function send(str) {
      document.cookie = "courseid=" + str.id;
      window.location.href = "instructor-data.php";
    }
  </script>

</body>

</html>