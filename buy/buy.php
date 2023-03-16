<?php
$showError = false;

session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) && $_SESSION['loggedin'] == true) {

    include '../partials/php/_dbconnect.php';

    $userDetailsQuery = "SELECT `address_state`, `address_city` FROM `users` WHERE `user_id` = " . $_SESSION['userid'] . "; ";
    $userDetails = mysqli_query($connectionquery, $userDetailsQuery);
    $userDetail = mysqli_fetch_assoc($userDetails);
    $filterState = $userDetail['address_state'];
    $filterCity = $userDetail['address_city'];
    $fromDate = date('Y-m-d',strtotime('-1 month'));
    $toDate = date("Y-m-d");

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
            <link rel='stylesheet' href='../partials/css/productDisplay.css'>
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
            <script src='../partials/js/script.js'></script>
            <script src='../partials/js/areaFilter.js'></script>
            <title>Document</title>
        </head>
        <body>
            <div class='cointainer homepage'>";   
    
                if($showError){
                echo "
                <div class='alert failed' id='alert'>
                    <b>'. $showError .'</b>    
                    <button onclick='disableAlert()'> <b> x </b> </button> 
                </div>";
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
                        <div class='menuitem active'>
                            <a href='buy.php'> BUY</a>
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

                $displayProductsQuery = "SELECT * FROM `products` 
                WHERE `end_date` >= CURRENT_DATE 
                AND `posted_on` BETWEEN '$fromDate' AND '$toDate' 
                AND `seller_id` != " . $_SESSION['userid'] . " 
                AND `seller_state` = '$filterState' 
                AND `seller_city` = '$filterCity'; ";
                
                $validRangeOfProducts = mysqli_query($connectionquery, $displayProductsQuery);

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $filterState = $_POST["filter-state"];
                    $filterCity = $_POST["filter-cities"];
                    $fromDate = $_POST["fromDate"];
                    $toDate = $_POST["toDate"];
                    $search = $_POST["search"];

                    $displayProductsQuery = "SELECT * FROM `products` 
                    WHERE `end_date` >= CURRENT_DATE 
                    AND `posted_on` BETWEEN '$fromDate' AND '$toDate' 
                    AND `seller_id` != " . $_SESSION['userid'] . " 
                    AND `seller_state` = '$filterState' 
                    AND `seller_city` = '$filterCity';";

                    if ($search != "" ) {
                        $displayProductsQuery = "SELECT * FROM `products` 
                        WHERE `end_date` >= CURRENT_DATE 
                        AND `posted_on` BETWEEN '$fromDate' AND '$toDate' 
                        AND `seller_id` != " . $_SESSION['userid'] . " 
                        AND `seller_state` = '$filterState' 
                        AND `seller_city` = '$filterCity' 
                        AND MATCH (`product_name`,`product_description`) against ('$search');";
                    }

                    $validRangeOfProducts = mysqli_query($connectionquery, $displayProductsQuery);
                
                }

                if ($validRangeOfProducts) {

                echo "  
                <div class='filter-options'>
                    <form action='' method='POST'>                    
                        <div class='filters-wrapper'>
                            <div class='area-filters' id='filter-area'>
                                <div class='filter-wrapper' >
                                    <span>Display Products from </span>
                                    <input list='filter-states' name='filter-state' id='filter-state' value='$filterState' placeholder='Select State' >
                                    <datalist id='filter-states'>
                                        <option value='AndhraPradesh'> Andhra Pradesh</option>
                                        <option value='ArunachalPradesh'>Arunachal Pradesh</option>
                                        <option value='Assam'>Assam</option>
                                        <option value='Bihar'>Bihar</option>
                                        <option value='Chhattisgarh'>Chhattisgarh</option>
                                        <option value='Goa'>Goa</option>
                                        <option value='Gujarat'>Gujarat</option>
                                        <option value='Haryana'>Haryana</option>
                                        <option value='HimachalPradesh'>Himachal Pradesh</option>
                                        <option value='Jharkhand'>Jharkhand</option>
                                        <option value='Karnatak'>Karnatak</option>
                                        <option value='Kerla'>Kerla</option>
                                        <option value='MadhyaPradesh'>Madhya Pradesh</option>
                                        <option value='Maharashtra'>Maharashtra</option>
                                        <option value='Manipur'>Manipur</option>
                                        <option value='Meghalaya'>Meghalaya</option>
                                        <option value='Mizoram'>Mizoram</option>
                                        <option value='Nagaland'>Nagaland</option>
                                        <option value='Odisha'>Odisha</option>
                                        <option value='Punjab'>Punjab</option>
                                        <option value='Rajasthan'>Rajasthan</option>
                                        <option value='Sikkim'>Sikkim</option>
                                        <option value='Tamil Nadu'>Tamil Nadu</option>
                                        <option value='Telangana'>Telangana</option>
                                        <option value='Tripura'>Tripura</option>
                                        <option value='UttarPradesh'>Uttar Pradesh</option>
                                        <option value='Uttarakhand'>Uttarakhand</option>
                                        <option value='WestBengal'>West Bengal</option>
                                    </datalist>
                                    <input list='filter-city' name='filter-cities' id='filter-cities' value='$filterCity' placeholder='Select City'>
                                    <datalist id='filter-city'>
                                    </datalist>
                                </div>    
                            </div>
                            <div class='filter-wrapper' id='filter-date'>
                                <span id='pf'>Posted from</span>
                                    <input type='date' name='fromDate' value='$fromDate'>
                                <span id='t'>to</span>
                                    <input type='date' name='toDate' value='$toDate' max=''>
                            </div>
                            <div class='filter-wrapper' id='filter-search'>
                                <span>Search :</span>
                                <input type='search' name='search'>
                                <button type='submit'>-></button>
                            </div>
                        </div>
                    </form>    
                </div>
                <hr> ";

                    echo
                    "<div class='products'>";

                    while( $product = mysqli_fetch_assoc($validRangeOfProducts)) {
                        echo "
                        <div class='product'>
                            <div class='details-wrapper'>
                                <div class='image'>
                                    <img src='". $product['product_image'] ."' alt='' srcset=''>
                                </div>
                                <div class='name'>
                                    <span> ". $product['product_name'] ."</span>
                                </div>
                                <div class='qty-price'>
                                    <span>". $product['product_qty'] ."</span>
                                    <span>". $product['initial_price'] ." /-</span>
                                </div>
                                <div class='desc'>
                                    <span>";
                                
                                if ( strlen( $product['product_description']) > 45 ) { echo substr($product['product_description'], 0, 45). "...</span>"; } 
                                else { echo $product['product_description'] ."...</span>"; }

                                echo "
                                </div>
                                <div class='location'>
                                    <span>". $product['seller_city'] ."</span>
                                    <span>". $product['seller_state'] ."</span>
                                </div>
                            </div>
                            <div class='button'>
                            <form action='productDetails.php' method='GET'>
                                <input type='text' name='productId' value='". $product['product_id'] ."' hidden>
                                <button type='submit'>VIEW DETAILS</button>
                            </form>
                            </div>
                        </div>";
                    }
                    echo    
                    "</div>";
                }
                else{ }
                echo "
            </div>
        </body>
    </html>";
}
else{
    header("location: ../login.php");
}
?>
