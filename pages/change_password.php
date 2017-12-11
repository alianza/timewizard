<?php

if ($_SESSION['L_STATUS'] == 1 || isset($_GET['salt'])) {

    if ($_SESSION['L_STATUS'] == 1) {

        $user_ID = $_SESSION["L_ID"];

        try {

            $sql = "select * FROM user WHERE `user_ID` = :user_ID";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':user_ID' => $user_ID));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

             } catch (PDOException $e) {

                echo("<div id='melding'>");

                echo $e->GetMessage();

                echo("</div>");

        }

            if ($result) {

                $email = $result['email'];

            } else {

                echo("<div id='melding'>");

                echo("Session user_ID niet gekoppeld aan email adres, probeer opnieuw.");

                echo("</div>");

            }

    } elseif (isset($_SESSION['salt'])) {

            $salt = $_GET['salt'];
            $sessionsalt = $_SESSION['salt'];

        if ($salt !== $sessionsalt) {

            echo("<div id='melding'>");

            echo("Beveiligingscode komt niet overeen!");

            echo("</div>");

            die();

        }

    } else {

            echo("<div id='melding'>");

            echo("Beveiligingscode komt niet overeen!");

            echo("</div>");

            die();

    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $wachtwoord = $wachtwoordconfirm = $email = "";

    $errors = array();

    if (empty($_POST["email"])) {
     $errors['email'] = "Email is vereist";
   } else {
     $email = test_input($_POST["email"]);
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $errors['email'] = "Ongeldig email format!";
     }
   }

    if (empty($_POST["wachtwoord"])) {
     $errors['wachtwoord'] = "wachtwoord is vereist!";
   }
    if(!empty($_POST["wachtwoord"]) ) {
        $wachtwoord = $_POST["wachtwoord"];
    if (strlen($_POST["wachtwoord"]) <= '8') {
        $errors['wachtwoord'] = "Je Wachtwoord moet minstens 8 karakters bevatten!";
    } elseif(!preg_match("#[0-9]+#",$wachtwoord)) {
        $errors['wachtwoord'] = "Je Wachtwoord moet minstens 1 cijfer bevatten!";
    } elseif(!preg_match("#[A-Z]+#",$wachtwoord)) {
        $errors['wachtwoord'] = "Wachtwoord moet minstens 1 hoofdletter bevatten!";
    } elseif(!preg_match("#[a-z]+#",$wachtwoord)) {
        $errors['wachtwoord'] = "Je Wachtwoord moet minstens 1 kleine letter bevatten!";
    }

    }

    if (empty($_POST["wachtwoordconfirm"])) {
     $errors['wachtwoord'] = "wachtwoord herhalen is vereist!";
   }
    if(!empty($_POST["wachtwoordconfirm"]) ) {
        $wachtwoordconfirm = $_POST["wachtwoordconfirm"];
    if (strlen($_POST["wachtwoordconfirm"]) <= '8') {
        $errors['wachtwoordconfirm'] = "Je Wachtwoord moet minstens 8 karakters bevatten!";
    } elseif(!preg_match("#[0-9]+#",$wachtwoord)) {
        $errors['wachtwoordconfirm'] = "Je Wachtwoord moet minstens 1 cijfer bevatten!";
    } elseif(!preg_match("#[A-Z]+#",$wachtwoord)) {
        $errors['wachtwoordconfirm'] = "Wachtwoord moet minstens 1 hoofdletter bevatten!";
    } elseif(!preg_match("#[a-z]+#",$wachtwoord)) {
        $errors['wachtwoordconfirm'] = "Je Wachtwoord moet minstens 1 kleine letter bevatten!";
    }

        if ($wachtwoord !== $wachtwoordconfirm) {

            $errors["wachtwoord"] = "Wachtwoorden komen niet overeen";

            $errors["wachtwoordconfirm"] = "Wachtwoorden komen niet overeen";

        }
}

 if (!$errors) {

     $hwachtwoord = md5($wachtwoord);

     if (!isset($user_ID)) {

        try {

            $sql = "select * FROM user WHERE `email` = :email";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':email' => $email));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {

                echo("<div id='melding'>");

                echo $e->GetMessage();

                echo("</div>");

        }

            if ($result) {

                $user_ID = $result['user_ID'];

            } else {

                echo("<div id='melding'>");

                echo("Onbekend E-Mail Adres!");

                echo("</div>");

            }

    }

     if (isset($user_ID)) {

        try {

            $sql = "UPDATE `user` SET `wachtwoord` = :hwachtwoord WHERE `user_ID` = :user_ID";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':hwachtwoord' => $hwachtwoord, ':user_ID' => $user_ID));

                echo "<div id='melding'>Wachtwoord Succesvol gewijzgd!</div>";

                unset($_SESSION['salt']);

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

            $to = $email;
            $subject = "DamsCommerce TimeWizard - Wachtwoord Heringesteld";

            $message = "
            <html>
                <head>
                <title>TimeWizard Notification</title>
                </head>
                <body>
                <p>Uw wachtwoord is succesvol gewijzigd in</p> <input type='text' value='$wachtwoord'>
                </body>
                </html>
                ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <info@damscommerce.nl>' . "\r\n";

            mail($to,$subject,$message,$headers);

            echo "<div id='melding'>Je Hebt een bevestigingsemail ontvangen!</div>";

            die();

     }

    }

 }

?>

            <div class="form">

<form name="inloggen" method="post">

            <div class="field">

                <p>Voer <?php if (!isset($user_ID)) {echo("uw E-mail adres in en");} ?> een nieuw wachtwoord in en herhaal deze om je wachtwoord opnieuw in te stellen.</p>

                <span><?php  if(isset($errors['email'])) echo $errors['email'] ?></span>
                <input id="input" type="email" name="email" placeholder="E-Mail Adres" value="<?php if (isset($user_ID)) {echo($email);} ?>" <?php if (isset($user_ID)) {echo("readonly");} ?> >

                <span><?php  if(isset($errors['wachtwoord'])) echo $errors['wachtwoord'] ?></span>
                <input id="input" type="password" name="wachtwoord" placeholder="Nieuw Wachtwoord">

                <span><?php  if(isset($errors['wachtwoordconfirm'])) echo $errors['wachtwoordconfirm'] ?></span>
                <input id="input" type="password" name="wachtwoordconfirm" placeholder="Wachtwoord herhalen">

                <input id="submit" type="submit" value="Wijzig Wachtwoord">

                </div>

        </form>

</div>

<?php

    } else {

    loginbarrier();

        }

    ?>
