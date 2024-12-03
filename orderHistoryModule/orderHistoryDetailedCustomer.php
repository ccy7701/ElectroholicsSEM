<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Order History | Electroholics</title>
    <title>Catalogue | Electroholics</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/catalogueStyle.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../siteJavascript.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .orderHistoryTable {
            width: 100%;
            border-collapse: collapse;
            background-color: #D9D9D9;
        }
        .orderHistoryTable .item {
            background-color: #FFFFFF;
            color: black;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        .orderHistoryTable .title {
            background-color: #01234F;
            color: white;
            text-align: center;
            font-size: 24px;
        }
        .orderHistoryTable td {
            padding-left: 10px;
            padding-right: 10px;
        }
        .itemRow {
            background-color: #FFFFFF;
            color: black;
            padding: 10px;
            align-items: center;
        }
        .lastRow {
            background-color: #FFFFFF; 
            width: 5%;
            color: black;
            padding: 10px;
            text-align: left;
            align-items: center;
        }
        .back-link {
            text-decoration: none;
            color: white;
            font-size: 18px;
            font-weight: normal;
        }
        .back-link:hover {
            font-weight: bold;
        }
        .button {
            background-color: #008000;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            text-align: center;
            float: right;
            margin: 10px;
            transition: 0.1s background-color;
        }
        .button:hover {
            cursor: pointer;
            background-color: #00AA00;
        }

        @media screen and (max-width: 600px) {
            .orderHistoryTable td {
                font-size: 14px;
                padding-left: 4px;
                padding-right: 4px;
            }
            .item {
                font-size: 14px;
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
                    echo "<a href='../inventoryTrackingModule/#' class='tab'><b>STORE INVENTORY</b></a>";
                    echo "<a href='../userProfileAndAccountModule/myAccount.php' class='tab'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
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
                $accountRole = 0;   // assume that 0 means that the user is not logged in
                echo "<a href='../userAuthenticationModule/login.php' class='tabRight'><b>LOGIN</b></a>";
            }

        ?>
        <a href="javascript:void(0);" class="icon" onClick="adjustTopnav();"><i class="fa fa-bars"></i></a>
    </nav>

    <main style="flex: 1">
        <div class="row">
            <div class="textBody">
                <br><br>
                <div class="name">
                    <table class="orderHistoryTable">
                        <?php
                            // get all items in this cart based on the cartID passed to here
                            if (isset($_GET["id"]) && $_GET["id"] != "") {
                                $id = $_GET["id"];
                                $accountID = $_SESSION["accountID"];
                                $fetchCartQuery = "
                                    SELECT
                                    order_receipt.orderID, order_receipt.orderDatetime
                                    FROM order_receipt
                                    JOIN cart ON order_receipt.cartID = cart.cartID
                                    JOIN item_order ON cart.cartID = item_order.cartID
                                    JOIN catalog_item ON item_order.productIndex = catalog_item.productIndex
                                    JOIN user_profile ON user_profile.userID = cart.userID
                                    WHERE user_profile.accountID = '$accountID' AND cart.cartID = '$id';
                                ";
                                $results = mysqli_query($conn, $fetchCartQuery);
                                $numRows = mysqli_num_rows($results);

                                $row = mysqli_fetch_assoc($results);
                                $orderID = $row["orderID"];
                                $orderDatetime = $row["orderDatetime"];
                            }
                        ?>
                        <tr>
                            <th class="title" colspan="5">Order History</th>
                        </tr>
                        <tr style="background-color: #FFFFFF; color: black;">
                            <td colspan="2" style="padding-top: 10px;">Order ID: <?=$orderID;?></td>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr style="background-color: #FFFFFF; color: black;">
                            <td colspan="2" style="padding-bottom: 10px;">Order Date: <?=$orderDatetime;?></td>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr class="item">
                            <td colspan="2">Product Ordered</td>
                            <td>Unit Price</td>
                            <td>Quantity</td>
                            <td>Total Price</td>
                        </tr>
                        <?php
                            if (isset($_GET["id"]) && $_GET["id"] != "") {
                                $id = $_GET["id"];
                                $accountID = $_SESSION["accountID"];
                                $fetchCartQuery = "
                                    SELECT
                                    catalog_item.*,
                                    item_order.orderQuantity, item_order.orderCost,
                                    order_receipt.orderID, order_receipt.paymentAmount, order_receipt.orderDatetime,
                                    cart.cartID,
                                    user_profile.userID
                                    FROM order_receipt
                                    JOIN cart ON order_receipt.cartID = cart.cartID
                                    JOIN item_order ON cart.cartID = item_order.cartID
                                    JOIN catalog_item ON item_order.productIndex = catalog_item.productIndex
                                    JOIN user_profile ON user_profile.userID = cart.userID
                                    WHERE user_profile.accountID = '$accountID' AND cart.cartID = '$id';
                                ";
                                $results = mysqli_query($conn, $fetchCartQuery);
                                $numRows = mysqli_num_rows($results);
                                $rowIndex = 1;

                                while ($row = mysqli_fetch_assoc($results)) {
                                    $productIndex = $row["productIndex"];
                                    $productID = $row["productID"];
                                    $productName = $row["productName"];
                                    $productPrice = number_format($row["productPrice"], 2);
                                    $productImagePath = $row["productImagePath"];
                                    $orderQuantity = $row["orderQuantity"];
                                    $orderCost = number_format($row["orderCost"], 2);
                                    $totalCost = number_format($row["paymentAmount"], 2);
    
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                    echo "<tr class='itemRow'>";
                                    echo "<td style='width: 10%;'><img src='$productImagePath' style='width: 60px; height: 60px; align-items: center; display: inline-block' alt='ProductImage'></td>";
                                    echo "<td style='width: 50%;'><b>$productID</b><br>$productName</td>";
                                    echo "<td style='text-align: center'>RM$productPrice</td>";
                                    echo "<td style='text-align: center'>$orderQuantity</td>";
                                    echo "<td style='text-align: center'>RM$orderCost</td>";
                                    echo "</tr>";
                                    
                                    $rowIndex++;
                                }
                            }

                            mysqli_close($conn);
                        ?>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <tr class="lastRow">
                            <td colspan="3" style="padding-top: 15px; padding-bottom: 15px;">&nbsp;</td>
                            <td style="text-align: center;">Total Price</td>
                            <td style="color: red; text-align: center;"><b>RM<?=$totalCost;?></b></td>
                        </tr>
                    </table>
                    <a class="back-link" href="javascript:history.back();" style="text-align: left, text-decoration: none; color: white; font-size: 18px;">< Back</a>
                    <button class="button" onclick="redirect('../invoicingModule/generateInvoiceCustomer.php?id=<?=$orderID;?>');">Generate Invoice</button>
                </div>
                <br><br>
            </div>
        </div>
    </main>

    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>
</body>

</html>