    <head>
        <meta http-equiv="Content-Type" content="text/html;
              charset=UTF-8">
    </head>

        <?php

if ($_SESSION['L_STATUS'] == 2) {

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sql']) && isset($_POST['project_ID'])) {

    $query = $_POST['sql'];
    $oudeproject_ID = $_POST['ID'];
    $errors = array();
    $project_ID = $projectnaam = "";
    $url = $_POST['url'];

    if (isset($_POST['verwijder'])) {

        try {

            $sql = "DELETE FROM `project` WHERE `project_ID` = $oudeproject_ID";

            $stmt = $db->prepare($sql);
            $stmt->execute();

            echo("<div id='melding'>project met ID: $oudeproject_ID verwijderd!</div>");

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div>");

        }

            die();
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

     if (empty($_POST["project_ID"])) {
     $errors['project_ID'] = "project_ID is vereist";
   } else {
     $project_ID = test_input($_POST["project_ID"]);
     if (!is_numeric($project_ID)) {
     $errors['project_ID'] = "Alleen cijfers toegestaan!";
     }
   }

   if (empty($_POST["projectnaam"])) {
     $errors['projectnaam'] = "projectnaam is vereist";
   } else {
     $projectnaam = test_input($_POST["projectnaam"]);
     if (!preg_match("/^[a-zA-Z ]*$/",$projectnaam)) {
     $errors['projectnaam'] = "Alleen letters en spaties toegestaan!";
     }
   }


}

 if (!$errors) {

        try {

            $stmt = $db->prepare($query);
            $stmt->execute(array(':newproject_ID' => $project_ID, ':projectnaam' => $projectnaam, ':project_ID' => $oudeproject_ID));

            echo("<div id='melding'>project met projectnaam: $projectnaam bijgewerkt! <br>Ga hier <a href='$url'>Terug</a></div>");


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
