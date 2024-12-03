<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Catalogue | Electroholics</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/catalogueStyle.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../siteJavascript.js"></script>
    <script>
        // JavaScript code to handle quantity increment and decrement
        document.addEventListener("DOMContentLoaded", function() {
            const cartItems = document.querySelectorAll(".cart-item");

            cartItems.forEach(item => {
                const quantityInput = item.querySelector(".quantity-input");
                const plusButton = item.querySelector(".plus");
                const minusButton = item.querySelector(".minus");

            plusButton.addEventListener("click", () => {
                quantityInput.value = parseInt(quantityInput.value) + 1;
                updateTotalPrice(); // You can define this function to update the total price
            });

            minusButton.addEventListener("click", () => {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    updateTotalPrice(); // You can define this function to update the total price
                }
            });
        });
    });
    </script>
    <script type="text/javascript">
        function createPath(target) {
            let scriptPath = "deleteFromCart.php?id=";
            let overallPath = scriptPath.concat(target);
            return overallPath;
        }
        function confirmRemoval(targetID) {
            var promptConfirm = confirm("Are you sure you want the delete this item from cart?");
            if (promptConfirm) {
                // if OK is clicked, redirect to deleteFromCart with the targetID
                var path = createPath(targetID);
                window.location.href = path;
            }
            // do nothing otherwise
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        main {
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            margin-bottom: 0px;
        }

        header {
            background-color: #02134F;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        .cart {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .cart-item {
            display: flex;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            align-items: center;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            margin-right: 10px;
        }

        .item-details {
            flex-grow: 1;
        }

        .cart-total {
            text-align: right;
            margin-top: 20px;
        }

        .remove-button {
            background-color: #D30000;
            color: #FFF;
            border: white;
            padding: 10px 20px; /* Adjust the padding as needed */
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }

        .remove-button:hover {
            background-color: #CD5C5C;
        }

        .checkout-button-disabled {
            background-color: #BEBEBE;
            color: white;
            border: white;
            padding: 10px 20px;
            font-size: 16px;
            float: right;
        }

        .checkout-button {
            background-color: #00ab41;
            color: white;
            border: white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            float: right;
        }

        .checkout-button:hover {
            background-color: #00c04b;
        }

        .quantity-button {
            background-color: #02134F;
            color: #FFF;
            border: none;
            width: 30px;
            height: 30px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.1s, color 0.1s;
            margin: 0 5px; /* Add margin between buttons */
        }

        /* Style for the plus button */
        .plus {
            background-color: #808080;
        }

        .plus:hover {
            background-color: #999999;
        }

        /* Style for the minus button */
        .minus {
            background-color: #808080;
        }

        .minus:hover {
            background-color: #999999;
        }

        .quantity-button:hover {
            background-color: #808080;
            color: #000;
        }

        .quantity-input {
            width: 30px; 
            height: 30px; 
            text-align: center; 
        }

        @media screen and (max-width: 600px) {
            .addProductButtonMin button {
                width: 40%;
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

                    echo "<a href='../shoppingCartModule/cart.php' class='active'><i class='fa fa-shopping-cart'><b></i> My Cart ($numberOfCartItems items)</b></a>";
                    echo "<a href='../userProfileAndAccountModule/profile.php' class='tab'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
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
    
    <main>
    <h1>Shopping Cart</h1>
        <section class="cart">
            <?php
                // first, get all items in the cart
                $fetchCartItemsQuery = "
                    SELECT catalog_item.*,
                    item_order.orderQuantity, item_order.orderCost,
                    cart.cartID, cart.totalCost,
                    user_profile.userID
                    FROM catalog_item
                    JOIN item_order ON item_order.productIndex = catalog_item.productIndex
                    JOIN cart ON cart.cartID = item_order.cartID
                    JOIN user_profile ON user_profile.userID = cart.userID
                    WHERE user_profile.accountID = '$accountID' AND cart.isActive = 1;
                ";
                $results = mysqli_query($conn, $fetchCartItemsQuery);
                $numRows = mysqli_num_rows($results);
                $rowIndex = 1;

                // if the query returns nothing, do the following
                if ($numRows == 0) {
                    $totalCost = number_format(0.00, 2);
                    echo "<div class='cart-item'>";
                    echo "<h2>No items have been added yet.</h2>";
                    echo "</div>";
                }
                // otherwise, loop through all the rows and populate the page
                else {
                    while ($row = mysqli_fetch_assoc($results)) {
                        $productIndex = $row["productIndex"];
                        $productID = $row["productID"];
                        $productType = $row["productType"];
                        $productName = $row["productName"];
                        $productPrice = number_format($row["productPrice"], 2);
                        $productImagePath = $row["productImagePath"];
                        $orderQuantity = $row["orderQuantity"];
                        $orderCost = number_format($row["orderCost"], 2);
                        $totalCost = number_format($row["totalCost"], 2);
    
                        echo "<div class='cart-item'>";
                        echo "<img src='$productImagePath' alt='ProductImage'>";
                        echo "<div class='item-details'>";
                        echo "<h2><b>$productID</b><br>$productName</h2>";
                        echo "<p>Price: RM$productPrice</p>";
                        echo "<div class='quantity-controls'>";
                        echo "<button class='quantity-button minus' onclick=\"redirect('minusOrderQuantity.php?id=$productIndex')\">-</button>";
                        echo "<input type='number' class='quantity-input' id=quantityAtRow-$rowIndex value='$orderQuantity' disabled>";
                        echo "<button class='quantity-button plus' onclick=\"redirect('addOrderQuantity.php?id=$productIndex')\">+</button>";
                        echo "</div>";
                        echo "</div>";
                        echo "<button class='remove-button' onclick=\"confirmRemoval('$productIndex')\">Remove</button>";
                        echo "</div>";
    
                        $rowIndex++;
                    }
                }

                mysqli_close($conn);
            ?>

            <div class="cart-total">
                <p>Total: RM<?=$totalCost;?></p>
            </div>
            <?php
                if ($numRows == 0) {
                    echo "<button class='checkout-button-disabled' disabled>Checkout</button>";
                }
                else {
                    echo "<button class='checkout-button' onclick=\"redirect('../paymentAndTransactionModule/payment.php')\">Checkout</button>";
                }
            ?>
        </div>
        </section>

        <br><br>
        
    </main>

    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>

</body>

</html>