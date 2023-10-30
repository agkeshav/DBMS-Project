<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Student</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="student-home.css">
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
  <link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,600,800&display=swap" rel="stylesheet">
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
      <div onClick="logout()">
        <span class="material-icons-sharp" onClick="logout()"></span>
        <h3>Logout</h3>
      </div>

    </div>
  </header>
  <div class="container" style="display: flex; flex-direction: column;  ">
    <div class="profile">
      <div class="top">
        <div class="info" style="text-align: center;">
          <?php
          $sql = "SELECT StudentID, PersonID FROM STUDENT
            WHERE STUDENT.StudentID = " . $_SESSION["userid"];
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
          echo "<p style='font-size: 24px;'>Hey, <b style='font-size: 28px;'>$name</b></p>";
          echo '<small style="font-size: 18px;"> ID :- ';
          echo $_SESSION["userid"];
          echo "</small>";
          ?>
        </div>
      </div>
    </div>
    <?php
    $courses = array();

    $sql = "SELECT * FROM UNDERTAKES, COURSE, DEPARTMENT
          WHERE UNDERTAKES.StudentID =" . $_SESSION["userid"] .
      " AND UNDERTAKES.CourseID = COURSE.CourseID
          AND DEPARTMENT.DeptNo = COURSE.DeptNo";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        array_push(
          $courses,
          array(
            $row['DeptName'],
            $row['CourseID'],
            $row['CourseName'],
            $row['ClassesTaken'],
            $row['Attendance'],
            $row['InternalMarks'],
            $row['PaperMarks']
          )
        );
      }
    }
    echo '<h1 style="display:inline;">Attendance</h1>';
    echo '<main style="display: flex; flex-direction: row;">
      ';
    for ($i = 0; $i < count($courses); $i++) {
      $total = $courses[$i][5] + $courses[$i][6];
      $grdpt = min(floor($total / 10) + 1, 10);

      if (intval($courses[$i][3]) > 0) {
        $attendancePercentage = round(intval($courses[$i][4]) / intval($courses[$i][3]) * 100);
      } else {
        $attendancePercentage = 0; // Set to 0 if the denominator is 0 to avoid division by zero.
      }
      echo '
      <div class="subjects" style=" margin-left:20px">
        <div class="eg">
          <span class="material-icons-sharp">architecture</span>
          <h2>' . $courses[$i][0] . '</h2>
           <h2>' . $courses[$i][1] . ' :- ' . $courses[$i][2] . '</h2>
          <h2>' . $courses[$i][4] . '/' . $courses[$i][3] . '</h2>
          <div class="progress">
            <svg>
              <circle cx="38" cy="38" r="36"></circle>
            </svg>
            <div class="number">
              <p>' . $attendancePercentage . '%</p>
            </div>
          </div>  
        </div>
        
      </div>
    ';

    }
    echo '</main>';
    echo '<h1>Marks</h1>';
    echo '<main style="display: flex; flex-direction: row;">
';
    for ($i = 0; $i < count($courses); $i++) {
      $total = $courses[$i][5] + $courses[$i][6];
      $grdpt = min(floor($total / 10) + 1, 10);


      echo '
      <div class="subjects" style="margin-top:35px; margin-left:20px">
        <div class="eg">
          <span class="material-icons-sharp">architecture</span>
          <h2>' . $courses[$i][0] . '</h2>
           <h2>' . $courses[$i][1] . ' :- ' . $courses[$i][2] . '</h2>
          <h3>Internal Marks:- ' . $courses[$i][5] . '</h3>
          <h3>Paper Marks:- ' . $courses[$i][6] . '</h3>
          <h3>Total Marks:- ' . $total . '</h3>
          <h3>Grade Point:- ' . $grdpt . '</h3>
          
        </div>
        
      </div>
    ';

    }
    echo '</main>';

    ?>

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