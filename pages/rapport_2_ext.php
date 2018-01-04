<?php

if ($_SESSION['L_STATUS'] !== 0) {

if (isset($_GET["project_ID"])) {

    $project_ID = $_GET["project_ID"];

} else {

    ?>

    <div class="form">

        <h1>Projecten</h1>

        <p>Kies het Project waar u het rapport voor wilt weergeven.</p>

<?php

        if ($_SESSION['L_STATUS'] == 2) {

    try {
            $sql = "SELECT * FROM `project`";
            $stmt = $db->prepare($sql);
            $stmt->execute();

        } catch(PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $project_ID = $row['project_ID'];
            $projectnaam = $row['projectnaam'];

        echo " <form action='index.php?page=rapport_2&project_ID=$project_ID' method='post'>

        <div class='field'>

    <input id='input' name='projectnaam' type='submit' value='$projectnaam'>

    <input id='input' name='project_ID' type='hidden' value='$project_ID'>

         </div> </form>";

    }

            if ($stmt->rowCount() == 0) {
                echo("</div><div id='melding'>Nog geen Projecten.</div>");
            }

        unset($project_ID);

        } else {

            loginbarrier();

        }

    ?>

        </div>
<?php

}

if (isset($project_ID)) {

    try {

            $result = false;

            $sql = "SELECT project.projectnaam, user.voornaam, user.tussenvoegsels, user.achternaam, log.datum, log.opmerking, taak.omschrijving, log.uren FROM log INNER JOIN user ON log.user_user_ID = user.user_ID INNER JOIN taak ON log.taak_taak_ID = taak.taak_ID INNER JOIN project ON log.project_project_ID = project.project_ID WHERE project.project_ID = :project_ID ORDER BY `achternaam` ASC, `datum` DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':project_ID' => $project_ID));

        $output = "<div id='table' align='center'><h1>Overzicht</h1>
                <h3>(Uitgebreid)</h3>
				<table border='5'>
                <tr>
                <th>ProjectNaam</th>
                <th>user</th>
                <th>Omschrijving</th>
                <th>Opmerking</th>
                <th>Datum</th>
                <th>uren</th>
                </tr>";

        } catch(PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        if (!isset($subtotaal)) {

            $subtotaal = 0;
            $totaal = 0;

        }

        if (isset($user)) {

        $usercheck = $user;

        } else {

            $usercheck = "";

        }

        $datum = $row['datum'];
        $omschrijving = $row['omschrijving'];
        $opmerking = $row['opmerking'];
        $uren = $row['uren'];
        $user = $row['voornaam'] . " " . $row['tussenvoegsels'] . " " . $row['achternaam'];

        if ($result == false) {

            $projectnaam = $row['projectnaam'];

        } else {

            $projectnaam = "";

        }

        if ($usercheck !== $user && $usercheck !== "") {

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

        if ($user == $usercheck) {

            $user = "";

        }

        $output .= "<tr>
                    <td>$projectnaam</td>
                    <td>$user</td>
                    <td>$omschrijving</td>
                    <td>$opmerking</td>
                    <td>$datum</td>
                    <td>$uren uur</td>
                    </tr>";

        $subtotaal = $subtotaal + $uren;

        $result = true;

        if ($user == "") {

            $user = $row['voornaam'] . " " . $row['tussenvoegsels'] . " " . $row['achternaam'];

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

        $output .= "</table></div>";

        if ($result == false) {

             echo("<div id='melding'>Nog geen logs.</div>");

        } else {

            echo($output);

            echo("<br><div class='form'><form name='inloggen' action='pages/rapport2-pdf.php' method='post'><div class='field'><textarea name='overzicht' style='display:none;'>$output</textarea><input id='submit' name='input' type='submit' value='Druk af!'></div></form></div>");

        }

}

        } else {

     loginbarrier();

}

?>
