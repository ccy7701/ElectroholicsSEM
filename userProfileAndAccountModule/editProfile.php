<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
<title>My Profile | Electroholics</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../siteJavascript.js"></script>
    <style>
        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .verticalMenu {
            background-color: #333;
            color: #fff;
            width: 15%;
            min-height: 100vh;
            float: left;
        }
        
        .verticalMenu ul {
            list-style-type: none;
            padding: 0;
        }

        .verticalMenu ul li {
            margin: 0;
            padding: 0;
        }

        .verticalMenu ul li a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            transition: background-color 0.3s;
            font-size: 18px;
        }

        .verticalMenu ul li a:hover {
            background-color: #555;
        }

        .verticalMenu a.active {
            background-color: #555;
            color: #fff;
        }

        .textBody {
            background-color: #444444;
            color: #FFFFFF;
            margin: 0;
            display: flex;
            flex-direction: column;
            padding-left: 12%;
            padding-right: 12%;
            min-height: 100vh;
            flex: 2;
            width: 85%;
        }

        .circle {
            height: 150px;
            width: 150px;
            background-color: #FFF;
            border-radius: 50%;
            border: 2px solid #555555;
            display: inline-block;
        }

        .profileTable {
            width: 100%;
            padding-left: 5%;
            padding-right: 5%;
            border-collapse: collapse;
        }

        .profileTable th {
            background-color: #02134F;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .profileTable td {
            background-color: #D9D9D9;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .innerProfileTable-container {
            text-align: center;
            width: 84%;
            padding-left: 8%;
            padding-right: 8%;
        }

        .innerProfileTable {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #555555;
        }

        .innerProfileTable td {
            background-color: #FFFFFF;
            color: #000000;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .editButton {
            width: 20%;
            background-color: #6573A3;
            color: #FFF;
            border: none;
            padding: 10px 20px; /* Adjust the padding as needed */
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
            text-align: center;
        }

        .editButton:hover {
            background-color: #C5CBE0;
            color: #000000;
        }

        .profileForm .textfield {
            height: 30px;
            width: 90%;
            display: inline-block;
            font-size: 16px;
            /* */
        }

        @media screen and (max-width: 960px) {
            .verticalMenu {
                width: 30%;
            }
            .textBody {
                width: 70%;
                padding-left: 5%;
                padding-right: 5%;
            }
            .editButton {
                width: 40%;
            }
        }

        @media screen and (max-width: 600px) {
            .verticalMenu {
                width: 100%;
                padding: 0;
                min-height: 0vh;
                display: float;
                float: center;
            }

            .textBody {
                flex: none;
                box-sizing: border-box;
                width: 100%;
                min-height: 80vh;
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
                    echo "<a href='../userProfileAndAccountModule/profile.php' class='active'><b><i class='fa fa-user-circle-o'></i> $username</b></a>";
                    echo "<a href='../userAuthenticationModule/logout.php' class='tabRight'><b>LOGOUT</b></a>";
                }
            }
            else {  // if a session is not active
                echo "<a href='userAuthenticationModule/login.php' class='tabRight'><b>LOGIN</b></a>";
            }

        ?>
        <a href="javascript:void(0);" class="icon" onClick="adjustTopnav();"><i class="fa fa-bars"></i></a>
    </nav>

    <main style="flex: 1;">
        <div class="row">
            <div class="verticalMenu">
                <ul>
                    <div id="category-links">
                        <li style="display: block; text-align: center; padding-bottom: 20px; font-size: 20px;"><b>Menu</b></li>
                        <li><a href="profile.php" class="active">Profile</a></li>
                        <?php
                            // the customer can see this Order History tab. the admin cannot.
                            if ($accountRole == 2) {
                                echo "<li><a href='orderHistory.php'>Order History</a></li>";
                            }
                        ?>
                    </div>
                </ul>
            </div>

            <div class="textBody">
                <br>

                <form class="profileForm" id="profileForm" action="editProfile-action.php" method="POST" enctype="multipart/form-data">
                    <table class="profileTable">
                        <?php
                            // fetch profile data from database
                            $profileQuery = "SELECT account.username, account.accountEmail, user_profile.accountID, userID, userFullName, userAddress, userContact, userDOB, userProfileImagePath
                            FROM user_profile
                            JOIN account ON user_profile.accountID = account.accountID
                            WHERE user_profile.accountID = '$accountID' LIMIT 1";
                            $result = mysqli_query($conn, $profileQuery);
                            $row = mysqli_fetch_assoc($result);
                            $username = $row["username"];
                            $accountEmail = $row["accountEmail"];
                            $currentFullName = $row["userFullName"];
                            $currentAddress = $row["userAddress"];
                            $currentContact = $row["userContact"];
                            $currentDOB = $row["userDOB"];
                            $currentProfileImagePath = $row["userProfileImagePath"];

                            mysqli_close($conn);
                        ?>
                        <tr>
                            <th style="text-align: left; padding-left: 10px;">
                                <?php
                                    if ($accountRole == 1) {
                                        echo "ID: ".$row["accountID"]." | Admin";
                                    }
                                    else if ($accountRole == 2) {
                                        echo "ID: ".$row["accountID"]." | Customer";
                                    }
                                ?>
                            </th>
                            <th style="text-align: right; padding-right: 10px;">
                                <input class="editButton" name="buttonSubmit" type="submit" value="EDIT">
                                <input class="editButton" name="buttonCancel" type="button" onclick="history.back();" value="CANCEL">
                            </th>
                        </tr>
                        <tr>
                            <script type="text/javascript">
                                // there is an image upload tool here. this function previews the image to the user
                                var loadFile = function(event) {
                                    var reader = new FileReader();
                                    reader.onload = function() {
                                        var output = document.getElementById('output');
                                        output.src = reader.result;
                                    };
                                    reader.readAsDataURL(event.target.files[0]);
                                }
                            </script>

                            <td colspan="2" style="color: black; text-align: center;">
                                <input class="pfpToUpload" id="pfpToUpload" name="pfpToUpload" type="file" accept=".jpg, .jpeg, .png" onchange="loadFile(event)"><br>
                                <br>
                                <img class="circle" id="output" src="<?=$row["userProfileImagePath"];?>"><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center; align-items: center;">
                                <div class="innerProfileTable-container">
                                    <table class="innerProfileTable">
                                        <tr>
                                            <td><b>Username</b></td>
                                            <td style="text-align: left;"><input class="textfield" id="username" name="username" type="text" value="<?=$username;?>" disabled></td>
                                        </tr>
                                        <tr>
                                            <td><b>Full Name</b></td>
                                            <td style="text-align: left;"><input class="textfield" id="userFullName" name="userFullName" type="text" value="<?=$currentFullName;?>" required></td>
                                        </tr>
                                        <tr>
                                            <td><b>Address</b></td>
                                            <td style="text-align: left;"><input class="textfield" id="userAddress" name="userAddress" type="text" value="<?=$currentAddress;?>" required></td>
                                        </tr>
                                        <tr>
                                            <td><b>Email</b></td>
                                            <td style="text-align: left;"><input class="textfield" id="accountEmail" name="accountEmail" type="text" value="<?=$accountEmail;?>" disabled></td>
                                        </tr>
                                        <tr>
                                            <td><b>Contact</b></td>
                                            <td style="text-align: left;"><input class="textfield" id="userContact" name="userContact" type="text" value="<?=$currentContact;?>" required></td>
                                        </tr>
                                        <tr>
                                            <td><b>Date of Birth</b></td>
                                            <td style="text-align: left;"><input class="textfield" id="userDOB" name="userDOB" type="date" value="<?=$currentDOB;?>" required></td>
                                        </tr>
                                    </table>
                                </div>
                                <br>
                            </td>
                        </tr>
                    </table>
                </form>
                
                <br>
            </div>
        </div>
    </main>

</body>