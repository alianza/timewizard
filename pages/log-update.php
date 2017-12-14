    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>

        <?php

if ($_SESSION['L_STATUS'] == 2) {

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sql']) && isset($_POST['log_ID'])) {

    $query = $_POST['sql'];
    $oudelog_ID = $_POST['ID'];
    $errors = array();
    $log_ID = $datum = $uren = $opmerking = $taak_ID = $user_ID = $project_ID = "";
    $url = $_POST['url'];

    if (isset($_POST['verwijder'])) {

        try {

            $sql = "DELETE FROM `log` WHERE `log_ID` = :oudelog_ID";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':oudelog_ID' => $oudelog_ID));

            echo("<div id='melding'>log met ID: $oudelog_ID verwijderd!</div>");

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div>");

        }

        die();

    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

     if (empty($_POST["log_ID"])) {
     $errors['log_ID'] = "log_ID is vereist";
   } else {
     $log_ID = test_input($_POST["log_ID"]);
     if (!is_numeric($log_ID)) {
     $errors['log_ID'] = "Alleen cijfers toegestaan!";
     }
   }

   if (empty($_POST["datum"])) {
     $errors['datum'] = "geboortedatum is vereist!";
   } else {
     $datum = test_input($_POST["datum"]);
   }

    if (empty($_POST["uren"])) {
     $errors['uren'] = "uren is vereist";
    } else {
     $uren = test_input($_POST["uren"]);
     if (!is_numeric($uren)) {
     $errors['uren'] = "Alleen cijfers toegestaan!";
     }
   }

    $opmerking = test_input($_POST["opmerking"]);

    if (empty($_POST["taak_taak_ID"])) {
     $errors['taak_taak_ID'] = "taak_ID is vereist";
    } else {
     $taak_ID = test_input($_POST["taak_taak_ID"]);
     if (!is_numeric($taak_ID)) {
     $errors['taak_taak_ID'] = "Alleen cijfers toegestaan!";
     }
   }

    if (empty($_POST["user_user_ID"])) {
     $errors['user_user_ID'] = "user_user_ID is vereist";
    } else {
     $user_ID = test_input($_POST["user_user_ID"]);
     if (!is_numeric($user_ID)) {
     $errors['user_user_ID'] = "Alleen cijfers toegestaan!";
     }
   }

    if (empty($_POST["project_project_ID"])) {
     $errors['project_project_ID'] = "project_project_ID is vereist";
    } else {
     $project_ID = test_input($_POST["project_project_ID"]);
     if (!is_numeric($project_ID)) {
     $errors['project_project_ID'] = "Alleen cijfers toegestaan!";
     }
   }

}

 if (!$errors) {

        try {

            $stmt = $db->prepare($query);
            $stmt->execute(array(':newlog_ID' => $log_ID, ':datum' => $datum, ':uren' => $uren, ':opmerking' => $opmerking, ':taak_ID' => $taak_ID, ':user_ID' => $user_ID, ':project_ID' => $project_ID, ':log_ID' => $oudelog_ID));

            echo("<div id='melding'>log met ID: $log_ID bijgewerkt! <br>Ga hier <a href='$url'>Terug</a></div>");


        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div>");

        }

    } else {

     echo("<div id='melding'>");

     foreach($errors as $value) {

         $key = key($errors);

         echo("Fout bij: $key - $value <br>");

         next($errors);

        }

     echo("<br>Ga hier <a href='index.php?page=kiestabel'>Terug</a>.");

     echo("</div>");

 }

} else {

    echo("<script>goto('index.php?page=kiestabel');</script>");

    echo("<div id='melding'>Geen Item ID opgegeven ga <a href='index.php?page=kiestabel'>Hier</a> terug!</div>");

}

} else {

    loginbarrier();

}

?>
