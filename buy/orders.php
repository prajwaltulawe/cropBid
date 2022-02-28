<?php
$showError = false;

session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) && $_SESSION['loggedin'] == true) {

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
            <link rel='stylesheet' href='https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css'>
            <link rel='stylesheet' href='../partials/css/sellOrders.css' />	
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
                        <div class='menuitem active-parent'>
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

                include '../partials/php/_dbconnect.php';
                $bidingDetailsQuery = "SELECT * FROM `orders` WHERE `buyer_id` = " . $_SESSION['userid'] . "; ";
                $bidingDetails = mysqli_query($connectionquery, $bidingDetailsQuery);

                if ($bidingDetails) {
                echo "
                <div class='profile sell'>
                    <div class='table'>
                        <table id='table_id' class=''>
                            <thead>
                                <tr>
                                    <th>    <span>  ORDER ID  </span> </th>
                                    <th>    <span>  NAME </span> </th>
                                    <th>    <span>  Qty. </span> </th>
                                    <th>    <span>  BASE PRICE </span> </th>
                                    <th>    <span>  YOUR BID </span> </th>
                                    <th>    <span>  END DATE </span> </th>
                                    <th>    <span>  STATUS </span> </th>
                                </tr>
                            </thead>
                            <tbody>";

                            while ($bidingRecord = mysqli_fetch_assoc($bidingDetails)) {
                            echo "
                            <tr>
                                <td> <form id='order". $bidingRecord['product_id'] ."' action='productDetails.php' method='GET'> 
                                        <input type='hidden' form='order". $bidingRecord['product_id'] ."' name='productId' value='". $bidingRecord['product_id'] ."' >
                                        <span class='align-center'>". $bidingRecord['product_id'] ."</span>
                                    </form>
                                </td>
                                <td>". $bidingRecord['product_name'] ."</td>
                                <td>". $bidingRecord['product_qty'] ."</td>
                                <td>". $bidingRecord['base_amount'] ."</td>
                                <td>". $bidingRecord['biding_amount'] ."</td>                                
                                <td>". $bidingRecord['end_date'] ."</td>                                
                                <td>    <button class='status";
                                if ( $bidingRecord['end_date'] >= date("Y-m-d") ) {
                                    echo " act' form='order". $bidingRecord['product_id'] ."' type='submit'> ACTIVE</button></td>";
                                } else {
                                    echo " expired' form='order". $bidingRecord['product_id'] ."' type='submit'> EXPIRED</button></td>";
                                }
                                echo " 
                                </tr>";
                            }
                            echo "
                            </tbody>
                        </table>
                    </div>
                </div>";
                }
                else{

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
}
else{
    header("location: ../login.php");
}
?>
