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


    // $utype = "inv";
    // $sql = "SELECT * FROM STUDENT";
    // $res = $conn->query($sql);
    
    // if ($res->num_rows > 0) {
    //     while ($row = $res->fetch_assoc()) {
    //         if (isset($_POST['userid'])) {
    //             if ($row['StudentID'] == $_POST['userid']) {
    //                 $utype = "student";
    //                 break;
    //             }
    //         }
    //     }
    // }
    
    // if ($utype == "inv") {
    //     $sql = "SELECT * FROM INSTRUCTOR";
    //     $res = $conn->query($sql);
    
    //     if ($res->num_rows > 0) {
    //         while ($row = $res->fetch_assoc()) {
    //             if (isset($_POST['userid'])) {
    //                 if ($row['InstructorID'] == $_POST['userid']) {
    //                     $utype = "instructor";
    //                     break;
    //                 }
    //             }
    //         }
    //     }
    // }
    

    ?>
    <header style="background-color:#222222">
        <div class="navbar">
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
        <p><b style="font-size: 25px;">STUDENT RECORD</b></p>
    </div>';
        $sql = "SELECT StudentID,PersonID FROM STUDENT";
        $res = $conn->query($sql);
        $students = array();
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $sid = $row['StudentID'];
                $pid = $row['PersonID'];

                $sql1 = "SELECT * FROM PERSON WHERE PERSON.PersonID = '" . $pid . "'";
                $res1 = $conn->query($sql1);

                $phone = array();
                $sql2 = "SELECT * FROM PHONE WHERE PHONE.PersonID = '" . $pid . "'";
                $res2 = $conn->query($sql2);
                if ($res2->num_rows > 0) {
                    // echo $res1->num_rows;
                    while ($row2 = $res2->fetch_assoc()) {

                        array_push(
                            $phone,
                            $row2['PhNo']
                        );
                    }
                }

                $i = 0;
                if ($res1->num_rows > 0) {
                    // echo $res1->num_rows;
                    while ($row1 = $res1->fetch_assoc()) {
                        $name = $row1['FirstName'] . " ";
                        if ($row1['MiddleName'] != "")
                            $name .= $row1['MiddleName'] . " ";
                        $name .= $row1['LastName'];
                        array_push(
                            $students,
                            array($sid, $name, $row1['Gender'], $phone[$i])
                        );
                        $i++;


                    }
                }


            }

        }

        ?>
        <?php
        echo '<main style="display: flex;flex-direction:column;">';
        for ($i = 0; $i < count($students); $i++) {
            echo '<div class="subjects" style="margin: 5px;">
        <div class="eg"
            style="display: flex; flex-direction: column; justify-content: flex-start; align-items: flex-start;margin:5px">
            <div style="display:flex;flex-direction:row">
                <h2>' . $students[$i][0] . ':- </h2>
                <h2>' . $students[$i][1] . '  </h2>';

            // Conditional part (outside of echo)
            if ($students[$i][2] == "M") {
                echo '<h2 style="margin-bottom:20px;margin-left:5px">(Male)</h2>';
            } else {
                echo '<h2 style="margin-bottom:20px;margin-left:5px">(Female)</h2>';
            }

            // Continue with the echo statement
            echo '</div>
        <h2>Phone:-' . $students[$i][3] . ' </h2>
        </div>
    </div>';
        }

        echo '</main>';
    } else if ($who == "instructor") {
        // echo $who;
        echo '<div class="container"
        style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">
        <p><b style="font-size: 25px;">INSTRUCTOR RECORD</b></p>
    </div>';
        $sql10 = "SELECT * FROM DEPARTMENT";
        $res10 = $conn->query($sql10);
        $mp = array();
        if ($res10->num_rows > 0) {
            while ($row = $res10->fetch_assoc()) {
                $mp[$row['DeptNo']] = $row['DeptName'];
            }
        }

        $sql11 = "SELECT * FROM HEAD";
        $res11 = $conn->query($sql11);
        $check = array();
        if ($res11->num_rows > 0) {
            while ($row = $res11->fetch_assoc()) {
                $check[$row['Head']] = $row['DeptNo'];
            }
        }


        $sql = "SELECT InstructorID,PersonID,DeptNo FROM INSTRUCTOR";
        $res = $conn->query($sql);
        $instructors = array();
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $iid = $row['InstructorID'];
                $pid = $row['PersonID'];

                $sql1 = "SELECT * FROM PERSON WHERE PERSON.PersonID = '" . $pid . "'";
                $res1 = $conn->query($sql1);

                $phone = array();
                $sql2 = "SELECT * FROM PHONE WHERE PHONE.PersonID = '" . $pid . "'";
                $res2 = $conn->query($sql2);
                if ($res2->num_rows > 0) {
                    // echo $res1->num_rows;
                    while ($row2 = $res2->fetch_assoc()) {

                        array_push(
                            $phone,
                            $row2['PhNo']
                        );
                    }
                }

                $i = 0;
                if ($res1->num_rows > 0) {
                    // echo $res1->num_rows;
                    while ($row1 = $res1->fetch_assoc()) {
                        $name = $row1['FirstName'] . " ";
                        if ($row1['MiddleName'] != "")
                            $name .= $row1['MiddleName'] . " ";
                        $name .= $row1['LastName'];
                        array_push(
                            $instructors,
                            array($iid, $name, $row1['Gender'], $phone[$i], $row['DeptNo'])

                        );

                        $i++;


                    }
                }



            }
        }
        ?>
            <?php
            echo '<main style="display: flex;flex-direction:column;">';
            for ($i = 0; $i < count($instructors); $i++) {
                echo '<div class="subjects" style="margin: 5px;">
                        <div class="eg" style="display: flex; flex-direction: column; justify-content: flex-start; align-items: flex-start;margin:5px">
                            <div style="display:flex;flex-direction:row">
                                <h2>' . $instructors[$i][0] . ':- </h2>
                                <h2>' . $instructors[$i][1] . '  </h2>';

                // Conditional part (outside of echo)
                if ($instructors[$i][2] == "M") {
                    echo '<h2 style="margin-bottom:20px;margin-left:5px">(Male)</h2>';
                } else {
                    echo '<h2 style="margin-bottom:20px;margin-left:5px">(Female)</h2>';
                }
                if (array_key_exists($instructors[$i][0], $check))
                    echo '<h2>  (  Head of Department ) </h2>';

                // Continue with the echo statement
                echo '</div>
                                    <h2>Phone:-' . $instructors[$i][3] . ' </h2>
                                    <h2>Department:- ' . $mp[$instructors[$i][4]] . ' </h2>
                                    </div>
                            </div>
                        </div>
                    </div>
                            ';
            }

            echo '</main>';





    } else if ($who == "department") {
        // echo $who;
        echo '<div class="container"
        style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">
        <p><b style="font-size: 25px;">DEPARTMENT RECORD</b></p>
    </div>';


        $sql = "SELECT DeptNo,DeptName FROM DEPARTMENT";
        $res = $conn->query($sql);
        $departments = array();
        $map = array();
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $did = $row['DeptNo'];
                // echo $did;
                array_push(
                    $departments,
                    array($did, $row['DeptName'])
                );
                $sql1 = "SELECT * FROM DEPARTMENT JOIN COURSE ON DEPARTMENT.DeptNo = COURSE.DeptNo WHERE DEPARTMENT.DeptNo = $did;";
                $res1 = $conn->query($sql1);
                if ($res1->num_rows > 0) {
                    while ($row1 = $res1->fetch_assoc()) {
                        $map[$row1['DeptName']][] = $row1['CourseID'];
                    }
                }

            }


        }
        $sql2 = "SELECT * FROM COURSE";
        $res2 = $conn->query($sql2);
        $map1 = array();
        if ($res2->num_rows > 0) {
            while ($row2 = $res2->fetch_assoc()) {
                $map1[$row2['CourseID']] = $row2['CourseName'];
            }
        }

        echo '<main style="display: flex;flex-direction:column;">';
        for ($i = 0; $i < count($departments); $i++) {
            echo '<div class="subjects" style="margin: 5px;">
        <div class="eg"
            style="display: flex; flex-direction: column; justify-content: flex-start; align-items: flex-start;margin:5px">
            <div style="display:flex;flex-direction:row">
                <h2>' . $departments[$i][0] . ':- </h2>
                <h2>' . $departments[$i][1] . '  </h2>
                
                
                
                <div class="custom-dropdown">
                <h2 style="margin-left:20px"> Courses </h2>
                    <div class="dropdown-content">';
            foreach ($map[$departments[$i][1]] as $value) {

                echo '<div class="dropdown-item">' . $value . ":-" . $map1[$value] . '</div>';



            }
            echo '</div>';
            echo '
  </div> ';

            echo '</div>
       
        </div>
    </div>';
        }

        echo '</main>';
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