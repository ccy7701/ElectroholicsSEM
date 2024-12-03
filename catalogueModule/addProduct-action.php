<?php
    session_start();
    include("../include/config.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<html>
    
<head>
    <title>Add Product Confirmation</title>
    <script src="../siteJavascript.js"></script>
</head>

<body>
    <?php
        // commit to the database the data from the editable fields
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // get all the data to be inserted first
            // productIndex is AUTO_INCREMENT-ed
            $productType = mysqli_real_escape_string($conn, $_POST["productType"]);
            $productName = mysqli_real_escape_string($conn, $_POST["productName"]);
            $productDescription = mysqli_real_escape_string($conn, $_POST["productDescription"]);
            $productPrice = mysqli_real_escape_string($conn, $_POST["productPrice"]);
            $productStock = mysqli_real_escape_string($conn, $_POST["productStock"]);

            // this block of code determines how to insert the productID, e.g. CPU00X
            $newProductType = '';
            switch ($productType) {
                case "cpu": 
                    $newProductType = "CPU"; break;
                case "motherboards": 
                    $newProductType = "MBD"; break;
                case "gpu":
                    $newProductType = "GPU"; break;
                case "ram":
                    $newProductType = "RAM"; break;
                case "ssd":
                    $newProductType = "SSD"; break;
                case "psu":
                    $newProductType = "PSU"; break;
                case "cases":
                    $newProductType = "CAS"; break;
                case "cooling":
                    $newProductType = "CLG"; break;
                case "cables":
                    $newProductType = "CBL"; break;
                default:
                    $newProductType = "UNKNOWN"; break;
            }
            
            $productTypeQuantityQuery = "SELECT * FROM catalog_item WHERE productType='$productType'";
            $results = mysqli_query($conn, $productTypeQuantityQuery);  // all rows returned by the query
            $numRows = mysqli_num_rows($results);
            $newProductIndex = str_pad(($numRows + 1), 3, '0', STR_PAD_LEFT);

            $finalProductID = $newProductType.$newProductIndex;

            // for image upload
            $productImageUploadFlag = 0;

            // IF THERE IS NO NEW IMAGE
            if (isset($_FILES["productImageToUpload"]) && $_FILES["productImageToUpload"]["name"] == "") {
                $addToDBQuery = "INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath)
                VALUES ('$finalProductID', '$productType', '$productName', '$productDescription', '$productPrice', '$productStock', '');
                ";

                if (mysqli_query($conn, $addToDBQuery)) {
                    // if the connection to the DB and the query is successful
                    echo "
                        <script>
                            popup(\"New product added successfully.\", \"processors.php\");
                        </script>
                    ";
                }
                else {
                    echo "
                        <script>
                            popup(\"ERROR: ".$mysqli_error($conn)."\", \"processors.php\");
                        </script>
                    ";
                }

                mysqli_close($conn);
            }
            // IF THERE IS AN IMAGE
            else if (isset($_FILES["productImageToUpload"]) && $_FILES["productImageToUpload"]["error"] == UPLOAD_ERR_OK) {
                $lastInsertedID = mysqli_insert_id($conn);
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
                    // push the data to the DB
                    $imgName = $newProductImageFileName;
                    $fullPath = $targetDirectory.$subdirectory.$imgName;
                    $addToDBQuery = "INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath)
                    VALUES ('$finalProductID', '$productType', '$productName', '$productDescription', '$productPrice', '$productStock', '$fullPath');
                    ";

                    if (mysqli_query($conn, $addToDBQuery)) {
                        // then, move a copy of the image to the designated fullPath
                        if (move_uploaded_file($_FILES["productImageToUpload"]["tmp_name"], $targetFile)) {
                            echo "
                                <script>
                                    popup(\"New product added successfully.\", \"processors.php\");
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