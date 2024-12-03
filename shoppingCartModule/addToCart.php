<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Add To Cart Confirmation</title>
    <script src="../siteJavascript.js"></script>
</head>

<body>
    <?php
        if (isset($_GET["id"]) && $_GET["id"] != "") {
            // get the chosen product's productIndex
            $productIndex = $_GET["id"];

            // get the corresponding customer cart
            $fetchCartQuery = "SELECT * FROM cart WHERE userID=".$_SESSION["accountID"]." AND isActive=1 LIMIT 1;";
            $cartResult = mysqli_query($conn, $fetchCartQuery);
            $row = mysqli_fetch_assoc($cartResult);
            $cartID = $row["cartID"];
            $totalCost = $row["totalCost"];
			
			// consider something about .isEmpty() like (if query row returned .isEmpty then do what, else do what else.

            // get the chosen product's information from the catalog_item table
            $fetchProductQuery = "SELECT * FROM catalog_item WHERE productIndex=$productIndex";
            $result = mysqli_query($conn, $fetchProductQuery);
            $row = mysqli_fetch_assoc($result);
            $productIndex = $row["productIndex"];
            $productPrice = $row["productPrice"];

            // first, check if this product already exists in the customer's cart
            $checkForExistingRowQuery = "SELECT * FROM item_order WHERE cartID='$cartID' AND productIndex='$productIndex' LIMIT 1";
            $checkResult = mysqli_query($conn, $checkForExistingRowQuery);
            
            // if this query happens to return a row, update the QUANTITY instead of adding a new row to item_order.
            if (mysqli_num_rows($checkResult) > 0) {
                // product already exists. update the quantity.
                $updateQuantityQuery = "
                    UPDATE item_order
                    SET orderQuantity = orderQuantity + 1,
                    orderCost = orderCost + '$productPrice'   
                    WHERE cartID = '$cartID' AND productIndex = '$productIndex';                         
                ";
                // then, update the totalCost in cart.
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
                if (mysqli_query($conn, $updateQuantityQuery) && mysqli_query($conn, $updateCartQuery)) {
                    // this session variable will be passed back to the previous page then displayed.
                    $_SESSION["successMessage"] = "Successfully added item to cart.";
                    echo "<script type=\"text/javascript\">window.history.back();</script>";
                }
                else {
                    echo "
                    <script>
                        popup(\"ERROR: ".mysqli_error($conn)."\", \"processors.php\");
                    </script>
                    ";
                }
                mysqli_close($conn);
            }
            // otherwise, if it does not, then add a new row to item_order.
            else {
                // this one adds a new row into item_order
                $addToCartQuery = "
                    INSERT INTO item_order (cartID, productIndex, orderQuantity, orderCost) VALUES
                    ('$cartID', '$productIndex', 1, (SELECT '$productPrice' FROM catalog_item WHERE productIndex = '$productIndex'));
                ";
                // then, this one updates the totalCost in cart
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
                if (mysqli_query($conn, $addToCartQuery) && mysqli_query($conn, $updateCartQuery)) {
                    // this session variable will be passed back to the previous page then displayed.
                    $_SESSION["successMessage"] = "Successfully added item to cart.";
                    echo "<script type=\"text/javascript\">window.history.back();</script>";
                }
                else {
                    echo "
                    <script>
                        popup(\"ERROR: ".mysqli_error($conn)."\", \"processors.php\");
                    </script>
                    ";
                }
                mysqli_close($conn);
            }
        }
    ?>
</body>

</html>