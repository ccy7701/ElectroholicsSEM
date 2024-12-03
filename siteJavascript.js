// external JavaScript document (siteJavascript.js)

// function use: to adjust (toggle) display of navigation bar when screen width <= 600px
function adjustTopnav() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    }
    else {
        x.className = "topnav";
    }
}

// function use: to toggle display of the product categories in the catalogueModule product pages
function adjustCategories() {
    var x = document.getElementById("categories");
    var caretIcon = document.getElementById("caret");
    if (x.style.display === "none" || x.style.display == "") {
        x.style.display = "block";
        caretIcon.classList.remove('fa-caret-down');
        caretIcon.classList.add('fa-caret-up');
    }
    else {
        x.style.display = "none";
        caretIcon.classList.remove('fa-caret-up');
        caretIcon.classList.add('fa-caret-down');        
    }
}

// function use: to switch between Customer and Admin login forms in login.php
function setLoginForm(loginType) {
    if (loginType == 1) {   // 1 - admin
        // display the admin image and login form
        document.getElementById('loginAsAdminImage').style.display = 'inline-block';
        document.getElementById('adminLoginForm').style.display = 'inline-block';
        // add the 'active' class to the admin button
        document.getElementById('loginAsAdminButton').classList.add('active');

        // hide the customer image and login form
        document.getElementById('loginAsCustomerImage').style.display = 'none';
        document.getElementById('customerLoginForm').style.display = 'none';
        // remove the 'active' class from the customer button
        document.getElementById('loginAsCustomerButton').classList.remove('active');
    }
    else if (loginType == 2) {  // 2 - customer
        // display the customer image and login form
        document.getElementById('loginAsCustomerImage').style.display = 'inline-block';
        document.getElementById('customerLoginForm').style.display = 'inline-block';
        // add the 'active' class to the customer button
        document.getElementById('loginAsCustomerButton').classList.add('active');

        // hide the admin image and login form
        document.getElementById('loginAsAdminImage').style.display = 'none';
        document.getElementById('adminLoginForm').style.display = 'none';
        // remove the 'active' class from the admin button
        document.getElementById('loginAsAdminButton').classList.remove('active');
    }
}

// function use: to validate if both password fields at registration page are the same value
function checkMatchingPassword() {
    // get the values at both password fields
    let password = document.getElementById("regPassword").value;
    let reenterPassword = document.getElementById("regReenterPassword").value;

    var passFlag = 0;

    if (password == reenterPassword) {
        passFlag = 1;
        document.getElementById("passwordMatch").style.color = "green";
        document.getElementById("passwordMatch").innerHTML = "Passwords match";
    }
    else {
        passFlag = 0;
        document.getElementById("passwordMatch").style.color = "red";
        document.getElementById("passwordMatch").innerHTML = "Passwords do not match";
    }

    return passFlag;
}

// function use: to validate if the registration details fit required criteria
// and do so dynamically as the user types in the password
function checkPasswordCriteria() {
    // get the value at regPassword
    let password = document.getElementById("regPassword").value;
    
    // criteria flags
    var lengthFlag = numberFlag = capitalFlag = 0;
    var passFlag = 0;

    // check: password length
    if (password.length >= 8) {
        lengthFlag = 1;
        document.getElementById("passwordLength").style.color = "green";
    }
    else {
        lengthFlag = 0;
        document.getElementById("passwordLength").style.color = "red";
    }

    // check: password contains at least one number
    if (/\d/.test(password)) {
        numberFlag = 1;
        document.getElementById("passwordNumber").style.color = "green";
    }
    else {
        lengthFlag = 0;
        document.getElementById("passwordNumber").style.color = "red";
    }

    // check: password contains at least one capital letter
    if (/[A-Z]/.test(password)) {
        capitalFlag = 1;
        document.getElementById("passwordCapital").style.color = "green";
    }
    else {
        capitalFlag = 0;
        document.getElementById("passwordCapital").style.color = "red";
    }

    if (lengthFlag && numberFlag && capitalFlag) {
        passFlag = 1;
    }

    return passFlag;
}

// function use: to validate all registration details before submission
function validateDetails(event) {
    event.preventDefault();     // prevent the form from submitting by default

    if (checkPasswordCriteria() && checkMatchingPassword()) {
        document.getElementById("registrationForm").submit();
    }
    else {
        alert("An error was encountered when submitting your registration information. Please try again.");
    }
}

// function use: to redirect to a different link on click
function redirect(target) {
    window.location.href = target;
}

// function use: to create a Javascript popup to be used inside any block of PHP code
function popup(message, target) {
    let text = message;
    alert(text);
    redirect(target);
}