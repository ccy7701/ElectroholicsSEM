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
        // commit to the database the data from the editable fields ONLY
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $target = mysqli_real_escape_string($conn, $_POST["productIndex"]);
            $productType = mysqli_real_escape_string($conn, $_POST["productType"]);
            $newProductName = mysqli_real_escape_string($conn, $_POST["productName"]);
            $newProductDescription = mysqli_real_escape_string($conn, $_POST["productDescription"]);
            $newProductPrice = str_replace(",", "", mysqli_real_escape_string($conn, $_POST["productPrice"]));
            
            // for image upload
            $productImageUploadFlag = 0;

            // IF THERE IS NO NEW IMAGE
            if (isset($_FILES["productImageToUpload"]) && $_FILES["productImageToUpload"]["name"] == "") {
                $updateDBQuery = "
                    UPDATE catalog_item
                    SET productName = '$newProductName',
                    productDescription = '$newProductDescription', productPrice = '$newProductPrice'
                    WHERE productIndex = '$target'; 
                ";

                if (mysqli_query($conn, $updateDBQuery)) {
                    echo "
                        <script>
                            popup(\"Product info updated successfully.\", \"processors.php\");
                        </script>
                    ";
                }
                else {
                    echo "
                        <script>
                            popup(\"ERROR: ".mysqli_error($conn)."\", \"processors.php\");
                        </script>
                    ";
                }

                mysqli_close($conn);
            }
            // IF THERE IS AN IMAGE
            else if (isset($_FILES["productImageToUpload"]) && $_FILES["productImageToUpload"]["error"] == UPLOAD_ERR_OK) {
                $productImageUploadFlag = 1;
                $targetDirectory = "../images/websiteElements/catalogueIMGs/";

                // determine which catalogueIMG subdirectory to go to
                $subdirectory = "";
                switch ($productType) {
                    case "cpu":
                        $subdirectory = "cpu/"; break;
                    case "motherboards":
                        $subdirectory = "motherboards/"; break;
                    case "gpu":
                        $subdirectory = "gpu/"; break;
                    case "ram":
                        $subdirectory = "ram/"; break;
                    case "ssd":
                        $subdirectory = "ssd/"; break;
                    case "psu":
                        $subdirectory = "psu/"; break;
                    case "cases":
                        $subdirectory = "cases/"; break;
                    case "cooling":
                        $subdirectory = "cooling/"; break;
                    case "cables":
                        $subdirectory = "cables/"; break;
                }

                $filetmp = $_FILES["productImageToUpload"];
                $newProductImageFileName = $filetmp["name"];

                $targetFile = $targetDirectory.$subdirectory.basename($_FILES["productImageToUpload"]["name"]);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // check: if file already exists
                if (file_exists($targetFile)) {
                    echo "
                        <script>
                            popup(\"ERROR(1): File already exists.\", \"processors.php\");
                        </script>
                    ";
                    $productImageUploadFlag = 0;
                }
                // check: if file size > 512KiB or 524288 bytes
                if ($_FILES["productImageToUpload"]["size"] > 524288) {
                    echo "
                        <script>
                            popup(\"ERROR(2): File size exceeds allowed limit.\", \"processors.php\");
                        </script>
                    ";
                    $productImageUploadFlag = 0;
                }
                // check: if file follows file format constraints
                if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                    echo "
                        <script>
                            popup(\"ERROR(3): File must be in .jpg, .jpeg or .png only.\", \"processors.php\");
                        </script>
                    ";
                    $productImageUploadFlag = 0;
                }

                // if the image passes all the above checks, proceed
                if ($productImageUploadFlag) {
                    // first, unlink the current image
                    $imgPathSeekQuery = "SELECT * FROM catalog_item WHERE productIndex='$target'";
                    $return = mysqli_query($conn, $imgPathSeekQuery);
                    $row = mysqli_fetch_assoc($return);
                    $imgToDelete = $row["productImagePath"];
                    if ($imgToDelete != "") {
                        unlink($imgToDelete);
                    }
                    // push the updated data to the DB
                    $imgName = $newProductImageFileName;
                    $fullPath = $targetDirectory.$subdirectory.$imgName;
                    $updateDBQuery = "
                        UPDATE catalog_item
                        SET productName = '$newProductName',
                        productDescription = '$newProductDescription', productPrice = '$newProductPrice',
                        productImagePath = '$fullPath'
                        WHERE productIndex = '$target';
                    ";

                    if (mysqli_query($conn, $updateDBQuery)) {
                        // then, move a copy of the image to the designated fullPath
                        if (move_uploaded_file($_FILES["productImageToUpload"]["tmp_name"], $targetFile)) {
                            echo "
                                <script>
                                    popup(\"Product info updated successfully.\", \"processors.php\");
                                </script>
                            ";
                        }
                        else {
                            echo "
                                <script>
                                    popup(\"ERROR: ".mysqli_error($conn)."\", \"processors.php\");
                                </script>
                            ";
                        }
                    }
                    else {
                        echo "
                            <script>
                                popup(\"ERROR: ".mysqli_error($conn)."\", \"processors.php\");
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