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
    <script type="text/javascript">
        // check window width at page load first. hide the menu if width < 600px.
        window.onload = function() {
            checkWindowWidth();
        }
        function checkWindowWidth() {
            var x = document.getElementById("categories");
            var caretIcon = document.getElementById("caret");
            if (window.innerWidth < 600) {
                x.style.display = "none";
                caretIcon.classList.remove('fa-caret-up');
                caretIcon.classList.add('fa-caret-down');  
            }
            else {
                x.style.display = "block";
                caretIcon.classList.remove('fa-caret-down');
                caretIcon.classList.add('fa-caret-up');
            }
        }
    </script>
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
        @media screen and (max-width: 600px) {
            .addProductButtonMin button {
                width: 40%;
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

                    echo "<a href='../shoppingCartModule/cart.php' class='tab'><i class='fa fa-shopping-cart'><b></i> My Cart ($numberOfCartItems items)</b></a>";
                    echo "<a href='../userProfileAndAccountModule/profile.php' class='tab'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
                    echo "<a href='../userAuthenticationModule/logout.php' class='tabRight'><b>LOGOUT</b></a>";
                }
            }
            else {  // if a session is not active
                $accountRole = 0;
                echo "<a href='../userAuthenticationModule/login.php' class='tabRight'><b>LOGIN</b></a>";
            }

        ?>
        <a href="javascript:void(0);" class="icon" onClick="adjustTopnav();"><i class="fa fa-bars"></i></a>
    </nav>

    <main style="flex: 1;">
        <div class="row">
            <div class="verticalMenu">
                <ul>
                    <div id="category-links">
                        <li>
                            <div class="search-wrapper">
                                <input type="search" id="search" placeholder=" Search " style="width: 80%; height: 40px; font-size: 16px; ">
                                <i class="fa fa-search"></i>
                            </div>
                        </li>
                        <br>
                        <li><a class="more" href="javascript:void(0);" onClick="adjustCategories();">All Categories&nbsp;&nbsp;<i class="fa fa-caret-down" id="caret"></i></a></li>
                        <div class="categories" id="categories">
                            <li><a href="../catalogueModule/processors.php">Processors (CPUs)</a></li>
                            <li><a href="../catalogueModule/motherboards.php" class="active">Motherboards</a></li>
                            <li><a href="../catalogueModule/gpu.php">Graphics Cards (GPUs)</a></li>
                            <li><a href="../catalogueModule/ram.php">Memory (RAM)</a></li>
                            <li><a href="../catalogueModule/ssd.php">Storage Drives (SSDs and HDDs)</a></li>
                            <li><a href="../catalogueModule/psu.php">Power Supplies (PSUs)</a></li>
                            <li><a href="../catalogueModule/cases.php">Cases and Cooling</a></li>
                            <li><a href="../catalogueModule/cables.php">Cables and Connectors</a></li>
                        </div>
                        <br>
                    </div>
                </ul>
            </div>

            <div class="textBody">
                <br>

                <div class="addProductButtonMin">
                    <?php
                    if ($accountRole == 1) {
                        echo "<button onclick=\"window.location.href='addProduct.php'\">Add Product</button>";
                    }
                    ?>
                </div>

                <br>

                <table class="indexTable" id="tableFeaturedProducts">
                    <tr>
                        <th colspan=3 style="font-size: 30px;">Motherboards</th>
                    </tr>

                    <?php
                        // fetch the products from the database
                        $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType='motherboards'";
                        $results = mysqli_query($conn, $fetchProductsQuery);    // all rows returned by the query
                        $numRows = mysqli_num_rows($results);                  // number of rows returned by that query
                        $tableRowsNeeded = ceil($numRows / 3);  // the number of rows needed in the products table

                        for ($i = 0; $i < $tableRowsNeeded; $i++) {
                            echo '<tr>';

                            // start the inner loop for <td> elements
                            for ($j = 0; $j < 3; $j++) {
                                // fetch the current row from the results
                                $row = mysqli_fetch_assoc($results);

                                if ($row) {
                                    // output the content of each cell
                                    $viewIndex = $row["productIndex"];
                                    echo "<td>";
                                    echo "<img class='tableImage' src='".$row['productImagePath']."'><br>";
                                    echo "<p class='product'><a href='viewproduct.php?id=$viewIndex' style='color: black; text-decoration: none;'>".$row['productName']."</a></p>";
                                    echo "<p><b>RM".number_format($row['productPrice'], 2)."</b></p>";

                                    if ($accountRole == 1) {    // if admin is logged in, show edit button for the product
                                        $editIndex = $row['productIndex'];
                                        echo "<input class='editButton' onclick=\"redirect('editProduct.php?id=$editIndex')\" type='button' value='Edit'>";
                                    }
                                    else if ($accountRole == 2) {   // if customer is logged in, show add to cart button for the product
                                        // the add to cart button is down here
                                        $addIndex = $row['productIndex'];
                                        echo '<div class="add-to-cart">';
                                        echo "<button onclick=\"redirect('../shoppingCartModule/addToCart.php?id=$addIndex');\">Add to Cart</button>";
                                        echo '</div>';
                                    }
                                    else {
                                        // leave this empty for now
                                    }

                                    // in the future, might also need to add a (VIEW) button HERE to view the product
                                }
                                else {
                                    // if no more rows, output an empty cell
                                    echo "<td>&nbsp;</td>";
                                }
                            }

                            echo "</tr>";
                        }
                    ?>
                </table>

                <script type="text/javascript">
                    function filterProducts() {
                        var searchInput = document.getElementById("search").value.toLowerCase();
                        var productContainers = document.querySelectorAll(".textBody .indexTable td");

                        // store the original table style first
                        var originalTableStyle = [];
                        for (var i = 0; i < productContainers.length; i++) {
                            originalTableStyle.push({
                                display: "table-cell",
                                width: "33.33%",
                            });
                        }

                        for (var i = 0; i < productContainers.length; i++) {
                            var product = productContainers[i].textContent.toLowerCase();
                            if (searchInput === "" || product.indexOf(searchInput) !== -1) {
                                productContainers[i].style.display = "block";
                                productContainers[i].style.width = "100.00%";
                            }
                            else {
                                productContainers[i].style.display = "none";
                            }
                        }

                        // Check if the search input is empty and refresh the page if it is
                        // if (searchInput === "") {
                        //     location.reload();  // This will refresh the page
                        // }

                        // New check: if search input is empty, display all in that category
                        if (searchInput === "") {
                            for (var j = 0; j < productContainers.length; j++) {
                                productContainers[j].style.display = originalTableStyle[j].display;
                                productContainers[j].style.width = originalTableStyle[j].width;
                            }
                        }
                    }

                    document.getElementById("search").addEventListener("input", filterProducts);
                </script>

                <br>
            </div>


        </div>
    </main>

    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>
</body>

</html>