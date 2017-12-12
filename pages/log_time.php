<?php

if ($_SESSION['L_STATUS'] == 1) {

    $user_ID = $_SESSION['L_ID'];

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['project_id']) && isset($_POST['projectnaam']) && isset($_POST['taak']) && $_POST['uren'] !== "" && isset($_POST['omschrijving']) && isset($_POST['datum']) && isset($_POST['opmerking']) && isset($_POST['url'])) {

    $project_ID = $_POST['project_id'];
    $projectnaam = $_POST['projectnaam'];
    $taak = $_POST['taak'];
    $uren = $_POST['uren'];
    $omschrijving = $_POST['omschrijving'];
    $datum = $_POST['datum'];
    $opmerking = $_POST['opmerking'];

try {

            $sql = "INSERT INTO `log`(`datum`, `uren`, `opmerking`, `taak_taak_ID`, `user_user_ID`, `project_project_ID`) VALUES (:datum,:uren,:opmerking,:taak,:user_ID,:project_ID)";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(':datum' => $datum, ':uren' => $uren, ':opmerking' => $opmerking, ':taak' => $taak, ':user_ID' => $user_ID, ':project_ID' => $project_ID));

                echo("<div id='melding'>Uren gelogd!</div> <div id='melding'><h2>Overzicht</h2></div>");

            $output = "<table class='outputtable' >
                        <tr>
                        <th>user</th>
                        <th>Project</th>
                        <th>taak</th>
                        <th>Datum</th>
                        <th>uren</th>
                        <th>opmerking</th>
                        </tr>
                        <tr>
                        <td>" . $_SESSION['L_NAME'] . "</td>
                        <td>$projectnaam</td>
                        <td>$omschrijving</td>
                        <td>$datum</td>
                        <td>$uren Uur</td>
                        <td>$opmerking</td>
                        </tr>
                        </table>";

    echo ($output);

//    $output = strrev($output);

    echo("<br><div class='form'><form name='inloggen' action='pages/overzicht-pdf.php' method='post'><div class='field'><textarea name='overzicht' style='display: none;'>$output</textarea><input id='submit' name='input' type='submit' value='Druk af!'></div></form></div>");

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div");

        }

} else {

//    $url = $_POST['url'];
//
//    echo("<script>goto('$url');</script>");

    echo("<div id='melding'>Geen project_ID en taak_ID opgegeven ga <a href='index.php?page=log_time_choose_project'>Hier</a> terug!</div>");

}

} else {

    loginbarrier();

}

?>
