<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_REQUEST['username'];
  $password = $_REQUEST['password'];
  $password = md5($password);

  $servname = "localhost";
  $conn = new mysqli($servname, "root", "", "college_db");

  if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

  $sql = "SELECT StudentID, PassHash FROM STUDENT";
  $res = $conn->query($sql);

  $login = "none";
  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      if ($row['StudentID'] == $username && $password == $row['PassHash']) {
        $login = "student";
        break;
      }
    }
  }

  $sql = "SELECT InstructorID, PassHash FROM INSTRUCTOR";
  $res = $conn->query($sql);

  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      if ($row['InstructorID'] == $username && $password == $row['PassHash']) {
        $login = "instructor";
        break;
      }
    }
  }

  if ($username == '00000' && $password == md5("password"))
    $login = "admin";

  $conn->close();

  if ($login == "none") {
    echo
      '<script>
        document.getElementById("invalid-login").innerHTML = "Invalid User Id or Password";
      </script>';
    session_unset();
    session_abort();
  } else {
    echo
      '<script>
        document.getElementById("invalid-login").innerHTML = "";
      </script>';

    $_SESSION["userid"] = $username;

    if (isset($_POST["remember"]) && $_POST["remember"] == "on") {
      setcookie("loggedin", "yes");
    }

    if ($login == "admin") {
      $_SESSION["usertype"] = "admin";
      if (isset($_POST["remember"]) && $_POST["remember"] == "on")
        setcookie("usertype", "admin");

      header("Location: admin-home.php");
    } elseif ($login == "student") {
      $_SESSION["usertype"] = "student";
      if (isset($_POST["remember"]) && $_POST["remember"] == "on")
        setcookie("usertype", "student");

      header("Location: student-home.php");
    } elseif ($login == "instructor") {
      $_SESSION["usertype"] = "instructor";
      if (isset($_POST["remember"]) && $_POST["remember"] == "on")
        setcookie("usertype", "instructor");

      header("Location: instructor-home.php");
    }
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
  <link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,600,800&display=swap" rel="stylesheet">
</head>

<body>
  <?php
  if (isset($_COOKIE["logout"]) && $_COOKIE["logout"] == "yes") {
    session_unset();
    session_destroy();
    setcookie("logout", "", time() - 3600);
  }

  if (isset($_COOKIE["loggedin"])) {
    if ($_COOKIE["usertype"] == "student")
      header("Location: student-home.php");
    else if ($_COOKIE["usertype"] == "instructor")
      header("Location: instructor-home.php");
  }
  ?>
  <div id="app-mount" class="appMount-3lHmkl">

    <div style="position: fixed; opacity: 0; pointer-events: none;"></div>
    <div class="app-1q1i1E">
      <div class="wrapper-6URcxg">
        <div style="">
          <div>
            <form class="authBoxExpanded-2jqaBe authBox-hW6HRx theme-dark" method="post"
              action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">
              <div class="centeringWrapper-2Rs1dR">
                <div
                  class="flex-1xMQg5 flex-1O1GKY horizontal-1ae9ci horizontal-2EEEnY flex-1O1GKY directionRow-3v3tfG justifyStart-2NDFzi alignCenter-1dQNNs noWrap-3jynv6"
                  style="flex: 1 1 auto;">
                  <div class="mainLoginContainer-1ddwnR">
                    <h3 class="title-jXR8lp marginBottom8-AtZOdT fontDisplay-1dagSA base-1x0h_U size24-RIRrxO">Welcome
                      back!</h3>
                    <div class="colorHeaderSecondary-3Sp3Ft size16-1P40sf">We're so excited to see you again!</div>
                    <div class="block-egJnc0 marginTop20-3TxNs6">
                      <div class="marginBottom20-32qID7">
                        <h5 class="colorStandard-2KCXvj size14-e6ZScH h5-18_1nd title-3sZWYQ defaultMarginh5-2mL-bP">
                          USER ID</h5>
                        <div class="input-cIJ7To input-1CjGeR">
                          <div class="outerContainer-2pDY4c hidden-2l9u-8">
                            <div class="container-1uOKxa" style="width: 0px;">
                              <div class="innerContainer-20_g0H">
                                <div class="countryCode-2YakYv" aria-controls="popout_54" aria-expanded="false"
                                  role="button" tabindex="0">ID +62</div>
                                <div class="separator-39gxf1"></div>
                              </div>
                            </div>
                          </div>
                          <div class="inputWrapper-31_8H8 inputWrapper-3aw2Sf">
                            <input class="inputDefault-_djjkz input-cIJ7To inputField-4g7rSQ" name="username"
                              type="text" placeholder="user id" aria-label="Email or Phone Number" autocomplete="off"
                              maxlength="999" spellcheck="false" value="" id="user">
                          </div>
                        </div>
                      </div>
                      <div>
                        <h5 class="colorStandard-2KCXvj size14-e6ZScH h5-18_1nd title-3sZWYQ defaultMarginh5-2mL-bP">
                          Password</h5>
                        <div class="inputWrapper-31_8H8 marginBottom20-32qID7">
                          <input class="inputDefault-_djjkz input-cIJ7To" name="password" type="password"
                            placeholder="*****" aria-label="Password" autocomplete="off" maxlength="999"
                            spellcheck="false" value="" id="pass">
                        </div>
                      </div>

                      <button type="submit" value="LOGIN" class=" marginBottom8-AtZOdT button-3k0cO7 button-38aScr lookFilled-1Gx00P colorBrand-3pXr91
                        sizeLarge-1vSeWK fullWidth-1orjjo grow-q77ONN">
                        LOGIN
                      </button>
                      <div class="marginTop4-2BNfKC">
                        <input type="checkbox" name="remember">Remember Me
                        <span id="invalid-login"></span>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="verticalSeparator-3huAjp"></div> -->
                  <!-- <div class="transitionGroup-aR7y1d qrLogin-1AOZMt">

                  </div> -->
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

  </div>

</body>

</html>