<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Registration | Electroholics</title>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../siteJavascript.js"></script>
    <style>
        main {
            min-height: 100vh;
        }
        .registrationFormContainer {
            padding-left: 20%;
            padding-right: 20%;
        }
        .registrationForm {
            width: 80%;
            padding-left: 10%;
            padding-right: 10%;
            border: 1px solid #999999;
        }
        .regFormRadio {
            margin-top: 10px;
        }
        .regFormField {
            height: 40px;
            width: 100%;
            display: block;
            font-size: 18px;
            border: none;
            background-color: #E4E4E4;
            margin-top: 5px;
            margin-bottom: 5px;
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
        .button:hover {
            cursor: pointer;
            background-color: #3366CC;
        }
        @media screen and (max-width: 600px) {
            .registrationFormContainer {
                padding-left: 10%;
                padding-right: 10%;
            }
            .registrationForm {
                width: 92%;
                padding-left: 6%;
                padding-right: 6%;
            }
            .button {
                width: 35%;
            }
        }
    </style>
</head>

<body>
    <nav class="topnav" id="myTopnav">
        <a href="../index.php" class="tab"><img src="../images/websiteElements/siteElements/electroholicsLogo.png"><b> ELECTROHOLICS </b></a>
        <a href="../index.php" class="tab"><b>HOME</b></a>
        <a href="../products.php" class="tab"><b>PRODUCTS</b></a>
        <a href="login.php" class="tabRight" style="border-bottom: 5px solid #FFFFFF;"><b>LOGIN</b></a>
        <a href="javascript:void(0);" class="icon" onClick="adjustTopnav();"><i class="fa fa-bars"></i></a>
    </nav>

    <main>
        <h2 style="text-align: center;">Registration</h2>
        <div class="registrationFormContainer">
            <form class="registrationForm" id="registrationForm" onsubmit="validateDetails(event);" action="registrationAction.php" method="POST">
                <br>
                <label for="regAccountRole">I am registering a new account as a</label><br>
                <input class="regFormRadio" name="regAccountRole" type="radio" value="2" checked required><label for="customer">Customer</label>
                <input class="regFormRadio" name="regAccountRole" type="radio" value="1" required><label for="Admin">Admin</label>
                <br><br>

                <label for="regUsername">Username *</label>
                <input class="regFormField" name="regUsername" type="text" placeholder="" required><br>

                <label for="regEmailAddress">Email Address *</label>
                <input class="regFormField" name="regEmailAddress" type="email" placeholder="" required><br>

                <label for="regPassword">Password *</label>
                <input class="regFormField" id="regPassword" name="regPassword" type="password" oninput="checkPasswordCriteria(); checkMatchingPassword();" required>
                <p id="passwordLength" style="margin: 0; color: red">Password at least 8 characters long</p>
                <p id="passwordNumber" style="margin: 0; color: red">Password has at least one number</p>
                <p id="passwordCapital" style="margin: 0; color: red">Password has at least one capital letter</p><br>

                <label for="regReenterPassword">Reenter Password *</label>
                <input class="regFormField" id="regReenterPassword" name="regReenterPassword" type="password" oninput="checkMatchingPassword();" required>
                <p id="passwordMatch" style="margin: 0; color: red">Passwords do not match</p><br>

                <div id="centerContent" style="text-align: center">
                    <input class="button" name="regSubmit" type="submit" value="REGISTER">
                </div>
                <br><br>
            </form>
        </div>
        <br>
    </main>

    <footer>
        <h5>Chiew Cheng Yi | Christopher Wong Sen Li | Carl Brandon Valentine | Danny Mickenzie anak Reda</h5>
    </footer>
</body>

</html>