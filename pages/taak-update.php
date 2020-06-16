<head>
    <meta http-equiv="Content-Type" content="text/html;
              charset=UTF-8">
</head>

<?php

if ($_SESSION['L_STATUS'] == 2) {

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sql']) && isset($_POST['taak_ID'])) {

        $query = $_POST['sql'];
        $oudetaak_ID = $_POST['ID'];
        $errors = array();
        $taak_ID = $omschrijving = "";
        $url = $_POST['url'];

        if (isset($_POST['verwijder'])) {

            try {

                $sql = "DELETE FROM `taak` WHERE `taak_ID` = $oudetaak_ID";

                $stmt = $db->prepare($sql);
                $stmt->execute();

                echo("<div id='melding'>taak met ID: $oudetaak_ID verwijderd!</div>");

            } catch (PDOException $e) {

                echo("<div id='melding'>");

                echo $e->getMessage();

                echo("</div>");

            }

            die();

        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (empty($_POST["taak_ID"])) {
                $errors['taak_ID'] = "taak_ID is vereist";
            } else {
                $taak_ID = test_input($_POST["taak_ID"]);
                if (!is_numeric($taak_ID)) {
                    $errors['taak_ID'] = "Alleen cijfers toegestaan!";
                }
            }

            if (empty($_POST["project_ID_project_ID"])) {
                $errors['project_ID'] = "project_ID is vereist";
            } else {
                $project_ID = test_input($_POST["project_ID_project_ID"]);
                if (!is_numeric($project_ID)) {
                    $errors['project_ID'] = "Alleen cijfers toegestaan!";
                }
            }

            if (empty($_POST["omschrijving"])) {
                $errors['omschrijving'] = "omschrijving is vereist";
            } else {
                $omschrijving = test_input($_POST["omschrijving"]);
                if (!preg_match("/^[a-zA-Z ]*$/", $omschrijving)) {
                    $errors['omschrijving'] = "Alleen letters en spaties toegestaan!";
                }
            }


        }

        if (!$errors) {

            try {

                $stmt = $db->prepare($query);
//            $stmt->execute(array(':newtaak_ID' => $taak_ID, ':omschrijving' => $omschrijving, ':taak_ID' => $oudetaak_ID));
                $stmt->execute(array(':newtaak_ID' => $taak_ID, ':project_ID' => $project_ID, ':omschrijving' => $omschrijving, ':taak_ID' => $oudetaak_ID));

                echo("<div id='melding'>taak met omschrijving: $omschrijving bijgewerkt! <br>Ga hier <a href='$url'>Terug</a></div>");


            } catch (PDOException $e) {

                echo("<div id='melding'>");

                echo $e->getMessage();

                echo("</div>");

            }

        } else {

            echo("<div id='melding'>");

            foreach ($errors as $value) {

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
