<?php
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Registration Action | Electroholics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Jost">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../sitejavascript.js"></script>
</head>

<body>
    <?php
        // STEP 1: Form data handling using mysqli_real_escape_string() function to escape special characters for use in an SQL query
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $accountRole = mysqli_real_escape_string($conn, $_POST["regAccountRole"]);
            $username = mysqli_real_escape_string($conn, $_POST["regUsername"]);
            $accountEmail = mysqli_real_escape_string($conn, $_POST["regEmailAddress"]);
            $accountPassword = mysqli_real_escape_string($conn, $_POST["regPassword"]);
        }

        $uniqueUsernameFlag = 0;
        $uniqueEmailFlag = 0;

        // validation for whether an account with this username already exists
        $usernameQuery = "SELECT * FROM account WHERE username='".$username."';";
        $result = mysqli_query($conn, $usernameQuery);

        if (mysqli_num_rows($result) == 1) {    // i.e. a row is returned, so a row with this username does already exist
            echo "<script>popup(\"ERROR: An account with this username already exists. Please register using a new username.\", \"registration.php\");</script>";
        }
        else {
            $uniqueUsernameFlag = 1;
        }

        // validation for whether an account with this email already exists
        $emailQuery = "SELECT * FROM account WHERE accountEmail='".$accountEmail."'";
        $result = mysqli_query($conn, $emailQuery);
        
        if (mysqli_num_rows($result) == 1) {
            echo "<script>popup(\"ERROR: An account with this email already exists. Please register using a new email.\", \"registration.php\");</script>";
        }
        else {
            $uniqueEmailFlag = 1;
        }

        if ($uniqueEmailFlag && $uniqueUsernameFlag) {  // proceed to push this new entry to the database
            $hashedPassword = trim(password_hash($accountPassword, PASSWORD_DEFAULT));
            $pushToAccountQuery = "INSERT INTO account (accountEmail, accountPassword, username, accountRole) VALUES
            ('$accountEmail', '$hashedPassword', '$username', '$accountRole');";

            if (mysqli_query($conn, $pushToAccountQuery)) {  // assuming the account was created successfully, then a new profile row is also created
                $lastInsertedID = mysqli_insert_id($conn);

                $pushToProfileQuery = "INSERT INTO user_profile (accountID, userFullName, userAddress, userContact, userDOB, userProfileImagePath) VALUES 
                ('$lastInsertedID', '', '', '', '', '');";

                if (mysqli_query($conn, $pushToProfileQuery)) {
                    $pushToCartQuery = "INSERT INTO cart (userID, totalCost, isActive) VALUES
                    ('$lastInsertedID', 0.00, 1);";
                    
                    if (mysqli_query($conn, $pushToCartQuery)) {
                        echo "<script>popup(\"New account created successfully. You will now be redirected to the login page.\", \"login.php\");</script>";
                    }
                    else {
                        $errorMessage = mysqli_error($conn);
                    echo "<script>popup(\"ERROR: '$errorMessage', \"registration.php\")</script>";
                    }
                }
                else {
                    $errorMessage = mysqli_error($conn);
                    echo "<script>popup(\"ERROR: '$errorMessage', \"registration.php\")</script>";
                }
            }
            else {
                $errorMessage = mysqli_error($conn);
                echo "<script>popup(\"ERROR: '$errorMessage', \"registration.php\")</script>";
            }
        }
        else {
            $errorMessage = mysqli_error($conn);
            echo "<script>popup(\"ERROR: '$errorMessage', \"registration.php\")</script>";
        }
        mysqli_close($conn);
    ?>
</body>

</html>