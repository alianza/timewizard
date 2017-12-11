<?php

require("../lib/fpdf/fpdf.php");
require("../dbconfig.php");

$project_ID = $_POST['project_ID'];

$pdf = new FPDF();
// First page
$pdf->AddPage();
$pdf->Image('../img/logo.png',10,10,-300);
$pdf->SetFont('Arial','',25);
$pdf->Ln(25);
$pdf->Write(5,"Rapport Overzicht");
$pdf->SetFont('Arial','',12);
$pdf->Ln(12);

try {

            $result = false;

            $sql = "SELECT project.projectnaam, user.voornaam, user.tussenvoegsels, user.achternaam, log.datum, taak.omschrijving, log.uren FROM log INNER JOIN user ON log.user_user_ID = user.user_ID INNER JOIN taak ON log.taak_taak_ID = taak.taak_ID INNER JOIN project ON log.project_project_ID = project.project_ID WHERE project.project_ID = :project_ID ORDER BY `achternaam` ASC, `datum` DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':project_ID' => $project_ID));

     } catch(PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $datum = $row['datum'];
        $omschrijving = $row['omschrijving'];
        $uren = $row['uren'];
        $projectnaam = $row['projectnaam'];
        $voornaam = $row['voornaam'];
         $tussenvoegsels = $row['tussenvoegsels'];
         $achternaam = $row['achternaam'];

        $pdf->Write(5,"$projectnaam -> $voornaam $tussenvoegsels $achternaam - $datum - $omschrijving - $uren uur");
        $pdf->Ln(8);

    }

$pdf->Output();

    ?>
