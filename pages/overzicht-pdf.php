<?php

require("../lib/pdftable/html_table.php");

$overzicht = str_replace("th", "td", $_POST['overzicht']);

$pdf = new PDF_HTML_Table();
// First page
$pdf->AddPage();
$pdf->Image('../img/logo.png', 10, 10, -300);
$pdf->SetFont('Arial', '', 25);
$pdf->WriteHTML("<br><br><br><br><br>");
$pdf->Write(5, "                             Log-Overzicht");
$pdf->SetFont('Arial', '', 12);
$pdf->WriteHTML("<br><br><br><br>");
$pdf->WriteHTML($overzicht);
$pdf->Output();
?>
