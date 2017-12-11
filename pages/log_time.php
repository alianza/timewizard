<?php

if ($_SESSION['L_STATUS'] == 1) {

    $user_ID = $_SESSION['L_ID'];

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['project_id']) && isset($_POST['projectnaam']) && isset($_POST['taak']) && $_POST['uren'] !== "" && isset($_POST['omschrijving']) && isset($_POST['datum']) && isset($_POST['url'])) {

    $project_ID = $_POST['project_id'];
    $projectnaam = $_POST['projectnaam'];
    $taak = $_POST['taak'];
    $uren = $_POST['uren'];
    $omschrijving = $_POST['omschrijving'];
    $datum = $_POST['datum'];

try {

            $sql = "INSERT INTO `log`(`datum`, `uren`, `taak_taak_ID`, `user_user_ID`, `project_project_ID`) VALUES (:datum,:uren,:taak,:user_ID,:project_ID)";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(':datum' => $datum, ':uren' => $uren, ':taak' => $taak, ':user_ID' => $user_ID, ':project_ID' => $project_ID));

                echo("<div id='melding'>Uren gelogd!</div> <div id='melding'><h2>Overzicht</h2></div>");

            $output = "<table class='outputtable' >
                        <tr>
                        <td>user</td>
                        <td>Project</td>
                        <td>taak</td>
                        <td>Datum</td>
                        <td>uren</td>
                        </tr>
                        <tr>
                        <td>" . $_SESSION['L_NAME'] . "</td>
                        <td>$projectnaam</td>
                        <td>$omschrijving</td>
                        <td>$datum</td>
                        <td>$uren Uur</td>
                        </tr>
                        </table>";

    echo ($output);

    $output = strrev($output);

    echo("<br><div class='form'><form name='inloggen' action='pages/overzicht-pdf.php' method='post'><div class='field'><input id='input' name='overzicht' type='hidden' value='$output'><input id='submit' name='input' type='submit' value='Druk af!'></div></form></div>");

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div");

        }

} else {

    $url = $_POST['url'];

    echo("<script>goto('$url');</script>");

    echo("<div id='melding'>Geen project_ID en taak_ID opgegeven ga <a href='$url'>Hier</a> terug!</div>");

}

} else {

    loginbarrier();

}

?>
