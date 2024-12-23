<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en" xml:lang="en">

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
    <script type="text/javascript">
        function createPath(target) {
            let scriptPath = "deleteProduct-action.php?id=";
            let overallPath = scriptPath.concat(target);
            return overallPath;
        }
        function confirmRemoval(targetID) {
            var promptConfirm = confirm("Are you sure you want to delete this product?");
            if (promptConfirm) {
                // if OK is clicked, redirect to deleteProduct-action with the targetID
                var path = createPath(targetID);
                window.location.href = path;
            }
            // do nothing otherwise
        }
    </script>
    <style>
        main {
            min-height: 90vh;
            background-color: #444444;
            color: #FFFFFF;

        }
        .editProduct-container {
            align: center;
            padding-left: 30%;
            padding-right: 30%;
        }
        .editProduct {
            width: 100%;
        }
        .editProduct .textfield {
            height: 30px;
            width: 100%;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .editProduct select {
            height: 30px;
            width: 100%;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .editProduct textarea {
            height: 100px;
            width: 100%;
            display: block;
            font-size: 18px;
            resize: none;
        }
        .editProduct .button {
            width: 30%;
            height: 30px;
            font-size: 18px;
            background-color: white;
            border: 1px solid #666666;
            transition: background-color 0.1s, color 0.1s;
        }
        .editProduct .button:hover {
            cursor: pointer;
            background-color: #888888;
            color: white;
        }
        @media screen and (max-width: 600px) {
            .editProduct-container {
                padding-left: 10%;
                padding-right: 10%;
            }
        }
    </style>
<head>

<body>
<nav class="topnav" id="myTopnav">
        <a href="../index.php" class="tab"><img src="../images/websiteElements/siteElements/electroholicsLogo.png" alt="Electroholics Logo"><b> ELECTROHOLICS </b></a>
        <a href="../index.php" class="tab"><b>HOME</b></a>
        <a href="processors.php" class="tab"><b>PRODUCTS</b></a>
        <?php
            if (isset($_SESSION["accountID"])) {    // if a user is logged in and a session is active
                $accountID = $_SESSION["accountID"];
                $accountEmail = $_SESSION["accountEmail"];
                $username = $_SESSION["username"];
                $accountRole = $_SESSION["accountRole"];

                if ($accountRole == 1) {    // if the logged in user is an admin, show tabs available only to admin side
                    echo "<a href='../inventoryTrackingModule/storeInventory.php' class='active'><b>STORE INVENTORY</b></a>";
                    echo "<a href='../orderHistoryModule/orderHistoryAdmin.php' class='tab'><b>ALL ORDER HISTORY</b></a>";
                    echo "<a href='../userProfileAndAccountModule/profile.php' class='tab'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
                    echo "<a href='../userAuthenticationModule/logout.php' class='tabRight'><b>LOGOUT</b></a>";
                    // add more in the future as and when required
                }
                else if ($accountRole == 2) {   // otherwise, just show tabs available to the customer
                    echo "<a href='../shoppingCartModule/cart.php' class='tab'><i class='fa fa-shopping-cart'><b></i> My Cart (# items)</b></a>";
                    echo "<a href='../userProfileAndAccountModule/profile.php' class='tab'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
                    echo "<a href='../userAuthenticationModule/logout.php' class='tabRight'><b>LOGOUT</b></a>";
                }
            }
            else {  // if a session is not active
                echo "<a href='userAuthenticationModule/login.php' class='tabRight'><b>LOGIN</b></a>";
            }

        ?>
        <button type="button" class="icon" onclick="adjustTopnav();">
            <i class="fa fa-bars"></i>
        </button>
    </nav>

    <main>
        <?php
        // Check if 'id' is set and not empty
        if (isset($_GET["id"]) && $_GET["id"] != "") {
            // Sanitize 'id' input
            $id = $_GET["id"];

            // Prepare the SQL query to prevent SQL injection
            $stmt = mysqli_prepare($conn, "SELECT * FROM catalog_item WHERE productIndex = ?");
            
            if ($stmt) {
                // Bind the parameter as an integer
                mysqli_stmt_bind_param($stmt, "i", $id);

                // Execute the query
                mysqli_stmt_execute($stmt);

                // Fetch the result
                $result = mysqli_stmt_get_result($stmt);

                // Check if a row is returned
                if ($row = mysqli_fetch_assoc($result)) {
                    // Fetch the data to populate the form
                    $productID = $row["productID"];
                    $productType = $row["productType"];
                    $productName = $row["productName"];
                    $productDescription = $row["productDescription"];
                    $productPrice = $row["productPrice"];
                    $productStock = $row["productStock"];
                    $productImagePath = $row["productImagePath"];
                } else {
                    echo "No product found.";
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            } else {
                echo "Failed to prepare statement.";
            }

            // Close the database connection
            mysqli_close($conn);
        }
        ?>
        <br>
        <div class="editProduct-container">
            <form class="editProduct" id="editProduct" action="editStock-action.php" method="POST" enctype="multipart/form-data">
                <caption><h2 style="text-align: center;">Edit Stock</h2></caption>
            
                <input id="productIndex" name="productIndex" type="hidden" value="<?=$id;?>">  
                <input id="productName" name="productNameHidden" type="hidden" value="<?=$productName;?>">

                <label for="productID">Product ID</label>
                <input class="textfield" id="productID" name="productID" type="text" value="<?=$productID;?>" disabled><br>

                <label for="productType">Product Type</label>
                <?php
                    // the product types as the database will accept
                    $availableProductTypes = ['cpu', 'motherboards', 'gpu', 'ram', 'ssd', 'psu', 'cases', 'cooling', 'cables'];
                    // the names associated to each product type that will be displayed in the form
                    $productTypeAssocArray = ['cpu'=>'CPU', 'motherboards'=>'Motherboard', 'gpu'=>'GPU',
                                            'ram'=>'RAM', 'ssd'=>'SSD', 'psu'=>'PSU',
                                            'cases'=>'Case', 'cooling'=>'Cooling', 'cables'=>'Cable'];

                    foreach ($availableProductTypes as $option) {
                        $displayString = isset($productTypeAssocArray[$option]) ? $productTypeAssocArray[$option] : $option;
                        if ($option == $productType) {
                            echo "<input class='textfield' id='productType' name='productType' type='text' value=\"$displayString\" disabled><br>";
                        }
                    }
                ?>

                <label for="productName">Product Name</label>
                <input class="textfield" id="productName" name="productName" type="text" value="<?=$productName;?>" disabled><br>

                <label for="productDescription">Product Description</label>
                <textarea id="productDescription" name="productDescription" rows="5" columns="5" disabled><?=$productDescription;?></textarea><br>

                <label for="productPrice">Product Price (RM)</label>
                <input class="textfield" id="productPrice" name="productPrice" type="text" value="<?php echo number_format($productPrice, 2);?>" disabled><br>

                <label for="productStock">Product Stock</label>
                <input class="textfield" id="productStock" name="productStock" type="number" value="<?=$productStock;?>" required><br>

                <script type="text/javascript">
                    // there is an image upload tool here. this function previews the image to the user before it is uploaded
                    var loadFile = function(event) {
                        var reader = new FileReader();
                        reader.onload = function() {
                            var output = document.getElementById('output');
                            output.src = reader.result;
                        };
                        reader.readAsDataURL(event.target.files[0]);
                    }
                </script>

                <label for="productImageToUpload">Product Image</label><br><br>

                <div style="text-align: center">
                    <img id="output" src="<?=$productImagePath?>" alt="Product Image" style="max-width: 250px; max-height: 250px; background-color: #FFFFFF; border: 2px solid #666666;"><br><br>
                    <input class="button" name="buttonSubmit" type="submit" value="Edit Stock">
                    <input class="button" name="buttonCancel" type="button" onclick="history.back();" value="Cancel">
                </div>
                <br>
            </form>
        </div>
    </main>

    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>

</body>

</html>