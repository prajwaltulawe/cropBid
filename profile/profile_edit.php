<?php
    session_start();
    $showError = false;
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $profileImage = $_FILES['file'];
        $firstName = $_POST['fname']; 
        $lastName = $_POST['lname']; 
        $mobileNumber = $_POST['mobileNo']; 
        $email = $_POST['email']; 
        $addressStreet = $_POST['street'];
        $addressCity = $_POST['city']; 
        $addressState = $_POST['state']; 
        $addressZip = $_POST['zip']; 
        $passwd = $_POST['newPassword'];
        $current_passwd = $_POST['currentPassword'];
        
        include '../partials/php/_dbconnect.php';
        $currentUserQuery = "SELECT * FROM `users` WHERE `user_id` = " . $_SESSION['userid'] . "; ";
        $currentUserQueryResult =  mysqli_query($connectionquery, $currentUserQuery);
        $currentUserDetails = mysqli_fetch_assoc($currentUserQueryResult);

        if ( password_verify($current_passwd, $currentUserDetails['password']) ) {        

            $mailExistsQuery = "SELECT * FROM `users` WHERE mobile_no = '$mobileNumber';";
            $phoneNoExistsQuery = "SELECT * FROM `users` WHERE email_id = '$email';";
            
            $phoneNoExists = mysqli_query($connectionquery, $phoneNoExistsQuery);
            $mailExists = mysqli_query($connectionquery, $mailExistsQuery);    

            $phoneNoExistsResult = mysqli_fetch_assoc($phoneNoExists);
            $mailExistsResult = mysqli_fetch_assoc($mailExists);

            if( $phoneNoExistsResult > 0 && $phoneNoExistsResult['user_id'] != $_SESSION['userid']){
                $showError = "Phone No. is been already Used..!";            
            }
            
            elseif( $mailExistsResult > 0 && $mailExistsResult['user_id'] != $_SESSION['userid'] ){
                $showError = "Email Id is been already Used..!";
            }
            
            $userId = $_SESSION['userid'];
            if ( $profileImage['name'] != "") {
                $userProfileImgLocation = $currentUserDetails['user_profileImg'];
                $profileImageName = $profileImage['name'];
                $profileImageTempName = $profileImage['tmp_name'];
                $profileImageErr = $profileImage['error'];
    
                $profileImageNameExt = explode('.', $profileImageName);
                $profileImageExt = strtolower(end($profileImageNameExt));
                $validExt = array('png','jpg','jpeg');

                if ( !in_array($profileImageExt, $validExt) ) {
                    $showError = "Please insert a valid image file..!";            
                }
                else{

                    $userImageFolderLocation = '../assets/users/'. $currentUserDetails['user_id'] ;
                    if (!file_exists($userImageFolderLocation)) {
                        mkdir($userImageFolderLocation, 0777);
                    }
                    
                    $userProfileImgLocation = $userImageFolderLocation ."/" .$profileImageName;                
                    if ( !file_exists($userProfileImgLocation) ) {
                        move_uploaded_file($profileImageTempName, $userProfileImgLocation);
                    }
                    
                    if ($currentUserDetails['user_profileImg'] != "../partials/images/sample/profile.png") {
                        unlink($currentUserDetails['user_profileImg']);
                    }

                    $updateUserSqlQuery = " UPDATE `users` SET 
                    `first_name`= '$firstName',
                    `last_name`= '$lastName',
                    `mobile_no`= $mobileNumber,
                    `email_id`= '$email',
                    `address_street`= '$addressStreet',
                    `address_city`= '$addressCity',
                    `address_state`= '$addressState',
                    `address_zip`= $addressZip,
                    `user_profileImg` = '$userProfileImgLocation' 
                    WHERE `user_id` = $userId;";
                    
                    $result = mysqli_query($connectionquery, $updateUserSqlQuery);
                    if ($result){
                        header("location: profile.php");
                    }
                    else{
                        $showError = "Opps..! Some error occurred. Please try again.";
                    }
                }
            }

            else{
                if ($passwd == "") {
                    $updateUserSqlQuery = " UPDATE `users` SET 
                        `first_name`= '$firstName',
                        `last_name`= '$lastName',
                        `mobile_no`= $mobileNumber,
                        `email_id`= '$email',
                        `address_street`= '$addressStreet',
                        `address_city`= '$addressCity',
                        `address_state`= '$addressState',
                        `address_zip`= $addressZip
                        WHERE `user_id` = $userId;";
                }
                else {
                    $passwordHash = password_hash($passwd, PASSWORD_DEFAULT);
                    $updateUserSqlQuery = " UPDATE `users` SET 
                        `first_name`= '$firstName',
                        `last_name`= '$lastName',
                        `mobile_no`= $mobileNumber,
                        `email_id`= '$email',
                        `address_street`= '$addressStreet',
                        `address_city`= '$addressCity',
                        `address_state`= '$addressState',
                        `address_zip`= $addressZip,
                        `password`= '$passwordHash'
                        WHERE `user_id` = $userId;";   
                }

                $result = mysqli_query($connectionquery, $updateUserSqlQuery);
                if ($result){
                    header("location: profile.php");
                }
                else{
                    $showError = "Opps..! Some error occurred. Please try again.";
                }
            }
        }
        else {
            $showError = "Please enter correct password to save changes..!" ;
        }
    }

    if (isset($_SESSION['username']) && isset($_SESSION['userid']) && $_SESSION['loggedin'] == true) {
        
        include '../partials/php/_dbconnect.php';
        $userDetailsQuery = "SELECT * FROM `users` WHERE `user_id` = " . $_SESSION['userid'] . "; ";
        $userDetails = mysqli_query($connectionquery, $userDetailsQuery);
        $userDetail = mysqli_fetch_assoc($userDetails);

        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8' />
            <meta http-equiv='X-UA-Compatible' content='IE=edge' />
            <meta name='viewport' content='width=device-width, initial-scale=1.0' />
            <link rel='stylesheet' href='../partials/css/fonts.css' />
            <link rel='stylesheet' href='../partials/css/color.css' />
            <link rel='stylesheet' href='../partials/css/style.css' />
            <link rel='stylesheet' href='../partials/css/profileEdit.css' />
            <script src='../partials/js/script.js'></script>
            <title>Document</title>
        </head>
        <body>
            <div class='cointainer homepage'>";   
        
            if($showError){
            echo '<div class="alert failed" id="alert">
                    <b>'. $showError .'</b>    
                    <button onclick="disableAlert()"> <b> x </b> </button> 
                </div>';
            }
            
            echo"   
                <div class='navbar'>
                    <div class='logo'>
                        <img src='../partials/images/logo/logo.png' alt=''>
                        <span>  C R O P B I D</span>
                    </div>
                    <div class='menuitems'>
                        <div class='menuitem'>
                            <a href='../sell/sell.php'> SELL</a>
                        </div>
                        <div class='menuitem'>
                            <a href='../buy/buy.php'> BUY</a>
                        </div>
                        <div class='menuitem'>
                            <a href='../buy/orders.php'> ORDERS</a>
                        </div>
                        <div class='menuitem active'>
                            <a href='profile.php'> PROFILE</a>
                        </div>
                        <div class='menuitem'>
                            <a href='../logout.php'> LOGOUT</a>
                        </div>
                    </div>
                </div>
                <div class='profile'>
                    <form action='' method='post' enctype='multipart/form-data'>
                        <div class='image'>
                            <div class='profile-img'>
                                <img name='profileImage' id='profileImage' src='". $userDetail['user_profileImg'] ."' alt=''>
                            </div>
                            <input type='file' id='file' name='file'>
                        </div>
                        <div class='form profile-info'> 
                            <table>
                                <tr>
                                    <th> <span>Name :</span> </th>
                                    <td>
                                        <input type='text' name='fname' id='fname' placeholder='First Name' value='". $userDetail['first_name'] ."' />
                                        <input type='text' name='lname' id='lname' placeholder='Last Name' value='". $userDetail['last_name'] ."' />    
                                    </td>
                                </tr>
                                <tr>
                                    <th> <span>Mobile Number :</span> </th>
                                    <td>
                                        <input type='text' id='cc' value='+91' maxlength='3' size='4' readonly />
                                        <input type='tel' maxlength='10' size='12' name='mobileNo' id='mobileNo' value='". $userDetail['mobile_no'] ."' />
                                    </td>
                                </tr>
                                <tr>
                                    <th> <span>Email :</span> </th>
                                    <td> <input type='email' name='email' id='email' value='". $userDetail['email_id'] ."' /> </td>
                                </tr>
                                <tr>
                                    <th> <span>Address :</span> </th>
                                    <td> 
                                        <input type='text' name='street' id='street' placeholder='Street' value='". $userDetail['address_street'] ."' maxlength='50' />
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td>
                                        <div class='add'>
                                            <input type='text' name='city' id='city' placeholder='City' value='". $userDetail['address_city'] ."' />
                                            <input type='text' name='state' id='state' placeholder='State' value='". $userDetail['address_state'] ."' />
                                            <input type='text' name='zip' id='zip' placeholder='Zip' value='". $userDetail['address_zip'] ."' />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th> <span>New Password :</span> </th>
                                    <td> <input type='password' name='newPassword' id='newPassword' value=''/> </td>
                                </tr>
                            </table>
                            <hr> 
                            <table>
                                <tr>
                                    <th> <span> Password : </span> </th>
                                    <td class='changes'>
                                        <input type='password' name='currentPassword' id='currentPassword' value='' required/>
                                        <button type='submit' id='save'>SAVE CHANGES</button>
                                    </td>
                                </tr>
                            </table>                                 
                        </div>
                    </form>
                </div>
            </div>
        </body>
        </html>";

    }
    else {
        header("location: ../index.html");
    }
?>
