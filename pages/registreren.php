<?php
require_once "recaptchalib.php";
$secret = "6LcwaRgTAAAAAIzbm5JUxUDbPO_HO1D2nybdeI5v";
$response = null;
$reCaptcha = new ReCaptcha($secret);
if (isset($_POST["g-recaptcha-response"])) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}
?>

    <head>
        <meta http-equiv="Content-Type" content="text/html;
              charset=UTF-8">
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>

        <?php

$errors = array();

$voornaam = $tussenvoegsels = $achternaam = $geboortedatum = $email = $gebruikersnaam = $wachtwoord = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

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

            $sql = "SELECT * FROM user WHERE gebruikersnaam = :gebruikersnaam";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':gebruikersnaam' => $gebruikersnaam));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result) {

                $errors['gebruikersnaam'] = "Gebruikersnaam moet uniek zijn!";

            }

        } catch (PDOException $e) {

             echo("<div id='melding'>");

            echo $e->getMessage();

             echo("</div");

        }

   }

    if (empty($_POST["wachtwoord"])) {
     $errors['wachtwoord'] = "Wachtwoord is Vereist";
   }
    if(!empty($_POST["wachtwoord"]) ) {
    $wachtwoord = $_POST["wachtwoord"];
    if (strlen($_POST["wachtwoord"]) <= '8') {
        $errors['wachtwoord'] = "Wachtwoord moet minstens 8 karakters bevatten!";
    }
    elseif(!preg_match("#[0-9]+#",$wachtwoord)) {
        $errors['wachtwoord'] = "Wachtwoord moet minstens 1 cijfer bevatten!";
    }
    elseif(!preg_match("#[A-Z]+#",$wachtwoord)) {
        $errors['wachtwoord'] = "Wachtwoord moet minstens 1 hoofdletter bevatten!";
    }
    elseif(!preg_match("#[a-z]+#",$wachtwoord)) {
        $errors['wachtwoord'] = "Je Wachtwoord moet minstens 1 kleine letter bevatten!";
    }

    }

    if (empty($_POST["wachtwoord1"])) {
     $errors['wachtwoord'] = "wachtwoord is Vereist";
   }
    if(!empty($_POST["wachtwoord1"]) ) {
    $wachtwoord1 = $_POST["wachtwoord1"];
    if (strlen($_POST["wachtwoord1"]) <= '8') {
        $errors['wachtwoord'] = "Je Wachtwoord moet minstens 8 karakters bevatten!";
    }
    elseif(!preg_match("#[0-9]+#",$wachtwoord)) {
        $errors['wachtwoord'] = "Je Wachtwoord moet minstens 1 cijfer bevatten!";
    }
    elseif(!preg_match("#[A-Z]+#",$wachtwoord)) {
        $errors['wachtwoord'] = "Wachtwoord moet minstens 1 hoofdletter bevatten!";
    }
    elseif(!preg_match("#[a-z]+#",$wachtwoord)) {
        $errors['wachtwoord'] = "Je Wachtwoord moet minstens 1 kleine letter bevatten!";
    }

    if ($wachtwoord !== $wachtwoord1) {

        $errors["wachtwoord"] = "Wachtwoorden komen niet overeen";

    }
}

    if ($response != null && $response->success) {

    } else {
        $errors["recaptcha"] = "Incorrecte recaptcha input<br>";
    }

 if (!$errors) {

     $hwachtwoord = md5($wachtwoord);

        try {

            $sql = "INSERT INTO user(voornaam, tussenvoegsels, achternaam, geboortedatum, email, gebruikersnaam, wachtwoord) VALUES (:voornaam,:tussenvoegsels,:achternaam,:geboortedatum,:email,:gebruikersnaam,:wachtwoord)";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':voornaam' => $voornaam, ':tussenvoegsels' => $tussenvoegsels, ':achternaam' => $achternaam, ':geboortedatum' => $geboortedatum, ':email' => $email, ':gebruikersnaam' => $gebruikersnaam, ':wachtwoord' => $hwachtwoord));

                echo("<div id='melding'>Nieuwe user aangemaakt met gebruikersnaam: " . $gebruikersnaam . "!</div>");

                mail($email, "E-mail conformation", "Thank you for registering your account with full-name: " . $voornaam . " " . $tussenvoegsels . " " . $achternaam . ".");

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div");

        }
    }

 }

?>

            <div class="form">

                <h2>Registratieformulier</h2>

                <form method="post" enctype="multipart/form-data">

                    <div class="field">
                        <span><?php  if(isset($errors['voornaam'])) echo $errors['voornaam'] ?></span>
                        <input type="text" id="input" name="voornaam" placeholder="Voornaam">
                        <span><?php  if(isset($errors['tussenvoegsels'])) echo $errors['tussenvoegsels'] ?></span>
                        <input type="text" id="input" name="tussenvoegsels" placeholder="Tussenvoegsels">
                        <span><?php  if(isset($errors['achternaam'])) echo $errors['achternaam'] ?></span>
                        <input type="text" id="input" name="achternaam" placeholder="Achternaam">
                        <span><?php  if(isset($errors['geboortedatum'])) echo $errors['geboortedatum'] ?></span>
                        <input type="text" id="input" onfocus="(this.type='date')" name="geboortedatum" placeholder="Geboortedatum">
                        <span><?php  if(isset($errors['email'])) echo $errors['email'] ?></span>
                        <input type="email" id="input" name="email" placeholder="Email">
                        <span><?php  if(isset($errors['gebruikersnaam'])) echo $errors['gebruikersnaam'] ?></span>
                        <input type="text" id="input" name="gebruikersnaam" placeholder="Gebruikersnaam">
                        <span><?php  if(isset($errors['wachtwoord'])) echo $errors['wachtwoord'] ?></span>
                        <input type="password" id="input" name="wachtwoord" placeholder="Wachtwoord">
                        <input type="password" id="input" name="wachtwoord1" placeholder="Wachtwoord herhalen">

                    </div>

                    <div class="field">
                        <span><?php  if(isset($errors['recaptcha'])) echo $errors['recaptcha'] ?></span>
                        <div class="g-recaptcha" data-sitekey="6LcwaRgTAAAAAAVPZz9meBmFqeO_LlWKBY_vnNMQ"></div>

                        <input type="submit" name="submit" id="submit" value="Aanmelden">

                    </div>

                </form>

            </div>
