<?php
session_start();
?>
<?php
$servname = "localhost";
$conn = new mysqli($servname, "root", "", "college_db");

if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

if (isset($_POST['fname']) && isset($_POST['mname']) && isset($_POST['lname']) && isset($_POST['ugend'])) {
    $sql = "UPDATE PERSON SET FirstName = '";
    $sql .= $_POST['fname'] . "', MiddleName = '";
    $sql .= $_POST['mname'] . "', LastName = '";
    $sql .= $_POST['lname'] . "', Gender = ";
    if ($_POST['ugend'] == 'Male')
        $sql .= "'M' ";
    elseif ($_POST['ugend'] == 'Female')
        $sql .= "'F' ";
    elseif ($_POST['ugend'] == 'Transgender')
        $sql .= "'T' ";
    $sql .= "WHERE PersonID = '" . $_SESSION["personid"] . "'";
    $conn->query($sql);
} else {
    // Handle the case where the required POST data is missing or undefined
    // TODO
}
?>
<?php
if (isset($_POST['insdept']) && $_SESSION["usertype"] == "instructor") {
    $sql = "SELECT DeptNo FROM DEPARTMENT WHERE DeptName = '";
    $sql .= $_POST['insdept'] . "'";
    $res = $conn->query($sql);

    $deptno = $res->fetch_assoc()['DeptNo'];

    $sql = "UPDATE INSTRUCTOR SET DeptNo = " . $deptno . " WHERE InstructorID = '";
    $sql .= $_SESSION["userid"] . "'";
    // echo $sql;
    $conn->query($sql);
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
            /* background-color: #222; */
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

        input[type="text"] {
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
        input[type="text"]:hover {
            background-color: #555;
            /* Darker background color on hover */
        }
    </style>
</head>

<body class="dark-theme">


    <header>
        <div class="navbar">
            <div onClick="logout()" style="margin-top: 20px;">
                <span class="material-icons-sharp" onClick="logout()"></span>
                <h3>Logout</h3>
            </div>

        </div>
    </header>
    <div class="container"
        style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">
        <p style='font-size: 24px; margin-top: 10px;'><b style='font-size: 25px;'>ADMINISTRATOR</b></p>
    </div>
    <div class="page-container" style="margin-top:-142px;margin-bottom:50px">

        <div class="content-wrap">


            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="admin-user-edit">


                <div class="user-type-label">
                    <?php
                    if ($_SESSION["usertype"] == "student")
                        echo '<span style="font-size: 20px;">STUDENT</span>';
                    elseif ($_SESSION["usertype"] == "instructor")
                        echo '<span style="font-size: 20px;">INSTRUCTOR</span>';
                    ?>
                </div>

                <?php
                $userarray = array();
                if ($_SESSION["usertype"] == "student") {
                    $sql = "SELECT StudentID, FirstName, MiddleName, LastName, Gender, STUDENT.PersonID
              FROM STUDENT, PERSON
              WHERE STUDENT.PersonID = PERSON.PersonID
              AND StudentID = '";
                    $sql .= $_SESSION["userid"] . "'";
                    $res = $conn->query($sql);

                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            $_SESSION["personid"] = $row['PersonID'];
                            array_push(
                                $userarray,
                                $row['StudentID'],
                                $row['FirstName'],
                                $row['MiddleName'],
                                $row['LastName'],
                                $row['Gender']
                            );
                        }
                    }
                } elseif ($_SESSION["usertype"] == "instructor") {
                    $sql = "SELECT InstructorID, FirstName, MiddleName, LastName, Gender, PERSON.PersonID
              FROM INSTRUCTOR, PERSON
              WHERE INSTRUCTOR.PersonID = PERSON.PersonID
              AND InstructorID = '";
                    $sql .= $_SESSION["userid"] . "'";
                    $res = $conn->query($sql);

                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            $_SESSION["personid"] = $row['PersonID'];
                            array_push(
                                $userarray,
                                $row['InstructorID'],
                                $row['FirstName'],
                                $row['MiddleName'],
                                $row['LastName'],
                                $row['Gender']
                            );
                        }
                    }
                }

                echo '<div class="user-person">
      <div class="rect-round-sm" style="font-size:18px">ID:- ' . $userarray[0] . '</div>
      <input type="text" name="fname" value="' . $userarray[1] . '" class="edit-id rect-round-sm" placeholder = "First Name">
      <input type="text" name="mname" value="' . $userarray[2] . '" class="edit-id rect-round-sm" placeholder = "Middle Name">
      <input type="text" name="lname" value="' . $userarray[3] . '" class="edit-id rect-round-sm" placeholder = "Last Name">
      
      <select name="ugend" class="rect-round-sm" style="background-color:#181A1E">';
                if ($userarray[4] == 'M')
                    echo '<option selected="selected">Male</option>';
                else
                    echo '<option>Male</option>';

                if ($userarray[4] == 'F')
                    echo '<option selected="selected">Female</option>';
                else
                    echo '<option>Female</option>';

                if ($userarray[4] == 'T')
                    echo '<option selected="selected">Transgender</option>';
                else
                    echo '<option>Transgender</option>';
                echo '</select>
    </div>';
                ?>

                <?php

                if ($_SESSION["usertype"] == "instructor") {
                    echo '<div class="label-dept"><span>DEPARTMENT:</span></div>
      <select style="background-color:#181A1E;font-size:18px;" class="user-dept-sel rect-round-sm" name="insdept >';

                    $departments = array();
                    $sql = "SELECT * FROM DEPARTMENT ORDER BY DeptNo";
                    $res = $conn->query($sql);

                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            array_push($departments, array($row['DeptNo'], $row['DeptName']));
                        }
                    }

                    $sql = "SELECT DeptNo FROM INSTRUCTOR WHERE InstructorID = '";
                    $sql .= $_SESSION["userid"] . "'";
                    $res = $conn->query($sql);

                    $deptno = 0;
                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            $deptno = $row['DeptNo'];
                        }
                    }

                    for ($i = 0; $i < COUNT($departments); $i++) {
                        if ($deptno == $departments[$i][0])
                            echo '<option selected="selected" name="insdept" value="' . $departments[$i][1] . '">' . $departments[$i][1] . '</option>';
                        else
                            echo '<option name="insdept" value="' . $departments[$i][1] . '">' . $departments[$i][1] . '</option>';
                    }
                    echo '</select>';
                }
                ?>
                <input type="submit" value="SUBMIT" class="btn-submit rect-circ" />

            </form>


        </div>

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