<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>

<head>
    <title>Edit Confirmation</title>
    <script src="../siteJavascript.js"></script>
</head>

<body>
    <?php
        // commit to the database the data from the editable fields
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $target = $_SESSION["accountID"];
            $newUserFullName = mysqli_real_escape_string($conn, $_POST["userFullName"]);
            $newUserAddress = mysqli_real_escape_string($conn, $_POST["userAddress"]);
            $newUserContact = mysqli_real_escape_string($conn, $_POST["userContact"]);
            $newUserDOB = mysqli_real_escape_string($conn, $_POST["userDOB"]);

            // for image upload
            $pfpImageUploadFlag = 0;

            // IF THERE IS NO NEW IMAGE
            if (isset($_FILES["pfpToUpload"]) && $_FILES["pfpToUpload"]["name"] == "") {
                $updateDBQuery = "
                    UPDATE user_profile
                    SET userFullName = '$newUserFullName', userAddress = '$newUserAddress',
                    userContact = '$newUserContact', userDOB = '$newUserDOB'
                    WHERE accountID = '$target';
                ";
                
                if (mysqli_query($conn, $updateDBQuery)) {
                    echo "Profile update was successful.";
                    header("location: profile.php");
                }
                else {
                    echo "
                        <script>
                            popup(\"ERROR: ".mysqli_error($conn)."\", \"profile.php\");
                        </script>
                    ";
                }

                mysqli_close($conn);
            }
            // IF THERE IS AN IMAGE
            else if (isset($_FILES["pfpToUpload"]) && $_FILES["pfpToUpload"]["error"] == UPLOAD_ERR_OK) {
                $pfpImageUploadFlag = 1;
                $targetDirectory = "../images/profilePictures/";

                $filetmp = $_FILES["pfpToUpload"];
                $newPfpImageFileName = $filetmp["name"];

                $targetFile = $targetDirectory.basename($_FILES["pfpToUpload"]["name"]);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // check: if file already exists
                if (file_exists($targetFile)) {
                    echo "
                        <script>
                            popup(\"ERROR(1): File already exists.\", \"profile.php\");
                        </script>
                    ";
                    $pfpImageUploadFlag = 0;
                }
                // check: if file size > 512KiB or 524288 bytes
                if ($_FILES["pfpToUpload"]["size"] > 524288) {
                    echo "
                        <script>
                            popup(\"ERROR(2): File size exceeds allowed limit (512KB).\", \"profile.php\");
                        </script>
                    ";
                    $pfpImageUploadFlag = 0;
                }
                // check: if file follows file format constraints
                if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                    echo "
                        <script>
                            popup(\"ERROR(3): File must be in .jpg, .jpeg or .png only.\", \"profile.php\");
                        </script>
                    ";
                    $pfpImageUploadFlag = 0;
                }

                // if the image passes all the above checks, proceed
                if ($pfpImageUploadFlag) {
                    // first, unlink the current image
                    $imgPathSeekQuery = "SELECT * FROM user_profile WHERE accountID='$target'";
                    $return = mysqli_query($conn, $imgPathSeekQuery);
                    $row = mysqli_fetch_assoc($return);
                    $imgToDelete = $row["userProfileImagePath"];
                    if ($imgToDelete != "") {
                        unlink($imgToDelete);
                    }
                    // push the updated data to the DB
                    $imgName = $newPfpImageFileName;
                    $fullPath = $targetDirectory.$imgName;
                    $updateDBQuery = "
                        UPDATE user_profile
                        SET userFullName = '$newUserFullName', userAddress = '$newUserAddress',
                        userContact = '$newUserContact', userDOB = '$newUserDOB',
                        userProfileImagePath = '$fullPath'
                        WHERE accountID = '$target';
                    ";

                    if (mysqli_query($conn, $updateDBQuery)) {
                        // then, move a copy of the image to the designated fullPath
                        if (move_uploaded_file($_FILES["pfpToUpload"]["tmp_name"], $targetFile)) {
                            echo "Profile update was successful.";
                            header("location: profile.php");
                        }
                        else {
                            echo "
                                <script>
                                    popup(\"ERROR: ".mysqli_error($conn)."\", \"profile.php\");
                                </script>
                            ";
                        }
                    }
                    else {
                        echo "
                            <script>
                                popup(\"ERROR: ".mysqli_error($conn)."\", \"profile.php\");
                            </script>
                        ";
                    }
                }

                mysqli_close($conn);
            }
        }


    ?>
</body>

</html>