<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Delete Product</title>
    <script src="../siteJavascript.js"></script>
</head>

<body>
    <?php
        if (isset($_GET["id"]) && $_GET["id"] != "") {
            $id = $_GET["id"];

            // first, unlink the image
            $imgPathSeekQuery = "SELECT * FROM catalog_item WHERE productIndex = '$id'";
            $return = mysqli_query($conn, $imgPathSeekQuery);
            $row = mysqli_fetch_assoc($return);
            $itemToDelete = $row["productName"];
            $imgToDelete = $row["productImagePath"];
            if ($imgToDelete != "") {
                unlink($imgToDelete);
            }

            // then, delete the row
            $deleteQuery = "DELETE FROM catalog_item WHERE productIndex = '$id'";
            if (mysqli_query($conn, $deleteQuery)) {
                echo "
                    <script>
                        popup(\"Catalog item ".$itemToDelete." removed successfully.\", \"processors.php\");
                    </script>
                ";
            }
            else {
                echo "
                    <script>
                        popup(\"ERROR: Something went wrong. ".mysqli_error($conn).".\", \"processors.php\");
                    </script>
                ";
            }

            mysqli_close($conn);
        }
    ?>
</body>

</html>
