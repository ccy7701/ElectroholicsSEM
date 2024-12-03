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
    <style>
        .addProductButtonMin {
            text-align: center;
        }
        .addProductButtonMin button {
            background-color: #02134F;
            color: #FFF;
            width: 30%;
            height: 40px;
            font-size: 18px;
            border: 1px solid #666666;
            transition: background-color 0.1s, color 0.1s;
        }
        .addProductButtonMin button:hover {
            background-color: #FFFFFF;
            color: #000;
            cursor: pointer;
        }

        /* Reset some default styles */
        body, h1, h2, p {
            margin: 0;
            padding: 0;
        }

        /* Style the header */
        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .textBody {
            background-color: #FFFFFF;
            color: #000000;
            margin: 0;
            display: flex;
            flex-direction: column;
            padding-left: 12%;
            padding-right: 12%;
            min-height: 100vh;
            flex: 2;
            width: 85%;
        }

        .product-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 100%;
        }

        .product-image {
            flex: 1;
            padding: 20px;
            text-align: center;
        }

        .product-image img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .product-info {
            flex: 2;
            padding: 20px;
        }

        .product-info h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .product-info p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .add-to-cart {
            text-align: left;
        }

        .add-to-cart button {
            background-color: #02134F;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 22px;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }

        .add-to-cart button:hover {
            background-color: #d4af37;
        }

        .back-link {
            text-decoration: none;
            color: #000000;
            font-size: 18px;
        }
        
        .back-link:hover {
            font-weight: bold;
        }

        /* Style the product description section */
        .product-description {
            margin-top: 20px;
        }

        .product-description h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .product-description p {
            font-size: 16px;
            line-height: 1.5;
        }

        @media screen and (max-width: 600px) {
            .addProductButtonMin button {
                width: 40%;
            }
            .textBody {
                padding-left: 6%;
                padding-right: 6%;
            }
            .product-details {
                width: 100%;
            }
            .verticalMenu {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php
        // if $_SESSION["successMessage"] is set from an AddToCart invocation, display this message
        if (isset($_SESSION["successMessage"])) {
            $successMessage = $_SESSION["successMessage"];
            // display the success message
            echo "
                <script>
                    popup(\"$successMessage\", \"\");
                </script>
            ";
            // then unset the variable again. not doing this will make it loop indefinitely
            unset($_SESSION["successMessage"]);
        }
    ?>

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
                    echo "<a href='../userProfileAndAccountModule/profile.php' class='tab'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
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

    <main style="flex: 1;">
        <?php
            // query for all information on the product first, based on ID passed to this page
            if (isset($_GET["id"]) && $_GET["id"] != "") {
                $productIndex = $_GET["id"];

                // get the corresponding product
                $fetchProductQuery = "SELECT * FROM catalog_item WHERE productIndex = '$productIndex' LIMIT 1";
                $result = mysqli_query($conn, $fetchProductQuery);
                $row = mysqli_fetch_assoc($result);
            }
        ?>

        <div class="row">
            <div class="verticalMenu">
                <ul>
                    <li>
                        <div class="search-wrapper">
                            <input type="search" id="search" placeholder=" Search " style="width: 80%; height: 40px; font-size: 16px; ">
                            <i class="fa fa-search"></i>
                        </div>
                    </li>
                    <br>
                    <div id="category-links">
                        <?php
                            // dynamically make the correct product category's link the 'active' class
                            $currentCategory = $row["productType"];
                            // if the currentCategory is "cooling", override it and set it to "cases"
                            if ($currentCategory === "cooling") {
                                $currentCategory = "cases";
                            }

                            $categories = array(
                                "cpu" => array("processors.php", "Processors (CPUs)"),
                                "motherboards" => array("motherboards.php", "Motherboards"),
                                "gpu" => array("gpu.php", "Graphics Cards (GPUs)"),
                                "ram" => array("ram.php", "Memory (RAM)"),
                                "ssd" => array("ssd.php", "Storage Drives (SSDs and HDDs)"),
                                "psu" => array("psu.php", "Power Supplies (PSUs)"),
                                "cases" => array("cases.php", "Cases and Cooling"),
                                // "cooling" => array("cases.php", "Cases and Cooling"),  [[ ASSUME THAT SINCE "COOLING" REFER TO "CASES" ANYWAY, THAT THIS IS NOT NEEDED ]]
                                "cables" => array("cables.php", "Cables and Connectors")
                            );

                            foreach ($categories as $key => $value) {
                                $link = $value[0];
                                $label = $value[1];

                                if ($key === $currentCategory) {
                                    echo "<li>";
                                    echo "<a href='../catalogueModule/".$link."' class='active'>".$label."</a>";
                                    echo "</li>";
                                }
                                else {
                                    echo "<li>";
                                    echo "<a href='../catalogueModule/".$link."'>".$label."</a>";
                                    echo "</li>";
                                }
                            }
                        ?>
                    </div>
                </ul>
            </div>

            <div class="textBody">
                <br>
                <a class="back-link" href="javascript:history.back();">< Back</a>
                <div class="product-details">
                    <div class="product-image">
                        <img src="<?=$row["productImagePath"];?>" alt="Product Image">
                    </div>
                    <div class="product-info">
                        <p><strong><?=$row["productID"];?></strong></p>
                        <h1><?=$row["productName"];?></h1>
                        <p><strong>Description: </strong>
                            <?php
                                if ($row["productDescription"] != "") {
                                    echo $row["productDescription"];
                                }
                                else {
                                    echo "<i>No description has been added yet.</i>";
                                }
                            ?>
                        </p>
                        <p><strong>Price:</strong> RM <?php echo number_format($row["productPrice"], 2);?></p>
                        <p>
                            <strong>Availability:</strong>
                            <?php
                                if ($row["productStock"] > 0) {
                                    echo " In Stock: ".$row["productStock"]." available";
                                }
                                else {
                                    echo " OUT OF STOCK";
                                }
                            ?>
                        </p>

                        <?php
                            // Check if the logged-in user is an admin
                            if ($accountRole == 1) {
                                // Replace the Add to Cart button with an Edit button for admins
                                $editIndex = $row['productIndex'];
                                echo "<button class='editButton' onclick=\"redirect('editProduct.php?id=$editIndex');\">Edit</button>";
                            } 
                            else if ($accountRole == 2) {
                                // Display the Add to Cart button for customers
                                $addIndex = $row['productIndex'];
                                echo '<div class="add-to-cart">';
                                echo "<button onclick=\"redirect('../shoppingCartModule/addToCart.php?id=$addIndex');\">Add to Cart</button>";
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            
    </main>

    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>

</body>

</html>