<?php
$showError = false;

session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) && $_SESSION['loggedin'] == true) {
    
    if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {

        $productId = $_GET['productId'];
        include '../partials/php/_dbconnect.php';

        if (isset($_POST['amount'])) {

            $existingBidQuery = "SELECT `product_id`,`order_id` FROM `orders` WHERE `buyer_id` = " . $_SESSION['userid'] . "; ";
            $bidExists = mysqli_query($connectionquery,$existingBidQuery);
            if (mysqli_num_rows($bidExists) > 0) {
                $existingBid = mysqli_fetch_assoc($bidExists);
                $updateBidQuery = "UPDATE `orders` SET `biding_amount` = '".$_POST['amount']."' WHERE `orders`.`order_id` = ".$existingBid['order_id'].";";
                $updateBid = mysqli_query($connectionquery,$updateBidQuery);
            } else {
                $buyerDetailsQuery = "SELECT * FROM `users` WHERE `user_id` = " . $_SESSION['userid'] . "; ";
                $buyerDetails = mysqli_query($connectionquery, $buyerDetailsQuery);
    
                if ($buyerDetails) {
                    $buyerDetail = mysqli_fetch_assoc($buyerDetails);
    
                    $buyerId = $buyerDetail['user_id'];
                    $buyerImage = $buyerDetail['user_profileImg'];
                    $buyerName = $buyerDetail['first_name']." ".$buyerDetail['last_name'];
                    $buyerMobileNo = $buyerDetail['mobile_no'];
                    $buyerEmail = $buyerDetail['email_id'];
                    $buyerAddress = $buyerDetail['address_street'];
                    $buyerCity = $buyerDetail['address_city'];
                    $buyerState = $buyerDetail['address_state'];             
                    $biding_amount = $_POST['amount'];

                    $productEndDateQuery = "SELECT `end_date` FROM `products` WHERE `product_id` = $productId ;";
                    $productEndDate = mysqli_query($connectionquery, $productEndDateQuery);
                    $productEndDateResult = mysqli_fetch_assoc($productEndDate);
                    $endDate = $productEndDateResult['end_date'];

                    $insertBidQuery = "INSERT INTO `orders`(`product_id`,`buyer_id`, `buyer_image`, `buyer_name`, `buyer_mobileNo`, `buyer_email`, `buyer_address`, `buyer_city`, `buyer_state`, `biding_amount`, `end_date`) 
                    VALUES ($productId,$buyerId,'$buyerImage','$buyerName','$buyerMobileNo','$buyerEmail','$buyerAddress','$buyerCity','$buyerState','$biding_amount', '$endDate')";
                    $insertBid = mysqli_query($connectionquery,$insertBidQuery);
                
                }
            }

        }

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
                <link rel='stylesheet' href='../partials/css/productDetails.css'>	
                <link rel='stylesheet' href='https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css'>
                <link rel='stylesheet' href='../partials/css/sellOrders.css' />	
                <link rel='stylesheet' href='../partials/css/buyProductDetails.css'>
                <script src='../partials/js/script.js'></script>
                <script defer src='../partials/js/productDetaildataTable.js'></script>
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

                    echo "
                    <div class='navbar'>
                        <div class='logo'>
                            <img src='../partials/images/logo/logo.png' alt=''>
                            <span>  C R O P B I D</span>
                        </div>
                        <div class='menuitems'>
                            <div class='menuitem'>
                                <a href='../sell/sell.php'> SELL</a>
                            </div>
                            <div class='menuitem active'>
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
                    </div>";

                    $productsDetailsQuery = "SELECT * FROM `products` WHERE `product_id` = $productId; ";
                    $productDetails = mysqli_query($connectionquery, $productsDetailsQuery);
                    $productDetailsResult = mysqli_fetch_assoc($productDetails);

                    if ($productDetails) {
                
                        echo "
                        <div class='product-details'>
                            <div class='product'>
                                <div class='image'>
                                    <img src='". $productDetailsResult['product_image'] ."' alt='' srcset=''>
                                </div>
                                <div class='details'>
                                    <table>
                                        <tr>
                                            <th> Product Name : </th>
                                            <td> ". $productDetailsResult['product_name'] ." </td>
                                            <th> Qty : </th>
                                            <td> ". $productDetailsResult['product_qty'] ." </td>
                                        </tr>
                                        <tr>
                                            <th> Initial Price : </th>
                                            <td> ". $productDetailsResult['initial_price'] ." </td>
                                            <th> End Date : </th>
                                            <td> ". $productDetailsResult['end_date'] ." </td>
                                        </tr>
                                        <tr>
                                            <th> Description : </th>
                                            <td colspan='3'><p>". $productDetailsResult['product_description'] ."</p></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <form action='' method='POST'>
                                        <table>
                                            <tr>
                                                <th> PLACE BID :</th>
                                                <td colspan='3'>
                                                    <input type='number' name='amount' maxlength='8'> 
                                                    <span>RS/-</span> 
                                                    <button type='submit'>SUBMIT</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <div class='biding-details'>
                                <div class='table'>
                                    <table id='table_id' class=''>
                                        <thead>
                                            <tr>
                                                <th>    <span>  NAME </span> </th>
                                                <th class='address-width'><span>ADDRESS</span> </th>
                                                <th>    <span>  CITY </span> </th>
                                                <th>    <span>  STATE </span> </th>
                                                <th>    <span>  BIDING AMOUNT </span> </th>
                                            </tr>
                                        </thead>
                                    <tbody>";

                                    $bidingRecordsQuery = "SELECT `buyer_name`,`buyer_address`,`buyer_city`,`buyer_state`,`biding_amount` FROM `orders` WHERE `product_id` = $productId ORDER BY `biding_amount` DESC; ";
                                    $bidingRecords = mysqli_query($connectionquery, $bidingRecordsQuery);

                                    while ($bidRecord = mysqli_fetch_assoc($bidingRecords)){

                                        echo "
                                        <tr>
                                            <td>".$bidRecord['buyer_name']."</td>
                                            <td> <p id='address'>".$bidRecord['buyer_address']."</p></td>             
                                            <td>".$bidRecord['buyer_city']."</td>
                                            <td>".$bidRecord['buyer_state']."</td>
                                            <td>".$bidRecord['biding_amount']."</td>
                                        </tr>";
                                    }
                                    echo "
                                    </tbody>
                                </table>
                            </div>
                        </div>";
                    }

                echo "     
                </div>
            </body> 
        <script src='https://code.jquery.com/jquery-3.4.1.slim.min.js' integrity='sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n' crossorigin='anonymous'></script>
        <script type='text/javascript' charset='utf8' src='https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js'></script>
        </html>";
    }
    else {
        header("location: ../login.php");
    }
}
else{
    header("location: ../login.php");
}
?>
