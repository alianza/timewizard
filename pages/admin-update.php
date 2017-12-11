    <head>
        <meta http-equiv="Content-Type" content="text/html;
              charset=UTF-8">
    </head>

        <?php

if ($_SESSION['L_STATUS'] == 2) {

if (isset($_POST['sql']) && isset($_POST['admin_ID'])) {

    $query = $_POST['sql'];
    $oudeadmin_ID = $_POST['ID'];
    $errors = array();
    $admin_ID = $gebruikersnaam = "";
    $url = $_POST['url'];

    if (isset($_POST['verwijder'])) {

        try {

            $sql = "DELETE FROM `admin` WHERE `admin_ID` = $oudeadmin_ID";

            $stmt = $db->prepare($sql);
            $stmt->execute();

            echo("<div id='melding'>Profiel van administrator met ID: $oudeadmin_ID verwijderd!</div>");

        } catch (PDOException $e) {

            echo $e->getMessage();

        }

        die();

    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

     if (empty($_POST["admin_ID"])) {
     $errors['admin_ID'] = "admin_ID is vereist";
   } else {
     $admin_ID = test_input($_POST["admin_ID"]);
     if (!is_numeric($admin_ID)) {
     $errors['admin_ID'] = "Alleen cijfers toegestaan!";
     }
   }

    if (empty($_POST["gebruikersnaam"])) {
     $errors['gebruikersnaam'] = "Gebruikersnaam is vereist!";
   } else {

        $gebruikersnaam = test_input($_POST["gebruikersnaam"]);

         try {

            $sql = "SELECT * FROM `admin` WHERE `gebruikersnaam` = '$gebruikersnaam'";

            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result) {

                if($result['gebruikersnaam'] !== $gebruikersnaam) {

                    $errors['gebruikersnaam'] = "Gebruikersnaam moet uniek zijn!";

                }

            }

        } catch (PDOException $e) {

             echo("<div id='melding'>");

            echo $e->getMessage();

             echo("</div>");

        }

   }


}

 if (!$errors) {

        try {

            $stmt = $db->prepare($query);
            $stmt->execute(array(':newadmin_ID' => $admin_ID, ':gebruikersnaam' => $gebruikersnaam, ':admin_ID' => $oudeadmin_ID));

            echo("<div id='melding'>Profiel van administrator met gebruikersnaam: $gebruikersnaam bijgewerkt! <br>Ga hier <a href='$url'>Terug</a></div>");


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

    echo("<div id='melding'>Geen User ID opgegeven ga <a href='index.php?page=kiestabel'>Hier</a> terug!</div>");

}

} else {

    loginbarrier();

}

?>
