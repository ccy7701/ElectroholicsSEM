<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>
    <title>Edit Confirmation</title>
    <script src="../siteJavascript.js"></script>
<head>

</head>

<body>
    <?php
        // commit to the database the data from the stock field ONLY
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $target = mysqli_real_escape_string($conn, $_POST["productIndex"]);
            $productName = mysqli_real_escape_string($conn, $_POST["productNameHidden"]);
            $newProductStock = mysqli_real_escape_string($conn, $_POST["productStock"]);

            $updateDBQuery = "
                UPDATE catalog_item
                SET productStock = '$newProductStock'
                WHERE productIndex = '$target';
            ";

            if (mysqli_query($conn, $updateDBQuery)) {
                echo "
                    <script>
                        popup(\"Product stock for ".$productName." updated successfully.\", \"storeInventory.php\");
                    </script>
                ";
            }
            else {
                echo "
                    <script>
                        popup(\"ERROR ENCOUNTERED. ".mysqli_error($conn)."\", \"storeInventory.php\");
                    </script>
                ";
            }
        }
        mysqli_close($conn);
    ?>
</body>

</html>