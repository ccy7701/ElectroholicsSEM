<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Login | Electroholics</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../siteJavascript.js"></script>
    <style>
        main {
            min-height: 90vh;
        }
        .row {
            display: flex;
            flex-wrap: wrap;    /* allows wrapping on smaller screens */
        }
        .col-left {
            text-align: center;
            width: 50%;
            border-right: 2px solid #CCCCCC;
        }
        .colLeftImage {
            width: 50%;
        }
        .col-right {
            flex: 2;
            width: 50%;
        }
        .button {
            background-color: #00186F;
            color: #FFFFFF;
            font-size: 18px;
            height: 40px;
            width: 25%;
            border-radius: 10px;
            transition: background-color 0.1s, color 0.1s;
        }
        .button.active {
            background-color: #3366CC;
        }
        .button:hover {
            cursor: pointer;
            background-color: #3366CC;
        }
        .loginForm {
            width: 90%;
            padding-left: 5%;
            padding-right: 5%;
        }
        .loginFormField {
            height: 40px;
            width: 100%;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            margin-top: 5px;
            border: none;
            background-color: #E4E4E4;
        }
        #loginAsAdminImage {
            display: none;  /* hide the Admin image by default */
        }
        #adminLoginForm {
            display: none;  /* hide the Admin form by default */
        }
        @media screen and (max-width: 900px) {
            .colLeftImage {
                width: 80%;
            }
            .button {
                width: 35%;
            }
        }
        @media screen and (max-width: 600px) {
            .row {   /* stack elements vertically on smaller screens */
                flex-direction: column;
                width: 90%;
                padding-left: 5%;
                padding-right: 5%;
            }
            .col-left {
                flex: none;
                box-sizing: border-box;
                width: 100%;
                border-right: none;
                border-bottom: 2px solid #CCCCCC;
                padding-bottom: 30px;
            }
            .colLeftImage {
                width: 80%;
            }
            .col-right {
                flex: none;
                box-sizing: border-box;
                width: 100%;
                padding-top: 15px;
            }
            .button {
                width: 25%;
            }
        }
    </style>
</head>

<body>
    <nav class="topnav" id="myTopnav">
        <a href="../index.php" class="tab"><img src="../images/websiteElements/siteElements/electroholicsLogo.png"><b> ELECTROHOLICS </b></a>
        <a href="../index.php" class="tab"><b>HOME</b></a>
        <a href="../catalogueModule/processors.php" class="tab"><b>PRODUCTS</b></a>
        <a href="login.php" class="tabRight" style="border-bottom: 5px solid #FFFFFF;"><b>LOGIN</b></a>
        <a href="javascript:void(0);" class="icon" onClick="adjustTopnav();"><i class="fa fa-bars"></i></a>
    </nav>

    <main>
        <h1 style="text-align: center;">Login</h1>
        <div class="row">
            <div class="col-left">
                <img class="colLeftImage" id="loginAsCustomerImage" src="../images/websiteElements/siteElements/loginAsCustomer.png">
                <img class="colLeftImage" id="loginAsAdminImage" src="../images/websiteElements/siteElements/loginAsAdmin.png">
                <br>
                <h2>Log in as</h2>
                <input class="button active" id="loginAsCustomerButton" type="button" value="Customer" onclick="setLoginForm(2);">
                <input class="button" id="loginAsAdminButton" type="button" value="Admin" onclick="setLoginForm(1);">
            </div>
            <div class="col-right">
                <br>
                <form class="loginForm" id="customerLoginForm" action="loginAction.php" method="POST">
                    <input name="loginType" type="hidden" value="2">

                    <label for="loginUsernameOrEmail">Username or email address *</label>
                    <input class="loginFormField" name="loginUsernameOrEmail" type="text" placeholder="Customer username/email address" required>

                    <label for="loginPassword">Password *</label>
                    <input class="loginFormField" name="loginPassword" type="password" placeholder="Customer password" required><br>
                
                    <input class="button" name="loginSubmitForm" type="submit" value="LOG IN">
                    <p>Don't have an account yet? <a href="registration.php" style="color: blue;">Register here.</a></p>
                </form>
                <form class="loginForm" id="adminLoginForm" action="loginAction.php" method="POST">
                    <input name="loginType" type="hidden" value="1">

                    <label for="loginUsernameOrEmail">Username or email address *</label>
                    <input class="loginFormField" name="loginUsernameOrEmail" type="text" placeholder="Admin username/email address" required>

                    <label for="loginPassword">Password *</label>
                    <input class="loginFormField" name="loginPassword" type="password" placeholder="Admin password" required><br>
                
                    <input class="button" name="loginSubmitForm" type="submit" value="LOG IN">
                    <p>Don't have an account yet? <a href="registration.php" style="color: blue;">Register here.</a></p>
                </form>
            </div>
        </div>
        <br>
    </main>

    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>
</body>

</html>