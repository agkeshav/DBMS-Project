<?php
session_start();
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
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

        .custom-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #202528;
            min-width: 460px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .custom-dropdown:hover .dropdown-content {
            display: block;
            cursor: pointer;
        }

        .dropdown-item {
            font-size: 20px;
            padding: 12px 16px;
            text-decoration: none;
            /* display: block; */
            cursor: pointer;
        }

        .dropdown-item:hover {
            cursor: pointer;

        }
    </style>
</head>

<body class="dark-theme">
    <?php
    $servname = "localhost";
    $conn = new mysqli($servname, "root", "", "college_db");
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);

    if (!isset($_COOKIE["name"]))
        echo "Enable Cookies";
    else
        $who = $_COOKIE["name"];



    ?>
    <header style="background-color:#222222">
        <div class="navbar">
            <a href="exam.html">
                <span class="material-icons-sharp" style="margin-top:15px">grid_view</span>
                <h3 style="margin-top:15px">Examination</h3>
            </a>
            <div onClick="logout()" style="margin-top: 20px;">
                <span class="material-icons-sharp" onClick="logout()"></span>
                <h3>Logout</h3>
            </div>

        </div>
    </header>



    <?php
    if ($who == "student") {
        echo '<div class="container"
        style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">
        <p style="font-size: 24px; margin-top: 10px;"><b style="font-size: 25px;">CREATE STUDENT</b></p>
        </div>';
        echo '<div class="container" style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">';
        echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" class="admin-user-edit" style="margin-top:60px;">';


        $pid = 0;
        $sql = "SELECT * FROM PERSON ORDER BY PersonID";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $pid = $row["PersonID"];
            }
        }
        $newpid = $pid + 1;

        $sid = 0;
        $sql = "SELECT * FROM STUDENT ORDER BY StudentID";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $sid = $row["StudentID"];
            }
        }
        $newsid = $sid + 1;


        if (isset($_POST['fname']) && isset($_POST['mname']) && isset($_POST['lname']) && isset($_POST['ph1']) && isset($_POST['ugend'])) {

            $fn = $_POST['fname'];
            $mn = $_POST['mname'];
            $ln = $_POST['lname'];
            $p1 = $_POST['ph1'];

            $gen = '';
            if ($_POST['ugend'][0] == "M")
                $gen = 'M';
            else if ($_POST['ugend'][0] == "F")
                $gen = 'F';
            else
                $gen = 'T';
            $sql = "INSERT INTO PERSON (PersonID, FirstName, MiddleName, LastName, Gender) VALUES ($newpid,'$fn','$mn','$ln','$gen')";
            $res = $conn->query($sql);

            if ($p1) {
                $sql = "INSERT INTO PHONE (PersonID, PhNo) VALUES ($newpid,'$p1')";
                $res = $conn->query($sql);
            }
            $pass = md5("pass1234");
            $sql = "INSERT INTO STUDENT (StudentID, PassHash, PersonID) VALUES ($newsid,'$pass',$newpid)";
            $res = $conn->query($sql);

        } else {
            // Handle the case where the required POST data is missing or undefined
            // TODO
        }

        echo '<main style="display: grid; grid-template-columns: auto auto auto; margin-top:400px">';

        echo '
        <div class="subjects" style="margin: 5px;">
        <div class="eg" style="width: 700px; display: flex;padding:60px; flex-direction: column; justify-content: center; align-items: center;">

            <div style="font-size:18px"><input type="text"  name="fname" maxlength="31" placeholder = "First Name" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px; margin:5px;" /></div>
            <div style="font-size:18px"><input type="text"  name="mname" maxlength="31" placeholder = "Middle Name" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px;margin:5px;" /></div>
            <div style="font-size:18px"><input type="text"  name="lname" maxlength="31" placeholder = "Last Name" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px;margin:5px;" /></div>
            <div style="font-size:18px"><input type="text"  name="ph1" maxlength="10" minlength="10" placeholder = "Phone1" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px;margin:5px;" /></div>
            
        <select name="ugend" class="rect-round-sm" style="background-color:#181A1E;padding:10px;margin:5px">';
        echo '<option selected="selected" >Male</option>';
        echo '<option>Female</option>';
        echo '<option>Transgender</option>';
        echo '</select>
        </div>

        </div>';

        echo '</main>';


        echo '<div style="display: flex;             
          justify-content: center;   
          align-items: center; ">
        <input type="submit" value="SUBMIT" />
    </div>

    </form>
    </div>
    ';

    } else if ($who == "instructor") {
        echo '<div class="container"
        style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">
        <p style="font-size: 24px; margin-top: 10px;"><b style="font-size: 25px;">CREATE INSTRUCTOR</b></p>
        </div>';
        echo '<div class="container" style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">';
        echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" class="admin-user-edit" style="margin-top:60px;">';


        $map = array();
        $sql = "SELECT * FROM DEPARTMENT";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $map[$row['DeptName']] = $row['DeptNo'];
            }
        }
        $pid = 0;
        $sql = "SELECT * FROM PERSON ORDER BY PersonID";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $pid = $row["PersonID"];
            }
        }
        $newpid = $pid + 1;

        $sid = 0;
        $sql = "SELECT * FROM INSTRUCTOR ORDER BY InstructorID";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $sid = $row["InstructorID"];
            }
        }
        $newsid = $sid + 1;


        if (isset($_POST['fname']) && isset($_POST['mname']) && isset($_POST['lname']) && isset($_POST['ph1']) && isset($_POST['ugend']) && isset($_POST['dept'])) {

            $fn = $_POST['fname'];
            $mn = $_POST['mname'];
            $ln = $_POST['lname'];
            $p1 = $_POST['ph1'];
            $dept = $map[$_POST['dept']];

            $gen = '';
            if ($_POST['ugend'][0] == "M")
                $gen = 'M';
            else if ($_POST['ugend'][0] == "F")
                $gen = 'F';
            else
                $gen = 'T';
            $sql = "INSERT INTO PERSON (PersonID, FirstName, MiddleName, LastName, Gender) VALUES ($newpid,'$fn','$mn','$ln','$gen')";
            $res = $conn->query($sql);

            if ($p1) {
                $sql = "INSERT INTO PHONE (PersonID, PhNo) VALUES ($newpid,'$p1')";
                $res = $conn->query($sql);
            }
            $pass = md5("1234pass");
            $sql = "INSERT INTO INSTRUCTOR (InstructorID, PassHash, PersonID,DeptNo) VALUES ($newsid,'$pass',$newpid,'$dept')";
            $res = $conn->query($sql);

        } else {
            // Handle the case where the required POST data is missing or undefined
            // TODO
        }

        echo '<main style="display: grid; grid-template-columns: auto auto auto; margin-top:400px">';

        echo '
        <div class="subjects" style="margin: 5px;">
        <div class="eg" style="width: 700px; display: flex;padding:60px; flex-direction: column; justify-content: center; align-items: center;">

            <div style="font-size:18px"><input type="text"  name="fname" maxlength="31" placeholder = "First Name" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px; margin:5px;" /></div>
            <div style="font-size:18px"><input type="text"  name="mname" maxlength="31" placeholder = "Middle Name" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px;margin:5px;" /></div>
            <div style="font-size:18px"><input type="text"  name="lname" maxlength="31" placeholder = "Last Name" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px;margin:5px;" /></div>
            <div style="font-size:18px"><input type="text"  name="ph1" maxlength="10" minlength="10" placeholder = "Phone1" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px;margin:5px;" /></div>
            
        <select name="ugend" class="rect-round-sm" style="background-color:#181A1E;padding:10px;margin:5px">';
        echo '<option selected="selected" >Male</option>';
        echo '<option>Female</option>';
        echo '<option>Transgender</option>';
        echo '</select>

        <div style="display:flex;flex-direction:row;justify-content:center;align-items:center">
        <h3>Assign Department:- </h3>
         <select name="dept" class="rect-round-sm" style="background-color:#181A1E;padding:10px;margin:5px">';
        foreach ($map as $key => $value) {
            echo '<option>' . $key . '</option>';
        }
        echo '</select>
        </div>
        
        </div>

        </div>';

        echo '</main>';


        echo '<div style="display: flex;             
          justify-content: center;   
          align-items: center; ">
        <input type="submit" value="SUBMIT" />
    </div>

    </form>
    </div>
    ';

    } else if ($who == "department") {
        echo '<div class="container"
        style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">
        <p style="font-size: 24px; margin-top: 10px;"><b style="font-size: 25px;">CREATE DEPARTMENT</b></p>
        </div>';
        echo '<div class="container" style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">';


        echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" class="admin-user-edit">';
        $depts = array();

        $sql = "SELECT * FROM DEPARTMENT";
        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                array_push(
                    $depts,
                    array($row['DeptNo'], $row['DeptName'])
                );
            }
        }

        if (isset($_POST['insid']) && isset($_POST['dnm'])) {
            $dnm = $_POST['dnm'];
            $insid = $_POST['insid'];
            $sql = "SELECT * FROM DEPARTMENT ORDER BY DeptNo";
            $res = $conn->query($sql);
            $ldid = 0;
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $ldid = $row['DeptNo'];
                }
            }

            $sql = "INSERT INTO DEPARTMENT (DeptNo, DeptName) VALUES ($ldid+1,'$dnm')";
            $res = $conn->query($sql);

            $sql = "INSERT INTO HEAD (DeptNo, Head) VALUES ($ldid+1,$insid)";
            $res = $conn->query($sql);

            $sql = "UPDATE INSTRUCTOR SET DeptNo = '";
            $sql .= $ldid + 1 . "' WHERE InstructorID = '" . $_POST['insid'] . "'";
            $res = $conn->query($sql);

            if ($res == TRUE) {
                echo 'Department Successfully Inserted';
            }

            echo $dnm;
            echo $insid;

        } else {
            // Handle the case where the required POST data is missing or undefined
            // TODO
        }


        $depts = array();

        $sql = "SELECT * FROM DEPARTMENT";
        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                array_push(
                    $depts,
                    array($row['DeptNo'], $row['DeptName'])
                );
            }
        }

        $headlist = array();
        $map = array();


        $sql = "SELECT * FROM HEAD";
        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                array_push($headlist, $row['DeptNo'], $row['Head']);
                $map[$row['Head']] = $row['DeptNo'];
            }
        }

        $inslist = array();


        $sql = "SELECT * FROM INSTRUCTOR";
        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                array_push($inslist, $row['InstructorID']);
            }
        }




        echo '<main style="display: grid; grid-template-columns: auto auto auto; margin-top:400px">';

        echo '
        <div class="subjects" style="margin: 5px;">
        <div class="eg" style="width: 700px; display: flex;padding:60px; flex-direction: column; justify-content: center; align-items: center;">

            <div style="font-size:18px"><input type="text"  name="dnm" maxlength="31" placeholder = "Enter Department Name" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px" /></div>
            <div style="display:flex;flex-direction:column;">
            <b style="margin-top:10px;font-size:15px;margin-bottom:10px;align-items:center;justify-content:center"> Assign HOD</b>
             <select class="rect-round-sm" name="insid" id="insid" style="background-color:#202528; width:360px;font-size:18px;color:#fff;border-radius:10px;padding:7px; margin-top:7px">';
        for ($j = 0; $j < COUNT($inslist); $j++) {
            if (!array_key_exists($inslist[$j], $map))
                echo '<option value="' . $inslist[$j] . '">' . $inslist[$j] . '</option>';
        }
        echo '</select>
            
            </div>
           
        
      </div>
      
    </div>
    
';

        echo '</main>';
        echo ' <div style="display: flex;             
          justify-content: center;   
          align-items: center; ">
        <input type="submit" value="SUBMIT" />
    </div>

    </form>
    </div>';

    }



    ?>














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