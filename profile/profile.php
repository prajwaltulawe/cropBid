<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../partials/css/fonts.css" />
    <link rel="stylesheet" href="../partials/css/color.css" />
    <link rel="stylesheet" href="../partials/css/style.css" />
    <link rel="stylesheet" href="../partials/css/index.css" />
    <script src="../partials/js/script.js"></script>
    <title>Document</title>
</head>

<body>

    <div class="cointainer homepage">

        <div class="navbar">
            <div class="logo">
                <img src="../partials/images/logo/logo.png" alt="">
                <span> A G R O B I D</span>
            </div>
            <div class="menuitems">
                <div class="menuitem">
                    <a href="../sell/sell.php"> SELL</a>
                </div>
                <div class="menuitem">
                    <a href="../buy/buy.php"> BUY</a>
                </div>
                <div class="menuitem">
                    <a href="../buy/orders.php"> ORDERS</a>
                </div>
                <div class="menuitem active">
                    <a href="profile.php"> PROFILE</a>
                </div>
                <div class="menuitem">
                    <a href="../logout.php"> LOGOUT</a>
                </div>
            </div>
        </div>

<?php

session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) && $_SESSION['loggedin'] == true) {
    $userDetailsQuery = "SELECT * FROM `users` WHERE `user_id` = " . $_SESSION['userid'] . "; ";

    include '../partials/php/_dbconnect.php';
    $userDetails = mysqli_query($connectionquery, $userDetailsQuery);
    $userDetail = mysqli_fetch_assoc($userDetails);

    echo "
    <div class='profile'>
        <div class='image'>
            <div class='profile-img'>
                <img src='". $userDetail['user_profileImg'] ."' alt=''>
            </div>
        </div>
        <div class='form profile-info'>

            <form action='profile_edit.php'>
                <table>
                    <tr>
                        <th> <span>Name :</span> </th>
                        <td>
                            <span name='fname' id='fname'>" . $userDetail['first_name'] ." ". $userDetail['last_name'] ."</span>
                        </td>
                    </tr>
                    <tr>
                        <th> <span>Mobile Number :</span> </th>
                        <td>
                            <span text' id='cc'> +91 </span>
                            <span name='mobileNo' id='mobileNo'> &nbsp;" . $userDetail['mobile_no'] ." </span>
                        </td>
                    </tr>
                    <tr>
                        <th> <span>Email :</span> </th>
                        <td> <span name='email' id='email'>" . $userDetail['email_id'] ."</span> </td>
                    </tr>
                    <tr>
                        <th> <span>Address :</span> </th>
                        <td>
                            <span name='street' id='street'>" . $userDetail['address_street'] ."</span>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class='add'>
                                <span name='city' id='city'>" . $userDetail['address_city'] ."</span>
                                <span name='state' id='state'>" . $userDetail['address_state'] ."</span>
                                <span name='zip' id='zip'>" . $userDetail['address_zip'] ."</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th> <span>Password :</span> </th>
                        <td> <span name='password' id='password'> **** </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td id='changes'>
                            <button id='edit'>EDIT</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>";
} else {    header("location: ../login.php");   }

?>

    </div>    
</body>

</html>