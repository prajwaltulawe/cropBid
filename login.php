<?php
    $error = false;
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if ($_POST['loginType'] == "email_id") { 
            $logInId = htmlspecialchars($_POST['email']); 
            $logInMethod = "email_id";
        }
        elseif ($_POST['loginType'] == "mobile_no") { 
            $logInId = htmlspecialchars($_POST['mobileNo']); 
            $logInMethod = "mobile_no";
        }
        $logInPassword = htmlspecialchars($_POST['password']); 

        include 'partials/php/_dbconnect.php';
        $isLogInIdValidQuery = "SELECT * FROM `users` WHERE $logInMethod = '$logInId';";
        $logInIdValidResult = mysqli_query($connectionquery, $isLogInIdValidQuery);
        $isValidId = mysqli_num_rows($logInIdValidResult);

        if ( $isValidId == 1 ){
            $resultRow = mysqli_fetch_assoc($logInIdValidResult);
            if ( password_verify($logInPassword, $resultRow['password'] ) ) {
                session_start();
                $_SESSION['loggedin'] = true ;
                $_SESSION['username'] = $resultRow['first_name'];
                $_SESSION['userid'] = $resultRow['user_id'];
                header("location: profile/profile_edit.php");
            }
            else {
                $error = "Incorrect Login Id or Password..!" ;
            }
        }
        else {
            $error = "Invalid Log in Id..!";
        }
    }   
?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="partials/css/fonts.css">
      <link rel="stylesheet" href="partials/css/color.css">
      <link rel="stylesheet" href="partials/css/style.css" />
      <link rel="stylesheet" href="partials/css/login.css" />
      <script src="partials/js/script.js"></script>
      <title>Document</title>
  </head>

  <body>     
        <div class="cointainer homepage">            
            <?php
            if($error){
            echo '<div class="alert failed" id="alert">
                <b>'. $error .'</b>    
                <button onclick="disableAlert()"> <b> x </b> </button> 
            </div>';
            }
            ?>            
            <div class="navbar">
                <div class="menuitems">
                    <div class="menuitem">
                        <a href="index.html"> HOME</a>
                    </div>
                    <div class="menuitem active">
                        <a href="login.php"> LOGIN</a>
                    </div>
                    <div class="menuitem">
                        <a href="signup.php"> SIGN UP</a>
                    </div>
                    <div class="menuitem">
                        <a href="aboutUs.html"> ABOUT US</a>
                    </div>
                </div>
            </div>
            <div class="logo login">
                <img src="partials/images/logo/logo.png" alt="">
                <span>  C R O P B I D</span>
            </div>
            <div class="form login">                   
                <form action="" method="post">
                    <select name="loginType" id="loginType" onchange="inputType()">
                        <option value="email_id" selected>Email Id</option>
                        <option value="mobile_no">Mobile No</option>
                    </select>
                    <div class="login-type" id="inputType"> 
                        <input type="email" name="email" id="email"  required/>
                    </div>
                    <div class="password">
                        <span>Password</span>
                        <input type="password" name="password" id="password" required/>
                    </div>
                    <button type="submit">Log In </button>       
                </form> 
            </div>
        </div>
  </body>
</html>