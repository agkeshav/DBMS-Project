<?php
session_start();
?>
<?php
$servname = "localhost";
$conn = new mysqli($servname, "root", "", "college_db");

if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

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
        <p style='font-size: 24px; margin-top: 10px;'><b style='font-size: 25px;'>MODIFY DEPARTMENTS</b></p>
    </div>
    <div class="container"
        style="display: flex; flex-direction: column;  justify-content: center; align-items: center;">


        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="admin-user-edit">
            <?php
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

            for ($i = 0; $i < COUNT($depts); $i++) {
                if (isset($_POST['insid' . $i]) && isset($_POST['dnm' . $i]) && isset($_POST['did' . $i])) {
                    $sql = "UPDATE DEPARTMENT SET DeptName = '";
                    $sql .= $_POST['dnm' . $i] . "' WHERE DeptNo = '" . $_POST['did' . $i] . "'";
                    $res = $conn->query($sql);

                    $sql = "UPDATE HEAD SET Head = '";
                    $sql .= $_POST['insid' . $i] . "' WHERE DeptNo = '" . $_POST['did' . $i] . "'";
                    $res = $conn->query($sql);
                    // echo $sql;
                } else {
                    // Handle the case where the required POST data is missing or undefined
                    // TODO
                }
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
                    $map[$row['DeptNo']] = $row['Head'];
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


            echo '<main style="display: grid; grid-template-columns: auto auto auto; margin-top:700px">';
            for ($i = 0; $i < COUNT($depts); $i++) {

                echo '
    <div class="subjects" style="margin: 5px;">
      <div class="eg" style="width: 490px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
       <div style="font-size:18px"> Dept. Id:- <input type="text" value="' . $depts[$i][0] . '" name="did' . $i . '" maxlength="5" style="background-color:#202528; width:160px;font-size:18px;color:#fff;border-radius:10px;padding:7px;margin-bottom:7px" /></div>
        <div style="font-size:18px"><input type="text" value="' . $depts[$i][1] . '" name="dnm' . $i . '" maxlength="31" style="background-color:#202528; width:360px;font-size:16px;color:#fff;border-radius:10px;padding:7px" /></div>
        <select class="rect-round-sm" name="insid' . $i . '" id="insid' . $i . '" style="background-color:#202528; width:360px;font-size:18px;color:#fff;border-radius:10px;padding:7px; margin-top:7px">';
                for ($j = 0; $j < COUNT($inslist); $j++) {
                    echo $map[$depts[$i][0]];
                    if ($inslist[$j] == $map[$depts[$i][0]])
                        echo '<option selected="selected" value="' . $inslist[$j] . ':-' . $inslist[$j] . '">' . $inslist[$j] . '</option>';
                    else
                        echo '<option value="' . $inslist[$j] . ':-' . $inslist[$j] . '">' . $inslist[$j] . '</option>';
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