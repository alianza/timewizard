<?php

if ($_SESSION['L_STATUS'] !== 0) {

    if ($_SESSION["L_STATUS"] == 1) {

        $user_ID = $_SESSION["L_ID"];

    } elseif (!isset($_GET["user_ID"])) {

        ?>

        <div class="form">

            <h1>users</h1>

            <p>Kies de user waar u het rapport voor wilt weergeven.</p>

            <?php

            if ($_SESSION['L_STATUS'] == 2) {


                try {
                    $sql = "SELECT * FROM `user`";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();

                } catch (PDOException $e) {

                    echo("<div id='melding'>");

                    echo $e->GetMessage();

                    echo("</div>");

                }

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $user_ID = $row['user_ID'];
                    $voornaam = $row['voornaam'];
                    $tussenvoegsels = $row['tussenvoegsels'];
                    $achternaam = $row['achternaam'];

                    echo " <form action='index.php?page=rapport_1&user_ID=$user_ID' method='post'>

        <div class='field'>

    <input id='input' name='user' type='submit' value='$voornaam $tussenvoegsels $achternaam'>

    <input id='input' name='user_ID' type='hidden' value='$user_ID'>

         </div> </form>";

                }

                if ($stmt->rowCount() == 0) {
                    echo("</div><div id='melding'>Nog geen Users.</div>");
                }

                unset($user_ID);

            } else {

                loginbarrier();
            }

            ?>

        </div>

        <?php

    } else {

        $user_ID = $_GET["user_ID"];

    }

    if (isset($user_ID)) {

        $endDate = date('Y-m-d');
        $startDate = strtotime('-7 day', strtotime($endDate));
        $startDate = date('Y-m-d', $startDate);

        if (isset($_POST["startDate"]) && isset($_POST["endDate"])) {
            $startDate = $_POST["startDate"];
            $endDate = $_POST["endDate"];
        } else if (isset($_GET["startDate"]) && isset($_GET["endDate"])) {
            $startDate = $_GET["startDate"];
            $endDate = $_GET["endDate"];
        }

        $formattedStartDate = date('d-m-Y', strtotime($startDate));
        $FormattedEndDate = date('d-m-Y', strtotime($endDate));

        $datePicker = "<div class='form'><details><summary>Select data range</summary><form name='inloggen' action='index.php?page=rapport_1_ext&user_ID=$user_ID' method='post'><div class='field'>Start<input type='date' id='input' name='startDate' placeholder='Datum' value='$startDate' required> End<input type='date' id='input' name='endDate' placeholder='Datum' value='$endDate' required><input id='submit' name='input' type='submit' value='Go!'></div></form></details></div>";

//    echo("StartDate=" . $startDate . " FormattedStartDate=" . $formattedStartDate . "<br> EndDate=" . $endDate . " FormattedEndDate=" . $FormattedEndDate);

        try {

            $result = false;

            $sql = "SELECT user.voornaam, user.tussenvoegsels, user.achternaam, log.datum, log.opmerking, taak.omschrijving, log.uren, project.projectnaam FROM log INNER JOIN user ON log.user_user_ID = user.user_ID INNER JOIN taak ON log.taak_taak_ID = taak.taak_ID INNER JOIN project ON log.project_project_ID = project.project_ID WHERE log.user_user_ID = :user_ID AND log.datum BETWEEN :startDate AND :endDate ORDER BY `projectnaam` ASC, `datum` DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':user_ID' => $user_ID, ':startDate' => $startDate, ':endDate' => $endDate));

            $output = "<div id='table' align='center'><h1>Overzicht</h1><h3>(Uitgebreid)</h3>
                <table border='5'>
                <tr>
                <th>user</th>
                <th>Projectnaam</th>
                <th>Omschrijving</th>
                <th>Opmerking</th>
                <th>Datum</th>
                <th>uren</th>
                </tr>";

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if (!isset($subtotaal)) {

                $subtotaal = 0;
                $totaal = 0;

            }

            if (isset($projectnaam)) {

                $projectnaamcheck = $projectnaam;

            } else {

                $projectnaamcheck = "";

            }

            $datum = $row['datum'];
            $omschrijving = $row['omschrijving'];
            $uren = $row['uren'];
            $opmerking = $row['opmerking'];
            $projectnaam = $row['projectnaam'];

            if ($result == false) {

                $voornaam = $row['voornaam'];
                $tussenvoegsels = $row['tussenvoegsels'];
                $achternaam = $row['achternaam'];

            } else {

                $voornaam = "";
                $tussenvoegsels = "";
                $achternaam = "";

            }

            if ($projectnaamcheck !== $projectnaam && $projectnaamcheck !== "") {

                $totaal = $totaal + $subtotaal;

                $output .= "<tr>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    </tr>";

                $output .= "<tr>
                    <td> </td>
                    <td> </td>
                    <td>Subtotaal:</td>
                    <td> </td>
                    <td> </td>
                    <td>$subtotaal uur</td>
                    </tr>";

                $output .= "<tr>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    </tr>";

                $subtotaal = 0;

            }

            if ($projectnaam == $projectnaamcheck) {

                $projectnaam = "";

            }

            $output .= "<tr>
                    <td>$voornaam $tussenvoegsels $achternaam</td>
                    <td>$projectnaam</td>
                    <td>$omschrijving</td>
                    <td id='opmerking'>$opmerking</td>
                    <td>$datum</td>
                    <td>$uren uur</td>
                    </tr>";

            $subtotaal = $subtotaal + $uren;

            $result = true;

            if ($projectnaam == "") {

                $projectnaam = $row['projectnaam'];

            }

        }

        if ($result == true) {

            $totaal = $totaal + $subtotaal;

            $output .= "<tr>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    </tr>";

            $output .= "<tr>
                    <td> </td>
                    <td> </td>
                    <td>Subtotaal:</td>
                    <td> </td>
                    <td> </td>
                    <td>$subtotaal uur</td>
                    </tr>";

            $output .= "<tr>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    </tr>";

            $output .= "<tr>
                    <td> </td>
                    <td> </td>
                    <td>Totaal:</td>
                    <td> </td>
                    <td> </td>
                    <td>$totaal uur</td>
                    </tr>";

            $subtotaal = 0;

        }

        $output .= "</table> Showing results from $formattedStartDate to $FormattedEndDate" . "</div>";

        if ($result == false) {

            echo("</div><div id='melding'><h1>Overzicht</h1>Nog geen logs.</div>");

            echo($datePicker);

        } else {

            echo($datePicker);

            echo($output);

            echo("<br><div class='form'><form name='inloggen' action='pages/rapport1-pdf.php' method='post'><div class='field'><textarea name='overzicht' style='display:none;'>$output</textarea><input id='submit' name='input' type='submit' value='Druk af!'></div></form></div>");

        }


    }

} else {

    loginbarrier();

}


?>
