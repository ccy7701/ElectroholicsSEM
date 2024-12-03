<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Store Inventory | Electroholics</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/catalogueStyle.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../siteJavascript.js"></script>
    <script type="text/javascript">
        // open the first tab ('cpu') by default on page load
        window.onload = function() {
            document.getElementById("defaultTab").click();
        }
        function openTab(evt, content) {
            var i, tabContent, tabLinks;
            tabContent = document.getElementsByClassName("tabContentContainer");
            for (i = 0; i < tabContent.length; i++) {
                tabContent[i].style.display = "none";
                tabContent[i].style.opacity = 0;        // opacity is set to 0 when hidden
            }
            tabLinks = document.getElementsByClassName("navTab");
            for (i = 0; i < tabLinks.length; i++) {
                tabLinks[i].className = tabLinks[i].className.replace(" active", "");
            }
            var selectedTab = document.getElementById(content);
            selectedTab.style.display = "block";
            // Trigger reflow before changing the opacity to ensure the transition is applied
            selectedTab.offsetHeight;
            selectedTab.style.opacity = 1;              // opacity is set to 1 when displaying
            evt.currentTarget.className += " active";
        }
    </script>
    <style>
        main {
            background-color: #333;
            min-height: 100vh;
        }
        .navTabsContainer {
            padding-left: 2%;
            padding-right: 2%;
            box-sizing: border-box;
        }
        .tabs {
            overflow: hidden;
            background-color: #A0A0A0;
            border: 1px solid #888888;
        }
        .navTab {
            background-color: #CCCCCC;
            float: left;
            border: 1px solid #888888;
            width: 11.11%;
            padding-top: 12px;
            padding-bottom: 12px;
            transition: 0.1s;
            font-size: 16px;
        }
        .navTab.active {
            background-color: #02134F;
            color: white;
        }
        .navTab:hover {
            background-color: #666666;
            color: white;
            cursor: pointer;
        }
        .tabContentContainer {
            display: none;
            padding: 1% 5%;
            transition: opacity 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }
        .tabContent {
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .inventoryTable {
            width: 100%;
            border-collapse: collapse;
        }
        .inventoryTable th {
            background-color: #02134F;
            padding-top: 10px;
            padding-bottom: 10px;
            width: 10%;
        }
        .inventoryTable tr:nth-child(odd) {
            background-color: #FFFFFF;
            transition: 0.1s;
        }
        .inventoryTable tr:nth-child(odd):hover {
            background-color: #BEC9EB;
        }
        .inventoryTable tr:nth-child(even) {
            background-color: #D9D9D9;
        }
        .inventoryTable .definingRow {
            font-size: 18px;
            font-weight: bold;
        }
        .inventoryTable .itemRow {

        }
        .inventoryTable .imageColumn {
            width: 15%;
            text-align: center;
            align-items: center;
        }
        .inventoryTable img {
            max-height: 128px;
            max-width: 128px;
        }
        .inventoryTable .productNameColumn {
            width: 45%;
            text-align: left;
        }
        .inventoryTable td {
            color: black;
            text-align: center;
        }
        .editButtonInv {
            text-align: center;
            color: #000000;
            background-color: #FFFFFF;
            border: 1px solid #888888;
            width: 50%;
            height: 50px;
            font-size: 16px;
            transition: background-color 0.1s, color 0.1s;
        }
        .editButtonInv:hover {
            background-color: #AAF0E6;
            cursor: pointer;
        }
        @media screen and (max-width: 900px) {
            .editButtonInv {
                width: 100%;
                font-size: 14px;
            }
        }
        @media screen and (max-width: 600px) {
            .navTab {
                width: 33.33%;
            }
            .tabContentContainer {
                padding: 1% 1.5%;
            }
            .inventoryTable .definingRow {
                font-size: 16px;
            }
            .inventoryTable .imageColumn {
                max-width: 100px;
            }
            .inventoryTable img {
                width: 84px;
                height: 84px;
            }
            .inventoryTable .productNameColumn {
                max-width: 100px;
                text-align: left;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .inventoryTable td {
                max-width: 100px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <nav class="topnav" id="myTopnav">
        <a href="../index.php" class="tab"><img src="../images/websiteElements/siteElements/electroholicsLogo.png"><b> ELECTROHOLICS </b></a>
        <a href="../index.php" class="tab"><b>HOME</b></a>
        <a href="../catalogueModule/processors.php" class="tab"><b>PRODUCTS</b></a>
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
                $accountRole = 0;   // assume that 0 means that the user is not logged in
                echo "<a href='../userAuthenticationModule/login.php' class='tabRight'><b>LOGIN</b></a>";
            }

        ?>
        <a href="javascript:void(0);" class="icon" onClick="adjustTopnav();"><i class="fa fa-bars"></i></a>
    </nav>

    <main style="flex: 1;">
        <br>
        <div class="navTabsContainer">
            <div class="tabs">
                <button class="navTab" id="defaultTab" onClick="openTab(event, 'cpu');">CPUs</button>   <!-- this tab has an ID because it is opened on page load by default -->
                <button class="navTab" onClick="openTab(event, 'motherboards');">Motherboards</button>
                <button class="navTab" onClick="openTab(event, 'gpu');">GPUs</button>
                <button class="navTab" onClick="openTab(event, 'ram');">RAM</button>
                <button class="navTab" onClick="openTab(event, 'ssd');">SSDs</button>
                <button class="navTab" onClick="openTab(event, 'psu');">PSUs</button>
                <button class="navTab" onClick="openTab(event, 'cases');">Cases</button>
                <button class="navTab" onClick="openTab(event, 'cooling');">Cooling</button>
                <button class="navTab" onClick="openTab(event, 'cables');">Cables</button>
            </div>

            <br>

            <!-- Content for Tab 1: processors -->
            <div id="cpu" class="tabContentContainer">
                <div class="tabContent">
                    <table class="inventoryTable" id="inventoryTable">
                        <tr><th colspan="5" style="font-size: 30px;">Processors</th></tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr class="definingRow">
                            <td>Product Image</td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Stock</td>
                            <td>Action</td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <!-- loop the following lines -->
                        <?php
                            if (isset($_SESSION["accountID"])) {
                                $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType = 'cpu';";
                                $result = mysqli_query($conn, $fetchProductsQuery);
                                // make a new row for every product that is returned from the query
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $productPrice = number_format($row["productPrice"], 2);
                                    echo "<tr class='itemRow'>";
                                    echo "<td class='imageColumn'><img src='".$row["productImagePath"]."'></td>";
                                    echo "<td class='productNameColumn'>".$row["productName"]."</td>";
                                    echo "<td>RM".$productPrice."</td>";
                                    echo "<td>".$row["productStock"]."";
                                    echo "<td><input class='editButtonInv' onclick=\"redirect('editStock.php?id=".$row["productIndex"]."')\" type='button' value='Edit\nStock'></td>";
                                    echo "</tr>";
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                }
                            }
                            else {
                                echo "ERROR: ".mysqli_error($conn);
                            }
                        ?>
                        <!-- end the loop -->
                    </table>
                </div>
            </div>

            <!-- Content for Tab 2: motherboards -->
            <div id="motherboards" class="tabContentContainer">
                <div class="tabContent">
                    <table class="inventoryTable" id="inventoryTable">
                        <tr><th colspan="5" style="font-size: 30px;">Processors</th></tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr class="definingRow">
                            <td>Product Image</td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Stock</td>
                            <td>Action</td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <!-- loop the following lines -->
                        <?php
                            if (isset($_SESSION["accountID"])) {
                                $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType = 'motherboards';";
                                $result = mysqli_query($conn, $fetchProductsQuery);
                                // make a new row for every product that is returned from the query
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $productPrice = number_format($row["productPrice"], 2);
                                    echo "<tr class='itemRow'>";
                                    echo "<td class='imageColumn'><img src='".$row["productImagePath"]."'></td>";
                                    echo "<td class='productNameColumn'>".$row["productName"]."</td>";
                                    echo "<td>RM".$productPrice."</td>";
                                    echo "<td>".$row["productStock"]."";
                                    echo "<td><input class='editButtonInv' onclick=\"redirect('editStock.php?id=".$row["productIndex"]."')\" type='button' value='Edit Stock'></td>";
                                    echo "</tr>";
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                }
                            }
                            else {
                                echo "ERROR: ".mysqli_error($conn);
                            }
                        ?>
                        <!-- end the loop -->
                    </table>
                </div>
            </div>

            <!-- Content for Tab 3: gpus -->
            <div id="gpu" class="tabContentContainer">
                <div class="tabContent">
                    <table class="inventoryTable" id="inventoryTable">
                        <tr><th colspan="5" style="font-size: 30px;">Processors</th></tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr class="definingRow">
                            <td>Product Image</td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Stock</td>
                            <td>Action</td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <!-- loop the following lines -->
                        <?php
                            if (isset($_SESSION["accountID"])) {
                                $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType = 'gpu';";
                                $result = mysqli_query($conn, $fetchProductsQuery);
                                // make a new row for every product that is returned from the query
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $productPrice = number_format($row["productPrice"], 2);
                                    echo "<tr class='itemRow'>";
                                    echo "<td class='imageColumn'><img src='".$row["productImagePath"]."'></td>";
                                    echo "<td class='productNameColumn'>".$row["productName"]."</td>";
                                    echo "<td>RM".$productPrice."</td>";
                                    echo "<td>".$row["productStock"]."";
                                    echo "<td><input class='editButtonInv' onclick=\"redirect('editStock.php?id=".$row["productIndex"]."')\" type='button' value='Edit Stock'></td>";
                                    echo "</tr>";
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                }
                            }
                            else {
                                echo "ERROR: ".mysqli_error($conn);
                            }
                        ?>
                        <!-- end the loop -->
                    </table>
                </div>
            </div>

            <!-- Content for Tab 4: ram -->
            <div id="ram" class="tabContentContainer">
                <div class="tabContent">
                    <table class="inventoryTable" id="inventoryTable">
                        <tr><th colspan="5" style="font-size: 30px;">Processors</th></tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr class="definingRow">
                            <td>Product Image</td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Stock</td>
                            <td>Action</td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <!-- loop the following lines -->
                        <?php
                            if (isset($_SESSION["accountID"])) {
                                $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType = 'ram';";
                                $result = mysqli_query($conn, $fetchProductsQuery);
                                // make a new row for every product that is returned from the query
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $productPrice = number_format($row["productPrice"], 2);
                                    echo "<tr class='itemRow'>";
                                    echo "<td class='imageColumn'><img src='".$row["productImagePath"]."'></td>";
                                    echo "<td class='productNameColumn'>".$row["productName"]."</td>";
                                    echo "<td>RM".$productPrice."</td>";
                                    echo "<td>".$row["productStock"]."";
                                    echo "<td><input class='editButtonInv' onclick=\"redirect('editStock.php?id=".$row["productIndex"]."')\" type='button' value='Edit Stock'></td>";
                                    echo "</tr>";
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                }
                            }
                            else {
                                echo "ERROR: ".mysqli_error($conn);
                            }
                        ?>
                        <!-- end the loop -->
                    </table>
                </div>
            </div>

            <!-- Content for Tab 5: ssd -->
            <div id="ssd" class="tabContentContainer">
                <div class="tabContent">
                    <table class="inventoryTable" id="inventoryTable">
                        <tr><th colspan="5" style="font-size: 30px;">Processors</th></tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr class="definingRow">
                            <td>Product Image</td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Stock</td>
                            <td>Action</td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <!-- loop the following lines -->
                        <?php
                            if (isset($_SESSION["accountID"])) {
                                $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType = 'ssd';";
                                $result = mysqli_query($conn, $fetchProductsQuery);
                                // make a new row for every product that is returned from the query
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $productPrice = number_format($row["productPrice"], 2);
                                    echo "<tr class='itemRow'>";
                                    echo "<td class='imageColumn'><img src='".$row["productImagePath"]."'></td>";
                                    echo "<td class='productNameColumn'>".$row["productName"]."</td>";
                                    echo "<td>RM".$productPrice."</td>";
                                    echo "<td>".$row["productStock"]."";
                                    echo "<td><input class='editButtonInv' onclick=\"redirect('editStock.php?id=".$row["productIndex"]."')\" type='button' value='Edit Stock'></td>";
                                    echo "</tr>";
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                }
                            }
                            else {
                                echo "ERROR: ".mysqli_error($conn);
                            }
                        ?>
                        <!-- end the loop -->
                    </table>
                </div>
            </div>

            <!-- Content for Tab 6: psu -->
            <div id="psu" class="tabContentContainer">
                <div class="tabContent">
                    <table class="inventoryTable" id="inventoryTable">
                        <tr><th colspan="5" style="font-size: 30px;">Processors</th></tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr class="definingRow">
                            <td>Product Image</td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Stock</td>
                            <td>Action</td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <!-- loop the following lines -->
                        <?php
                            if (isset($_SESSION["accountID"])) {
                                $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType = 'psu';";
                                $result = mysqli_query($conn, $fetchProductsQuery);
                                // make a new row for every product that is returned from the query
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $productPrice = number_format($row["productPrice"], 2);
                                    echo "<tr class='itemRow'>";
                                    echo "<td class='imageColumn'><img src='".$row["productImagePath"]."'></td>";
                                    echo "<td class='productNameColumn'>".$row["productName"]."</td>";
                                    echo "<td>RM".$productPrice."</td>";
                                    echo "<td>".$row["productStock"]."";
                                    echo "<td><input class='editButtonInv' onclick=\"redirect('editStock.php?id=".$row["productIndex"]."')\" type='button' value='Edit Stock'></td>";
                                    echo "</tr>";
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                }
                            }
                            else {
                                echo "ERROR: ".mysqli_error($conn);
                            }
                        ?>
                        <!-- end the loop -->
                    </table>
                </div>
            </div>

            <!-- Content for Tab 7: cases -->
            <div id="cases" class="tabContentContainer">
                <div class="tabContent">
                    <table class="inventoryTable" id="inventoryTable">
                        <tr><th colspan="5" style="font-size: 30px;">Processors</th></tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr class="definingRow">
                            <td>Product Image</td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Stock</td>
                            <td>Action</td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <!-- loop the following lines -->
                        <?php
                            if (isset($_SESSION["accountID"])) {
                                $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType = 'cases';";
                                $result = mysqli_query($conn, $fetchProductsQuery);
                                // make a new row for every product that is returned from the query
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $productPrice = number_format($row["productPrice"], 2);
                                    echo "<tr class='itemRow'>";
                                    echo "<td class='imageColumn'><img src='".$row["productImagePath"]."'></td>";
                                    echo "<td class='productNameColumn'>".$row["productName"]."</td>";
                                    echo "<td>RM".$productPrice."</td>";
                                    echo "<td>".$row["productStock"]."";
                                    echo "<td><input class='editButtonInv' onclick=\"redirect('editStock.php?id=".$row["productIndex"]."')\" type='button' value='Edit Stock'></td>";
                                    echo "</tr>";
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                }
                            }
                            else {
                                echo "ERROR: ".mysqli_error($conn);
                            }
                        ?>
                        <!-- end the loop -->
                    </table>
                </div>
            </div>

            <!-- Content for Tab 8: cooling -->
            <div id="cooling" class="tabContentContainer">
                <div class="tabContent">
                    <table class="inventoryTable" id="inventoryTable">
                        <tr><th colspan="5" style="font-size: 30px;">Processors</th></tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr class="definingRow">
                            <td>Product Image</td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Stock</td>
                            <td>Action</td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <!-- loop the following lines -->
                        <?php
                            if (isset($_SESSION["accountID"])) {
                                $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType = 'cooling';";
                                $result = mysqli_query($conn, $fetchProductsQuery);
                                // make a new row for every product that is returned from the query
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $productPrice = number_format($row["productPrice"], 2);
                                    echo "<tr class='itemRow'>";
                                    echo "<td class='imageColumn'><img src='".$row["productImagePath"]."'></td>";
                                    echo "<td class='productNameColumn'>".$row["productName"]."</td>";
                                    echo "<td>RM".$productPrice."</td>";
                                    echo "<td>".$row["productStock"]."";
                                    echo "<td><input class='editButtonInv' onclick=\"redirect('editStock.php?id=".$row["productIndex"]."')\" type='button' value='Edit Stock'></td>";
                                    echo "</tr>";
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                }
                            }
                            else {
                                echo "ERROR: ".mysqli_error($conn);
                            }
                        ?>
                        <!-- end the loop -->
                    </table>
                </div>
            </div>

            <!-- Content for Tab 9: cables -->
            <div id="cables" class="tabContentContainer">
                <div class="tabContent">
                    <table class="inventoryTable" id="inventoryTable">
                        <tr><th colspan="5" style="font-size: 30px;">Processors</th></tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr class="definingRow">
                            <td>Product Image</td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Stock</td>
                            <td>Action</td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>

                        <!-- loop the following lines -->
                        <?php
                            if (isset($_SESSION["accountID"])) {
                                $fetchProductsQuery = "SELECT * FROM catalog_item WHERE productType = 'cables';";
                                $result = mysqli_query($conn, $fetchProductsQuery);
                                // make a new row for every product that is returned from the query
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $productPrice = number_format($row["productPrice"], 2);
                                    echo "<tr class='itemRow'>";
                                    echo "<td class='imageColumn'><img src='".$row["productImagePath"]."'></td>";
                                    echo "<td class='productNameColumn'>".$row["productName"]."</td>";
                                    echo "<td>RM".$productPrice."</td>";
                                    echo "<td>".$row["productStock"]."";
                                    echo "<td><input class='editButtonInv' onclick=\"redirect('editStock.php?id=".$row["productIndex"]."')\" type='button' value='Edit Stock'></td>";
                                    echo "</tr>";
                                    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
                                }
                            }
                            else {
                                echo "ERROR: ".mysqli_error($conn);
                            }
                        ?>
                        <!-- end the loop -->
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>
</body>

</html>