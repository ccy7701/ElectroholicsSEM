<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Delete from Cart</title>
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

            // delete from item_order first
            $deleteFromCartQuery = "
                DELETE item_order
                FROM item_order
                INNER JOIN cart ON cart.cartID = item_order.cartID
                WHERE cart.cartID = '$cartID' AND item_order.cartID = '$cartID' AND item_order.productIndex = '$id';
            ";
            // then update the cart table
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
            if (mysqli_query($conn, $deleteFromCartQuery) && mysqli_query($conn, $updateCartQuery)) {
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