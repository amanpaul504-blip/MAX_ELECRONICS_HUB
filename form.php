<?php
$require('fpdf/fpdf.php');
$pdf -> new fpdf();
$pdf -> AddPage();
$pdf ->SetFont('Arial','B',16);
include 'appl/appl.php';
$pdf -> setTitle($title);
$sql = "Select "
$Conn->OpenConn();

// arial bold IS
$pdf -> SetFont('Arial', 'B',1s);
$w = $pdf ->GetStringwidth($title)+6;

$pdf -> setX((210-$w)/2);
$pdf -> cell(40,10, 'First Name:', 1, 0, 'c');
$pdf -> cell($w, 10, $fname,1,1,'c');

$pdf -> setX((210-$w)/2);
$pdf -> cell(40,10, 'Last Name:', 1, 0, 'c');
$pdf -> cell($w, 10, $lname,1,1,'c');

$pdf ->Output();

?>


