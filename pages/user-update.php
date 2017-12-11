    <head>
        <meta http-equiv="Content-Type" content="text/html;
              charset=UTF-8">
    </head>

        <?php

if ($_SESSION['L_STATUS'] == 2) {

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sql']) && isset($_POST['user_ID'])) {

    $query = $_POST['sql'];
    $oudeuser_ID = $_POST['ID'];
    $errors = array();
    $user_ID = $voornaam = $tussenvoegsels = $achternaam = $geboortedatum = $email = $gebruikersnaam = "";
    $url = $_POST['url'];

    if (isset($_POST['verwijder'])) {

        try {

            $sql = "DELETE FROM `user` WHERE `user_ID` = $oudeuser_ID";

            $stmt = $db->prepare($sql);
            $stmt->execute();

            echo("<div id='melding'>Profiel van user met ID: $oudeuser_ID verwijderd!</div>");

        } catch (PDOException $e) {

            echo $e->getMessage();

        }

        die();

    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

     if (empty($_POST["user_ID"])) {
     $errors['user_ID'] = "user_ID is vereist";
   } else {
     $user_ID = test_input($_POST["user_ID"]);
     if (!is_numeric($user_ID)) {
     $errors['user_ID'] = "Alleen cijfers toegestaan!";
     }
   }

   if (empty($_POST["voornaam"])) {
     $errors['voornaam'] = "Voornaam is vereist";
   } else {
     $voornaam = test_input($_POST["voornaam"]);
     if (!preg_match("/^[a-zA-Z ]*$/",$voornaam)) {
     $errors['voornaam'] = "Alleen letters en spaties toegestaan!";
     }
   }

    if (empty($_POST["tussenvoegsels"])) {
        $tussenvoegsels = "";
    } else {
        $tussenvoegsels = test_input($_POST["tussenvoegsels"]);
        if (!preg_match("/^[a-zA-Z ]*$/",$tussenvoegsels)) {
        $errors['tussenvoegsels'] = "Alleen letters en spaties toegestaan!";
     }
    }

    if (empty($_POST["achternaam"])) {
     $errors['achternaam'] = "Achternaam is vereist";
   } else {
     $achternaam = test_input($_POST["achternaam"]);
     if (!preg_match("/^[a-zA-Z ]*$/",$achternaam)) {
     $errors['achternaam'] = "Alleen letters en spaties toegestaan!";
     }
   }

   if (empty($_POST["geboortedatum"])) {
     $errors['geboortedatum'] = "geboortedatum is vereist!";
   } else {
     $geboortedatum = test_input($_POST["geboortedatum"]);
   }

   if (empty($_POST["email"])) {
     $errors['email'] = "Email is vereist";
   } else {
     $email = test_input($_POST["email"]);
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $errors['email'] = "Ongeldig email format!";
     }
   }

    if (empty($_POST["gebruikersnaam"])) {
     $errors['gebruikersnaam'] = "Gebruikersnaam is vereist!";
   } else {

        $gebruikersnaam = test_input($_POST["gebruikersnaam"]);

         try {

            $sql = "SELECT * FROM user WHERE gebruikersnaam = '$gebruikersnaam'";

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
            $stmt->execute(array(':newuser_ID' => $user_ID, ':voornaam' => $voornaam, ':tussenvoegsels' => $tussenvoegsels, ':achternaam' => $achternaam, ':geboortedatum' => $geboortedatum, ':email' => $email, ':gebruikersnaam' => $gebruikersnaam, ':user_ID' => $oudeuser_ID));

            echo("<div id='melding'>Profiel van user met gebruikersnaam: $gebruikersnaam bijgewerkt! <br>Ga hier <a href='$url'>Terug</a></div>");


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
