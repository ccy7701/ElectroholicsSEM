<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>Order History | Electroholics</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../siteJavascript.js"></script>
    <style>
        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .verticalMenu {
            background-color: #333;
            color: #fff;
            width: 15%;
            min-height: 100vh;
            float: left;
        }
        
        .verticalMenu ul {
            list-style-type: none;
            padding: 0;
        }

        .verticalMenu ul li {
            margin: 0;
            padding: 0;
        }

        .verticalMenu ul li a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            transition: background-color 0.3s;
            font-size: 18px;
        }

        .verticalMenu ul li a:hover {
            background-color: #555;
        }

        .verticalMenu a.active {
            background-color: #555;
            color: #fff;
        }

        .textBody {
            background-color: #444444;
            color: #FFFFFF;
            margin: 0;
            display: flex;
            flex-direction: column;
            padding-left: 8%;
            padding-right: 8%;
            min-height: 100vh;
            flex: 2;
            width: 85%;
        }

        .orderHistoryTable {
            width: 100%;
            padding-left: 5%;
            padding-right: 5%;
            border-collapse: collapse;
            color: black;
        }

        .orderHistoryTable th {
            background-color: #02134F;
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 18px;
            color: white;
        }

        .orderHistoryTable tr {
            background-color: #FFFFFF;
        }

        .orderHistoryTable img {
            width: 120px;
            height: 120px;
        }

        .orderHistoryTable td a {
            text-decoration: none;
            color: black;
        }

        .orderHistoryTable a:hover {
            font-weight: bold;
        }

        @media screen and (max-width: 960px) {
            .verticalMenu {
                width: 30%;
            }
            .textBody {
                width: 70%;
                padding-left: 5%;
                padding-right: 5%;
            }
        }

        @media screen and (max-width: 600px) {
            .verticalMenu {
                width: 100%;
                padding: 0;
                min-height: 0vh;
                display: float;
                float: center;
            }
            .textBody {
                flex: none;
                box-sizing: border-box;
                width: 100%;
                min-height: 80vh;
                padding-left: 4%;
                padding-right: 4%;
            }
            .orderHistoryTable {
                font-size: 13px;
                padding-left: 2%;
                padding-right: 2%;
            }
            .orderHistoryTable img {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>

<body>

    <nav class="topnav" id="myTopnav">
        <a href="../index.php" class="tab"><img src="../images/websiteElements/siteElements/electroholicsLogo.png"><b> ELECTROHOLICS </b></a>
        <a href="../index.php" class="tab"><b>HOME</b></a>
        <a href="../catalogueModule/processors.php" class="tab"><b>PRODUCTS</b></a>
        <?php
            if (isset($_SESSION["accountID"])) {    // if a user is logged in and a session is active
                $accountID = $_SESSION["accountID"];
                $accountEmail = $_SESSION["accountEmail"];
                $username = $_SESSION["username"];
                $accountRole = $_SESSION["accountRole"];

                if ($accountRole == 1) {    // if the logged in user is an admin, show tabs available only to admin side
                    echo "<a href='../inventoryTrackingModule/storeInventory.php' class='tab'><b>STORE INVENTORY</b></a>";
                    echo "<a href='../orderHistoryModule/orderHistoryAdmin.php' class='tab'><b>ALL ORDER HISTORY</b></a>";
                    echo "<a href='../userProfileAndAccountModule/profile.php' class='active'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
                    echo "<a href='../userAuthenticationModule/logout.php' class='tabRight'><b>LOGOUT</b></a>";
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

                    echo "<a href='../shoppingCartModule/cart.php' class='tab'><i class='fa fa-shopping-cart'><b></i> My Cart ($numberOfCartItems items)</b></a>";
                    echo "<a href='../userProfileAndAccountModule/profile.php' class='active'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
                    echo "<a href='../userAuthenticationModule/logout.php' class='tabRight'><b>LOGOUT</b></a>";
                }
            }
            else {  // if a session is not active
                echo "<a href='userAuthenticationModule/login.php' class='tabRight'><b>LOGIN</b></a>";
            }

        ?>
        <a href="javascript:void(0);" class="icon" onClick="adjustTopnav();"><i class="fa fa-bars"></i></a>
    </nav>

    <main style="flex: 1;">
        <div class="row">
            <div class="verticalMenu">
                <ul>
                    <div id="category-links">
                        <li style="display: block; text-align: center; padding-bottom: 20px; font-size: 20px;"><b>Menu</b></li>
                        <li><a href="../userProfileAndAccountModule/profile.php">Profile</a></li>
                        <?php
                            // the customer can see this Order History tab. the admin cannot.
                            if ($accountRole == 2) {
                                echo "<li><a href='../orderHistoryModule/orderHistory.php' class='active'>Order History</a></li>";
                            }
                        ?>
                    </div>
                </ul>
            </div>

            <div class="textBody">
                <br>

                <table class="orderHistoryTable">
                    <tr>
                        <th colspan="5">Order History</th>
                    </tr>

                    <tr style="background-color: #D9D9D9;">
                        <td colspan="5">&nbsp;</td>
                    </tr>

                    <?php
                        // get all the orders previously made by this customer's account
                        // this means, all carts under this customer where isActive = 0
                        $fetchPastCartsQuery = "
                            SELECT order_receipt.*, COUNT(*) AS numOfItems,
                            item_order.productIndex, item_order.orderQuantity, item_order.orderCost, 
                            catalog_item.productName, catalog_item.productID, catalog_item.productImagePath,
                            cart.totalCost, cart.cartID
                            FROM order_receipt
                            JOIN cart ON cart.cartID = order_receipt.cartID
                            JOIN item_order ON item_order.cartID = cart.cartID
                            JOIN catalog_item ON item_order.productIndex = catalog_item.productIndex
                            JOIN user_profile ON cart.userID = user_profile.userID
                            JOIN account ON account.accountID = user_profile.accountID
                            WHERE account.accountID = '$accountID'
                            GROUP BY order_receipt.orderID
                            ORDER BY order_receipt.orderDatetime DESC;
                        ";
                        $results = mysqli_query($conn, $fetchPastCartsQuery);
                        $numRows = mysqli_num_rows($results);
                        $rowIndex = 1;

                        while ($row = mysqli_fetch_assoc($results)) {
                            $orderID = $row["orderID"];
                            $cartID = $row["cartID"];
                            $orderDatetime = $row["orderDatetime"];
                            $productImagePath = $row["productImagePath"];
                            $productName = $row["productName"];
                            $productID = $row["productID"];
                            $orderQuantity = $row["orderQuantity"];
                            $orderCost = number_format($row["orderCost"], 2);
                            $numOfItems = $row["numOfItems"];
                            $totalCost = number_format($row["totalCost"], 2);

                            // the rows for the orderID and order datetime
                            echo "<tr>";
                            echo "<td colspan='2' style='padding-left: 10px; padding-top: 5px;'>Order ID: $orderID</td>";
                            echo "<td colspan='3'>&nbsp;</td>";
                            echo "</tr>";
                            echo "<tr style='border-bottom: 1px solid #828282'>";
                            echo "<td colspan='2' style='padding-left: 10px; padding-bottom: 5px;'>Date: $orderDatetime";
                            echo "<td colspan='3'>&nbsp;</td>";
                            echo "</tr>";

                            // the row with the first product and its details
                            echo "<tr style='border-bottom: 1px solid #828282;'>";
                            echo "<td style='text-align: center;'><img src='$productImagePath'></td>";
                            echo "<td><b>$productID</b><br>$productName<br>x$orderQuantity</td>";
                            echo "<td>&nbsp;</td>";
                            echo "<td>&nbsp;</td>";
                            echo "<td style='color: red;'><b>RM$orderCost</b></td>";
                            echo "</tr>";

                            // the row with the link to view detailed order history
                            echo "<tr>";
                            echo "<td colspan='2' style='padding-top: 5px; padding-bottom: 5px; padding-left: 10px;'>";
                            echo "<a href='orderHistoryDetailedCustomer.php?id=$cartID'>View all $numOfItems products in this order</a>";
                            echo "</td>";
                            echo "<td>&nbsp;</td>";
                            echo "<td>Order Total:</td>";
                            echo "<td style='color: red;'><b>RM$totalCost</b>";

                            // the darker row of empty space
                            echo "<tr style='background-color: #D9D9D9;'><td colspan='5'>&nbsp;</td></tr>";
                        }

                        mysqli_close($conn);
                    ?>
                </table>

                </br>
            </div>
        </div>
    </main>

    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>

</body>

</html>
