<?php
    session_start();
    include("include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Home | Electroholics</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="siteJavascript.js"></script>
    <style>
        .blurredBackgroundContainer {
            position: relative;
            text-align: center;
            color: #FFFFFF;
            height: 600px;
        }
        .textCenterAligned {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
        }
        .textBody {
            background-color: #444444;
            color: #FFFFFF;
            margin: 0;
            display: flex;
            flex-direction: column;
            padding-left: 20%;
            padding-right: 20%;
        }
        .indexTable {
            width: 100%;
            padding: 20px;
            border-collapse: collapse;
        }
        .indexTable th {
            background-color: #02134F;
            padding-top: 14px;
            padding-bottom: 14px;
        }
        .indexTable td {
            background-color: #D9D9D9;
            padding: 10px;
            text-align: center;
            box-sizing: border-box;
            color: #000000;
            transition: background-color 0.1s, color 0.1s;
        }
        .indexTable td:hover {
            background-color: #FAF0E6;
        }
        .indexTable td a {
            text-decoration: none;
            color: black;
        }
        .tableImage {
            width: 180px;
            height: 180px;
        }
        @media screen and (max-width: 600px) {
            .textCenterAligned {
                padding: 0px;
            }
            .textBody {
                padding-left: 5%;
                padding-right: 5%;
            }
            .tableImage {
                width: 90px;
                height: 90px;
            }
        }
    </style>
</head>

<body>
    <nav class="topnav" id="myTopnav">
        <a href="index.php" class="tab"><img src="images/websiteElements/siteElements/electroholicsLogo.png"><b> ELECTROHOLICS </b></a>
        <a href="index.php" class="active"><b>HOME</b></a>
        <a href="catalogueModule/processors.php" class="tab"><b>PRODUCTS</b></a>
        <?php
            if (isset($_SESSION["accountID"])) {    // if a user is logged in and a session is active
                $accountID = $_SESSION["accountID"];
                $accountEmail = $_SESSION["accountEmail"];
                $username = $_SESSION["username"];
                $accountRole = $_SESSION["accountRole"];

                if ($accountRole == 1) {    // if the logged in user is an admin, show tabs available only to admin side
                    echo "<a href='inventoryTrackingModule/storeInventory.php' class='tab'><b>STORE INVENTORY</b></a>";
                    echo "<a href='orderHistoryModule/orderHistoryAdmin.php' class='tab'><b>ALL ORDER HISTORY</b></a>";
                    echo "<a href='userProfileAndAccountModule/profile.php' class='tab'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
                    echo "<a href='userAuthenticationModule/logout.php' class='tabRight'><b>LOGOUT</b></a>";
                    // add more in the future as and when required
                }
                else if ($accountRole == 2) {   // otherwise, just show tabs available to the customer
                    // query for customer's cart
                    $cartQuery = "
                        SELECT COUNT(item_order.cartID) AS numberOfCartItems FROM item_order
                        JOIN cart on item_order.cartID = cart.cartID
                        JOIN user_profile ON cart.userID = user_profile.userID
                        WHERE user_profile.accountID = '$accountID' AND cart.isActive = 1;
                    ";
                    $result = mysqli_query($conn, $cartQuery);
                    $row = mysqli_fetch_assoc($result);
                    $numberOfCartItems = $row["numberOfCartItems"];

                    echo "<a href='shoppingCartModule/cart.php' class='tab'><i class='fa fa-shopping-cart'><b></i> My Cart ($numberOfCartItems items)</b></a>";
                    echo "<a href='userProfileAndAccountModule/profile.php' class='tab'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
                    echo "<a href='userAuthenticationModule/logout.php' class='tabRight'><b>LOGOUT</b></a>";
                }
            }
            else {  // if a session is not active
                echo "<a href='userAuthenticationModule/login.php' class='tabRight'><b>LOGIN</b></a>";
            }

        ?>
        <a href="javascript:void(0);" class="icon" onClick="adjustTopnav();"><i class="fa fa-bars"></i></a>
    </nav>

    <main>
        <div class="blurredBackgroundContainer">
            <img src="images/websiteElements/siteElements/indexBackground1.png" alt="pcBuild" style="width: 100%; height: 600px; object-fit: cover;">
            <div class="textCenterAligned">
                <br>
                <img src="images/websiteElements/siteElements/electroholicsLogo.png" alt="Logo" style="width: 40%; height: 40%;">
                <b><i>
                <p>Welcome to Electroholics, where technology meets passion!</p>
                <p>At Electroholics, We're more than just a PC store, we're a community of tech
                enthusiasts dedicated to fueling your love for all things electronic.</p>
                <p>Shop with us now!</p>
                </i></b>
                <br>
            </div>
        </div>
        <div class="textBody">
            <br>
            <table class="indexTable" id="tableFeaturedProducts">
                <tr>
                    <th colspan=3>Featured Products >></th>
                </tr>
                <?php
                    $getProductsQuery = "
                        SELECT *
                        FROM catalog_item
                        WHERE productType IN ('cpu', 'motherboards', 'gpu', 'ram', 'ssd', 'psu', 'cases', 'cooling', 'cables')
                        GROUP BY productType;
                    ";
                    $result = mysqli_query($conn, $getProductsQuery);
                    $numRows = mysqli_num_rows($result);
                    $tableRowsNeeded = ceil($numRows / 3);

                    for ($i = 0; $i < $tableRowsNeeded; $i++) {
                        echo "<tr>";
                        for ($j = 0; $j < 3; $j++) {
                            $row = mysqli_fetch_assoc($result);

                            if ($row) {
                                // since the productImagePaths have an "up one level" ../ thing in front, trim that away first
                                $trimmedProductImagePath = str_replace("../", "", $row["productImagePath"]);
                                $productName = $row["productName"];
                                $productIndex = $row["productIndex"];
                                // output the content of each cell
                                echo "<td>";
                                echo "<img class='tableImage' src='$trimmedProductImagePath'><br>";
                                echo "<a href='catalogueModule/viewProduct.php?id=$productIndex'>$productName</a>";
                                echo "</td>";
                            }
                        }
                        echo "</tr>";
                    }

                    mysqli_close($conn);
                ?>
            </table>
            <br>
        </div>
    </main>
    
    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>
</body>

</html>