<?php
    $showAlert = false;
    $showError = false;
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $fname = htmlspecialchars($_POST["fname"]);
        $lname = htmlspecialchars($_POST["lname"]);
        $mobno = htmlspecialchars($_POST["mobileNo"]);
        $email = $_POST["email"];
        $passwd = htmlspecialchars($_POST["password"]);
        
        $mailExistsQuery = "SELECT * FROM `users` WHERE mobile_no = '$mobno';";
        $phoneNoExistsQuery = "SELECT * FROM `users` WHERE email_id = '$email';";
        
        include 'partials/php/_dbconnect.php';
        $mailExists = mysqli_query($connectionquery, $mailExistsQuery);    
        $phoneNoExists = mysqli_query($connectionquery, $phoneNoExistsQuery);
        
        $mailExistsResult = mysqli_fetch_assoc($mailExists);
        $phoneNoExistsResult = mysqli_fetch_assoc($phoneNoExists);

        if( $mailExistsResult > 0){
            $showError = "Email Id is already Registered..!";
        }
        elseif( $phoneNoExistsResult > 0){
            $showError = "Phone No. is already Registered..!";
        }
        else{
            $passwordHash = password_hash($passwd, PASSWORD_DEFAULT);
            $addUserSqlQuery = "INSERT INTO `users` ( `first_name`, `last_name`, `mobile_no`, `email_id`, `password`, `user_profileImg`) 
                VALUES ('$fname', '$lname', '$mobno', '$email', '$passwordHash', '../partials/images/sample/profile.png');";
            $result = mysqli_query($connectionquery, $addUserSqlQuery);
            if ($result){
                $showAlert = true;
            }
            else{
                $showError = "Opps..! Some error occurred. Please try again.";
            }
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
      <link rel="stylesheet" href="partials/css/signup.css" />
      <script src="partials/js/script.js"></script>
      <title>Document</title>
  </head>

  <body>
      
        <div class="cointainer homepage">

        <?php
        if($showAlert){
        echo '<div class="alert sucess" id="alert">
            <b> Account Registered Successfully..! </b>    
            <button onclick="disableAlert()"> <b> x </b> </button> 
        </div>';
        }
        if($showError){
        echo '<div class="alert failed" id="alert">
            <b>'. $showError .'</b>    
            <button onclick="disableAlert()"> <b> x </b> </button> 
        </div>';
        }
        ?>
            
            <div class="navbar">
                <div class="menuitems">
                    <div class="menuitem">
                        <a href="index.html"> HOME</a>
                    </div>
                    <div class="menuitem">
                        <a href="login.php"> LOG IN</a>
                    </div>
                    <div class="menuitem active">
                        <a href="signup.php"> SIGN UP</a>
                    </div>
                    <div class="menuitem">
                        <a href="aboutUs.html"> ABOUT US</a>
                    </div>
                </div>
            </div>

            <div class="logo signup">
                <img src="partials/images/logo/logo.png" alt="">
                <span>  C R O P B I D</span>
            </div>

            <div class="form login">
                     
                <form action="" method="post">

                    <span>Name</span>
                    <div class="name">
                        <input type="text" name="fname" id="fname" placeholder="First Name" required/>
                        <input type="text" name="lname" id="lname" placeholder="Last Name" required/>
                    </div>

                    <span>Mobile Number</span>
                    <div class="number">
                        <input type="text" id="cc" value="+91" maxlength="3" size="4" readonly />
                        <input type="tel" maxlength="10" size="12" name="mobileNo" id="mobileNo" required/>
                    </div>

                    <span>Email</span>
                    <div class="email" id="inputType"> 
                        <input type="email" name="email" id="email"  required/>
                    </div>

                    <div class="password">
                        <span>Password</span>
                        <input type="password" name="password" id="password" required/>
                    </div>

                    <button type="submit">Sign In </button> 
                    
                </form> 

            </div>
        </div>
  </body>
</html>              