<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Add Product | Electroholics</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/catalogueStyle.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../siteJavascript.js"></script>
    <style>
        main {
            min-height: 90vh;
            background-color: #444444;
            color: #FFFFFF;
        }
        .addProduct-container {
            align: center;
            padding-left: 30%;
            padding-right: 30%;
        }
        .addProduct {
            width: 100%;
        }
        .addProduct .textfield {
            height: 30px;
            width: 100%;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .addProduct select {
            height: 30px;
            width: 100%;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .addProduct textarea {
            height: 100px;
            width: 100%;
            display: block;
            font-size: 18px;
            resize: none;
        }
        .addProduct .button {
            width: 30%;
            height: 30%;
            font-size: 18px;
            background-color: white;
            border: 1px solid #666666;
            transition: background-color 0.1s, color 0.1s;
        }
        .addProduct .button:hover {
            cursor: pointer;
            background-color: #888888;
            color: white;
        }
        @media screen and (max-width: 600px) {
            .addProduct-container {
                padding-left: 10%;
                padding-right: 10%;
            }
        }
    </style>
</head>

<body>
    <nav class="topnav" id="myTopnav">
        <a href="../index.php" class="tab"><img src="../images/websiteElements/siteElements/electroholicsLogo.png"><b> ELECTROHOLICS </b></a>
        <a href="../index.php" class="tab"><b>HOME</b></a>
        <a href="processors.php" class="active"><b>PRODUCTS</b></a>
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
                    echo "<a href='../shoppingCartModule/cart.php' class='tab'><i class='fa fa-shopping-cart'><b></i> My Cart (# items)</b></a>";
                    echo "<a href='../userProfileAndAccountModule/profile.php' class='tab'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
                    echo "<a href='../userAuthenticationModule/logout.php' class='tabRight'><b>LOGOUT</b></a>";
                }
            }
            else {  // if a session is not active
                echo "<a href='../userAuthenticationModule/login.php' class='tabRight'><b>LOGIN</b></a>";
            }

        ?>
        <a href="javascript:void(0);" class="icon" onClick="adjustTopnav();"><i class="fa fa-bars"></i></a>
    </nav>

    <main>
        <div class="addProduct-container">
            <form class="addProduct" id="addProduct" action="addProduct-action.php" method="POST" enctype="multipart/form-data">
                <caption><h2 style="text-align: center;">Add Product</h2></caption>
            
                <!-- productIndex and productID will be generated in the backend. See addProduct-action.php -->

                <label for="productType">Product Type *</label>
                <select id="productType" name="productType" required>
                    <option value="" disabled selected>Select a product type...</option>
                    <option value="cpu">CPU</option>
                    <option value="motherboards">Motherboard</option>
                    <option value="gpu">GPU</option>
                    <option value="ram">RAM</option>
                    <option value="ssd">SSD</option>
                    <option value="psu">PSU</option>
                    <option value="cases">Case</option>
                    <option value="cooling">Cooling</option>
                    <option value="cables">Cable</option>
                </select><br>

                <label for="productName">Product Name *</label>
                <input class="textfield" id="productName" name="productName" type="text" required><br>

                <label for="productDescription">Product Description</label>
                <textarea id="productDescription" name="productDescription" rows="5" columns="5"></textarea><br>
            
                <label for="productPrice">Product Price (RM) *</label>
                <input class="textfield" id="productPrice" name="productPrice" type="text" required><br>

                <!-- let stock quantity default to 0 first. -->
                <input id="productStock" name="productStock" type="hidden" value="0">

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

                <label for="productImageToUpload">Product Image (max. 512KB)</label><br>
                <input class="productImageToUpload" id="productImageToUpload" name="productImageToUpload" type="file" accept=".jpg, .jpeg, .png" onchange="loadFile(event)"><br><br>

                <div style="text-align: center">
                    <img id="output" src="" style="max-width: 256px; max-height: 256px; background-color: #FFFFFF; border: 2px solid #666666;"><br><br>

                    <input class="button" name="buttonAdd" type="submit" value="Add">
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