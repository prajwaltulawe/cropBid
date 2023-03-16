<?php
    session_start();
    $showError = false;
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $productName = htmlspecialchars($_POST['pname']);
        $productQty = htmlspecialchars($_POST['qty']); 
        $initialPrice = htmlspecialchars($_POST['basePrice']); 
        $productDesc = htmlspecialchars($_POST['description']); 
        $endDate = $_POST['endDate']; 
        $productImage = $_FILES['file'];
        $sellerId = $_SESSION['userid'];
        
        $productImageName = $productImage['name'];
        $profileImageTempName = $productImage['tmp_name'];
        $profileImageErr = $productImage['error'];

        $productImageNameExt = explode('.', $productImageName);
        $profileImageExt = strtolower(end($productImageNameExt));
        $validExt = array('png','jpg','jpeg');            

        if ( !in_array($profileImageExt, $validExt) ) {
            $showError = "Please insert a valid image file..!";            
        }
        else{
            $sellerImageFolderLocation = '../assets/products/'. $sellerId ;
            if (!file_exists($sellerImageFolderLocation)) {
                mkdir($sellerImageFolderLocation, 0777);
            }
            
            $productImgLocation = $sellerImageFolderLocation ."/" .$productImageName;                
            if ( !file_exists($productImgLocation) ) {
                move_uploaded_file($profileImageTempName, $productImgLocation);
            }
            else {
                $showError = "Image already  exists..! Try renaming current image.";
            }

            include '../partials/php/_dbconnect.php';
            $sellerDetailsQuery = "SELECT `address_state`, `address_city` FROM `users` WHERE `user_id` = $sellerId; ";
            $sellerDetails = mysqli_query($connectionquery, $sellerDetailsQuery);
            $sellerDetail = mysqli_fetch_assoc($sellerDetails);
            $sellerState = $sellerDetail['address_state'];
            $sellerCity = $sellerDetail['address_city']; 
            
            $insertProductSqlQuery = " INSERT INTO `products` 
            (`product_name`, `product_qty`, `initial_price`, `product_description`, `posted_on`, `end_date`, `product_image`, `seller_id`, `seller_state`, `seller_city`) VALUES
            ('$productName', '$productQty', '$initialPrice', '$productDesc','". date("Y-m-d") ."', '$endDate', '$productImgLocation', '$sellerId', '$sellerState', '$sellerCity');";
            print_r($insertProductSqlQuery);
            $result = mysqli_query($connectionquery, $insertProductSqlQuery);
            if ($result){
                header("location: orders.php");
            }
            else{
                $showError = "Opps..! Some error occurred. Please try again.";
            }
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
                <link rel='stylesheet' href='../partials/css/sell.css'>
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
                        <div class='menuitem active'>
                            <a href='sell.php'> SELL</a>
                        </div>
                        <div class='menuitem'>
                            <a href='../buy/buy.php'> BUY</a>
                        </div>
                        <div class='menuitem'>
                            <a href='orders.php'> ORDERS</a>
                        </div>
                        <div class='menuitem'>
                            <a href='../profile/profile.php'> PROFILE</a>
                        </div>
                        <div class='menuitem'>
                            <a href='../logout.php'> LOG OUT</a>
                        </div>
                    </div>
                </div>
                <div class='profile sell'>
                    <form action='' method='post' enctype='multipart/form-data' runat='server'>
                        <div class='image'>
                            <div class='profile-img'>
                                <img name='productImage' id='productImage' src='../partials/images/sample/sample-product.jpg' alt=''>
                            </div>
                            <input type='file' id='file' name='file' value='' onchange='productImagePreview()'>
                        </div>
                        <div class='form profile-info post'> 
                            <table>
                                <tr>
                                    <th> <span>Product Name :</span> </th>
                                    <td>
                                        <input type='text' name='pname' id='pname' required/>
                                    </td>
                                </tr>
                                <tr>
                                    <th> <span>Qty :</span> </th>
                                    <td>
                                        <input type='text' name='qty' id='qty' required/>
                                    </td>
                                </tr>
                                <tr>
                                    <th> <span>Initial Price :</span> </th>
                                    <td>
                                        <input type='number' name='basePrice' id='basePrice'  required/>
                                    </td>
                                </tr>
                                <tr>
                                    <th> <span>Description :</span> </th>
                                    <td> 
                                        <div class='desc'>
                                            <textarea name='description' id='description' cols='10' rows='2' minlength='45' required></textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th> <span>Bid End Date :</span> </th>
                                    <td> <input type='date' name='endDate' id='endDate' required/> </td>
                                </tr>
                            </table>
                            <hr> 
                            <table>
                                <tr>
                                    <td class='post'>
                                        <button type='submit' id='post'>POST PRODUCT</button>
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
