<?php
$showError = false;

session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) && $_SESSION['loggedin'] == true) {
    include '../partials/php/_dbconnect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $productId = $_POST['productDetail'];
        if (isset($_POST['_sold'])) {
            $markAsSoldQuery = "UPDATE `products` SET `end_date`= '". date('Y-m-d',strtotime('-1 day'))."' WHERE `product_id` = $productId; ";
            $markedAsSold = mysqli_query($connectionquery, $markAsSoldQuery); 
            if ($markedAsSold) {
                $showError = "Product marked as sold..!";
            } else {
                $showError = "Some error occoured please try again later.";
            }
        } else if (isset($_POST['_update'])) {
            $endDate = $_POST["endDate"];
            $updateEndDateQuery = "UPDATE `products` SET `end_date`= '$endDate' WHERE `product_id` = $productId; ";
            $updateEndDate = mysqli_query($connectionquery, $updateEndDateQuery); 
            if ($updateEndDate) {
                $showError = "End Date updated..!";
            } else {
                $showError = "Some error occoured please try again later.";
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
                        <div class='menuitem active-parent'>
                            <a href='sell.php'> SELL</a>
                        </div>
                        <div class='menuitem'>
                            <a href='../buy/buy.php'> BUY</a>
                        </div>
                        <div class='menuitem active'>
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
                                        <td> <input form='updateStatus' type='date' name='endDate' id='endDate' value='". $productDetailsResult['end_date'] ."' required/> </td>
                                    </tr>
                                    <tr>
                                        <th> Description : </th>
                                        <td colspan='3'><p> ". $productDetailsResult['product_description'] ." </p></td>
                                    </tr>
                                </table>
                                <hr>
                                <form id='updateStatus' action='' method='POST'>
                                    <table>
                                        <tr id='actions-wrapper'>
                                            <td> 
                                                <input type='hidden' name='productDetail' value='". $productDetailsResult['product_id'] ."' required/>
                                                <button type='submit' id='sold' name='_sold' value='sold'>Mark as Sold..!</button> 
                                                <button type='submit' id='update' name='_update' value='update'>Update Date</button>
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
                                            <th>    <span>  USER  </span> </th>
                                            <th>    <span>  NAME </span> </th>
                                            <th>    <span>  PHONE NO. </span> </th>
                                            <th>    <span>  EMAIL </span> </th>
                                            <th class='desc-width'><span>ADDRESS</span> </th>
                                            <th>    <span>  CITY </span> </th>
                                            <th>    <span>  STATE   </span> </th>
                                            <th>    <span>  BIDING AMOUNT </span> </th>
                                        </tr>
                                    </thead>
                                    <tbody>";

                                    $bidingRecordsQuery = "SELECT * FROM `orders` WHERE `product_id` = $productId ORDER BY `biding_amount` DESC; ";
                                    $bidingRecords = mysqli_query($connectionquery, $bidingRecordsQuery);

                                    while ($bidRecord = mysqli_fetch_assoc($bidingRecords)){

                                        echo "
                                        <tr>
                                            <td> <img id='buyerImage' src='".$bidRecord['buyer_image']."' alt=''> </td>
                                            <td>".$bidRecord['buyer_name']."</td>
                                            <td>".$bidRecord['buyer_mobileNo']."</td>
                                            <td>".$bidRecord['buyer_email']."</td>
                                            <td> <p id='address'>".$bidRecord['buyer_address']."</p></td>             
                                            <td>".$bidRecord['buyer_city']."</td>
                                            <td>".$bidRecord['buyer_state']."</td>
                                            <td>".$bidRecord['biding_amount']."</td>
                                        </tr>";
                                    }
                                    echo "
                                    <tr>
                                        <td><img src='' alt=''></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td> <p></p></td>             
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        </tr>
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
            <script>
                $(document).ready( function () {
                    $('#table_id').DataTable();
                } );
            </script>
        </html>";
    } else {
        header("location: ../login.php");
    }
}   else {
    header("location: ../login.php");
}
?>
