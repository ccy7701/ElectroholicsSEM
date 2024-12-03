<?php
    session_start();
    include("../include/config.php");

    ob_end_clean();

    require("../fpdf186/fpdf.php");

    $id = "";
    $accountID = "";

    if (isset($_GET["id"]) && $_GET["id"] != "") {
        $id = $_GET["id"];
        $accountID = $_SESSION["accountID"];
    }

    // query for all the details from the target cart
    $fetchCartQuery = "
        SELECT
        catalog_item.*,
        item_order.orderQuantity, item_order.orderCost,
        order_receipt.orderID, order_receipt.paymentAmount, order_receipt.orderDatetime,
        cart.cartID,
        user_profile.*,
        account.accountEmail
        FROM order_receipt
        JOIN cart ON order_receipt.cartID = cart.cartID
        JOIN item_order ON cart.cartID = item_order.cartID
        JOIN catalog_item ON item_order.productIndex = catalog_item.productIndex
        JOIN user_profile ON user_profile.userID = cart.userID
        JOIN account ON user_profile.accountID = account.accountID
        WHERE order_receipt.orderID = '$id';
    ";
    $results = mysqli_query($conn, $fetchCartQuery);
    $numRows = mysqli_num_rows($results);

    // customer information
    $orderID = "";
    $userFullName = "";
    $userAddress = "";
    $userContact = "";
    $accountEmail = "";
    $orderDatetime = "";
    $grandTotal = 0.00;
    $itemsData = array();

    while ($row = mysqli_fetch_assoc($results)) {
        $userFullName = $row["userFullName"];
        $userAddress = $row["userAddress"];
        $userContact = "Contact: ".$row["userContact"];
        $accountEmail = "Email: ".$row["accountEmail"];
        $orderID = "Order ID: ".$row["orderID"];
        $orderDatetime = "Order Date: ".$row["orderDatetime"];
        $grandTotal = $row["paymentAmount"];
        // store the item details in this array
        $itemsData[] = $row;
    }

    // start building the PDF
    $pdf = new FPDF('P', 'mm', "A4");
    $pdf->AddPage();
    $pdf->SetTitle("Invoice");

    // the receipt header
    $pdf->SetFont('Helvetica', 'B', 22);
    $pdf->Cell(75, 10, '', 0, 0);
    $pdf->Cell(75, 10, '', 0, 0);
    $pdf->Cell(75, 10, 'INVOICE', 0, 1);
    $pdf->Image("../images/websiteElements/siteElements/electroholicsLogo.png", 158, 18, 40, 40);
    $pdf->SetFont('Helvetica', 'B', 18);
    $pdf->Cell(75, 7, 'Electroholics Sdn. Bhd.', 0, 1);
    $pdf->SetFont('Helvetica', '', 16);
    $pdf->Cell(75, 7, '123 Tech Avenue', 0, 1);
    $pdf->Cell(75, 7, 'Kuala Lumpur', 0, 1);
    $pdf->Cell(75, 7, 'Malaysia', 0, 1);
    $pdf->Cell(75, 7, '+011-5550156', 0, 1);

    // the order ID and datetime
    $pdf->SetFont('Helvetica', '', 14);

    // bill to: customer...
    $pdf->Cell(75, 4, '', 0, 1);
    $pdf->SetFont('Helvetica', 'B', 16);
    $pdf->Cell(100, 7, 'Bill To', 0, 1);
    $pdf->SetFont('Helvetica', '', 14);
    $pdf->Cell(100, 6, $userFullName, 0, 1);
    $pdf->Cell(75, 6, $userAddress, 0, 1);
    $pdf->Cell(115, 6, $userContact, 0, 0);
    $pdf->Cell(75, 6, $orderID, 0, 1);          // part of order ID and datetime at the right
    $pdf->Cell(115, 6, $accountEmail, 0, 0);
    $pdf->Cell(75, 6, $orderDatetime, 0, 1);    // part of order ID and datetime at the right

    // the items table
    $pdf->Cell(75, 12, '', 0, 1);
    $pdf->SetFont('Helvetica', 'B', 14);
    $pdf->Cell(10, 8, 'No.', 1, 0, 'C');
    $pdf->Cell(85, 8, 'Product', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Unit Price (RM)', 1, 0, 'C');
    $pdf->Cell(15, 8, 'Qty.', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Total Price (RM)', 1, 1, 'C');

    $rowIndex = 1;

    // then populate the table with the itemsData array
    $pdf->SetFont('Courier', '', 14);
    foreach ($itemsData as $row) {
        // there are products with very long names. trim them first.
        $trimmedProductName = substr($row["productName"], 0, 26);
        $productString = "[".$row["productID"]."] ".$trimmedProductName;
        $pdf->Cell(10, 8, $rowIndex, 1, 0, 'C');
        $pdf->SetFont('Courier', '', 11);
        $pdf->Cell(85, 8, $productString, 1, 0, 'L');
        $pdf->SetFont('Courier', '', 14);
        $pdf->Cell(40, 8, number_format($row["productPrice"], 2), 1, 0, 'R');
        $pdf->Cell(15, 8, $row["orderQuantity"], 1, 0, 'C');
        $pdf->Cell(40, 8, number_format($row["orderCost"], 2), 1, 1, 'R');
        $rowIndex++;
    }

    // end with the final row that shows the grand total.
    $pdf->SetFont('Helvetica', 'B', 14);
    $pdf->Cell(150, 8, 'Grand Total', 1, 0, 'R');
    $pdf->SetFont('Courier', 'B', 14);
    $pdf->Cell(40, 8, number_format($grandTotal, 2), 1, 1, 'R');

    // set the name of the pdf, if the user so wishes to download it
    $pdf->Output();

    mysqli_close($conn);
?>