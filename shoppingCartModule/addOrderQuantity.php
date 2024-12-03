<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang='en'>

<head>
    <title>Add Order Quantity</title>
    <script src="../siteJavascript.js"></script>
</head>

<body>
    <?php
        if (isset($_GET["id"]) && $_GET["id"] != "") {
            $id = $_GET["id"];
            $accountID = $_SESSION["accountID"];

            // first, get the right cart
            $getCartQuery = "
                SELECT cart.cartID FROM cart
                JOIN user_profile ON cart.userID = user_profile.userID
                JOIN account ON user_profile.accountID = account.accountID
                WHERE account.accountID = '$accountID' AND cart.isActive = 1;
            ";

            $return = mysqli_query($conn, $getCartQuery);
            $row = mysqli_fetch_assoc($return);
            $cartID = $row["cartID"];

            // add to item_order first
            $addQuantityQuery = "
                UPDATE item_order
                SET 
                orderQuantity = orderQuantity + 1,
                orderCost = orderCost + (SELECT catalog_item.productPrice FROM catalog_item WHERE productIndex = '$id')
                WHERE cartID = '$cartID' AND productIndex = '$id';
            ";
            // then update the cart
            $updateCartQuery = "
                UPDATE cart
                SET totalCost = (
                    SELECT SUM(item_order.orderCost) AS totalCost
                    FROM item_order
                    WHERE item_order.cartID = '$cartID'
                    )
                WHERE cart.cartID = '$cartID' AND cart.isActive = 1;
            ";

            // if both queries work together
            if (mysqli_query($conn, $addQuantityQuery) && mysqli_query($conn, $updateCartQuery)) {
                echo "<script type=\"text/javascript\">window.history.back();</script>";
            }
            else {
                echo "
                    <script>
                        popup(\"ERROR: ".mysqli_error($conn)."\", \"cart.php\");
                    </script>
                ";
            }
            mysqli_close($conn);
        }
    ?>
</body>

</html>