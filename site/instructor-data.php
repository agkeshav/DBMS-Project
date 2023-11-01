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
      margin-left: 447px;
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

  if (!isset($_COOKIE["courseid"]))
    echo "Enable Cookies";
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

    <form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" style="margin-top:-36px">




      <?php
      if (isset($_POST["clsInp"])) {
        $sql = "UPDATE COURSE SET COURSE.ClassesTaken = '" . $_POST["clsInp"] .
          "' WHERE COURSE.CourseId = '" . $_COOKIE["courseid"] . "'";
        $conn->query($sql);
      } else {
        // Handle the case where the required POST data is missing or undefined
        // TODO
      }

      $sql = "SELECT * FROM UNDERTAKES WHERE UNDERTAKES.CourseID = '" . $_COOKIE["courseid"];
      $sql .= "'ORDER BY UNDERTAKES.StudentID";
      $res = $conn->query($sql);

      $students = array();
      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          array_push($students, $row['StudentID']);
        }
      }

      for ($i = 0; $i < COUNT($students); $i++) {
        if (isset($_POST['cls' . $i]) && isset($_POST['pap' . $i]) && isset($_POST['int' . $i])) {
          $sql = "UPDATE UNDERTAKES SET Attendance = '";
          $sql .= $_POST['cls' . $i] . "', PaperMarks = '";
          $sql .= $_POST['pap' . $i] . "', InternalMarks = '";
          $sql .= $_POST['int' . $i] . "' WHERE StudentID = '" . $students[$i] . "'";
          $res = $conn->query($sql);
        } else {
          // Handle the case where the required POST data is missing or undefined
          // TODO
        }
      }

      $sql = "SELECT COURSE.CourseID, COURSE.CourseName, COURSE.ClassesTaken FROM COURSE
            WHERE COURSE.CourseID = '" . $_COOKIE["courseid"] . "'";
      $res = $conn->query($sql);

      $course = null;
      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $course = array($row['CourseID'], $row['CourseName'], $row['ClassesTaken']);
        }
      }
      echo '<main style="display: flex; justify-content: space-around;">
    <div class="subjects">
      <div class="eg" style="width: 400px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <h2>' . $course[0] . '</h2>
        <h2>' . $course[1] . '</h2>
        <main>
            <div class="subjects" style="display: flex; flex-direction:row; ">
              <div class="eg" style="margin-top:-17px">
                <h2>Classes</h2>
                <div style="display:flex;flex-direction:row; justify-content:space-around">
                <a href="#" class="float" onClick="decrClass()">
<i class="fa fa-minus my-float" ></i>
</a><h2 id="numClass">' . $course[2] . '</h2><input id="clsInp" type="hidden" name="clsInp" value="' . $course[2] . '"/><a href="#" class="float">
<i class="fa fa-plus my-float" onClick="incrClass()"></i>
</a>
                </div>
              </div>
            </div>
          </main>
      </div>
    </div>
  </main>
    ';

      ?>

      <div class="line"></div>

      <!-- <div class="ins-course-edit-label">
        <span>Internal</span>
        <span>Paper</span>
        <span>Classes</span>
      </div> -->

      <?php
      $sql = "SELECT *
            FROM UNDERTAKES, STUDENT, PERSON
            WHERE UNDERTAKES.CourseID = '" . $_COOKIE["courseid"];
      $sql .= "'AND UNDERTAKES.StudentID = STUDENT.StudentID
            AND PERSON.PersonID = STUDENT.PersonID
            ORDER BY STUDENT.StudentID";
      $res = $conn->query($sql);


      $students = array();
      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $name = $row['FirstName'] . " ";
          if ($row['MiddleName'] != "")
            $name .= $row['MiddleName'] . " ";
          $name .= $row['LastName'];
          array_push(
            $students,
            array($row['StudentID'], $name, $row['Attendance'], $row['PaperMarks'], $row['InternalMarks'])
          );
        }
      }
      echo '<main style="display: grid; grid-template-columns: auto auto;">';
      for ($i = 0; $i < COUNT($students); $i++) {
        echo '
    <div class="subjects" style="margin: 5px;">
      <div class="eg" style="width: 490px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <h2>' . $students[$i][0] . '</h2>
        <h2>' . $students[$i][1] . '</h2>
        <main>
            <div class="subjects" style="display: flex; flex-direction:row; ">
              <div class="eg" style="margin-top:-17px">
                <h2>Classes</h2>
                <div style="display:flex;flex-direction:row; justify-content:space-around">
                  <input type="text" name="cls' . $i . '" value="' . $students[$i][2] . '" maxlength="2" style="background-color:#202528; width:60px;font-size:30px;color:#fff;border-radius:10px;padding:7px" />

                </div>
              </div>
              <div class="eg" style="margin-top:-17px">
                <h3>Paper Marks</h3>
                <div style="display:flex;flex-direction:row; justify-content:space-around">
                <input type="text" name="pap' . $i . '" value="' . $students[$i][3] . '" maxlength="2" style="background-color:#202528; width:60px;font-size:30px;color:#fff;border-radius:10px;padding:7px;" />


                </div>
              </div>
              <div class="eg" style="margin-top:-17px">
                <h3>Internal Marks</h3>
                <div style="display:flex;flex-direction:row; justify-content:space-around">
 <input type="text" name="int' . $i . '" value="' . $students[$i][4] . '" maxlength="2" style="background-color:#202528; width:60px;font-size:30px;color:#fff;border-radius:10px;padding:7px" />
                </div>
              </div>
            </div>
          </main>
      </div>
    </div>
  
    ';
      }
      echo '</main>';
      ?>
      <input type="submit" value="SUBMIT" />

    </form>
  </div>



  <script>
    function logout() {
      document.cookie = "courseid" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = "loggedin" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = "logout=yes";
      window.location.href = 'index.php';
    }

    function incrClass() {
      var temp = document.getElementById("numClass").innerHTML;
      document.getElementById("numClass").innerHTML = parseInt(temp) + 1;
      document.getElementById("clsInp").value = parseInt(temp) + 1;
    }

    function decrClass() {
      var temp = document.getElementById("numClass").innerHTML;
      document.getElementById("numClass").innerHTML = parseInt(temp) - 1;
      document.getElementById("clsInp").value = parseInt(temp) - 1;
    }
  </script>

</body>

</html>