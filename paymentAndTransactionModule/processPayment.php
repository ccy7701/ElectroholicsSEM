<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Process Payment</title>
    <script src="../siteJavascript.js"></script>
</head>

<body>
    <?php
        if (isset($_SESSION["accountID"])) {
            $accountID = $_SESSION["accountID"];

            // STEP 1: Update the stock in catalog_item for every item bought
            $fetchOrderQuantitiesQuery = "
                SELECT catalog_item.productIndex, catalog_item.productStock, item_order.orderQuantity
                FROM catalog_item
                JOIN item_order ON item_order.productIndex = catalog_item.productIndex
                JOIN cart ON cart.cartID = item_order.cartID
                JOIN user_profile ON user_profile.userID = cart.userID
                WHERE user_profile.accountID = '$accountID' AND cart.isActive = 1;
            ";
            $results = mysqli_query($conn, $fetchOrderQuantitiesQuery);
            while ($row = mysqli_fetch_assoc($results)) {
                $productIndex = $row["productIndex"];
                $productStock = $row["productStock"];
                $orderQuantity = $row["orderQuantity"];
                $updateStockQuery = "
                    UPDATE catalog_item
                    SET productStock = productStock - $orderQuantity
                    WHERE productIndex = '$productIndex';
                ";
                if (mysqli_query($conn, $updateStockQuery)) {
                    echo "Update stock successful.";
                }
                else {
                    echo "
                        <script>
                            popup(\"ERROR: Something went wrong. ".mysqli_error($conn).".\", \"../index.php\");
                        </script>
                    ";
                }
            }
            
            // STEP 2: Add a new row to order_receipt with the current cart
            $addOrderReceiptQuery = "
                INSERT INTO order_receipt (cartID, paymentAmount)
                SELECT cartID, totalCost
                FROM cart
                JOIN user_profile ON user_profile.userID = cart.userID
                JOIN account ON account.accountID = user_profile.accountID
                WHERE account.accountID = '$accountID' AND cart.isActive = 1;
            ";

            // STEP 3: Update the status of this cart, make it 'completed' as in isActive from 1 to 0
            $changeCartStatusQuery = "
                UPDATE cart
                SET isActive = 0
                WHERE cart.userID IN (
                    SELECT user_profile.userID
                    FROM user_profile
                    JOIN account ON account.accountID = user_profile.accountID
                    WHERE account.accountID = '$accountID'
                )
                AND cart.isActive = 1;
            ";

            // STEP 4: Create a new cart for the account, make that one active
            $createNewCartQuery = "
                INSERT INTO cart (userID, totalCost, isActive)
                SELECT user_profile.accountID, 0.00, 1
                FROM user_profile
                JOIN account ON user_profile.accountID = account.accountID
                WHERE user_profile.accountID = '$accountID'
            ";

            // if all three queries work together
            if (mysqli_query($conn, $addOrderReceiptQuery) && mysqli_query($conn, $changeCartStatusQuery) && mysqli_query($conn, $createNewCartQuery)) {
                echo "
                    <script>
                        popup(\"Order successful.\", \"../orderHistoryModule/orderHistory.php\");
                    </script>
                ";
            }
            else {
                echo "
                    <script>
                        popup(\"ERROR: ".mysqli_error($conn)."\", \"payment.php\");
                    </script>
                ";
            }
        }

        mysqli_close($conn);
    ?>
</body>

</html>