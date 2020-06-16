<head>
    <meta http-equiv="Content-Type" content="text/html;
              charset=UTF-8">
</head>

<?php

if ($_SESSION['L_STATUS'] == 2) {

    $errors = array();

    $gebruikersnaam = $wachtwoord = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        if (empty($_POST["gebruikersnaam"])) {
            $errors['gebruikersnaam'] = "Gebruikersnaam is vereist!";
        } else {

            $gebruikersnaam = test_input($_POST["gebruikersnaam"]);

            try {

                $sql = "SELECT * FROM admin WHERE gebruikersnaam = :gebruikersnaam";

                $stmt = $db->prepare($sql);
                $stmt->execute(array('gebruikersnaam' => $gebruikersnaam));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {

                    $errors['gebruikersnaam'] = "Gebruikersnaam moet uniek zijn!";

                }

            } catch (PDOException $e) {

                echo("<div id='melding'>");

                echo $e->getMessage();

                echo("</div>");

            }

        }

        if (empty($_POST["wachtwoord"])) {
            $errors['wachtwoord'] = "wachtwoord is Vereist";
        }
        if (!empty($_POST["wachtwoord"])) {
            $wachtwoord = $_POST["wachtwoord"];
            if (strlen($_POST["wachtwoord"]) <= '8') {
                $errors['wachtwoord'] = "Je Wachtwoord moet minstens 8 karakters bevatten!";
            } elseif (!preg_match("#[0-9]+#", $wachtwoord)) {
                $errors['wachtwoord'] = "Je Wachtwoord moet minstens 1 cijfer bevatten!";
            } elseif (!preg_match("#[A-Z]+#", $wachtwoord)) {
                $errors['wachtwoord'] = "Wachtwoord moet minstens 1 hoofdletter bevatten!";
            } elseif (!preg_match("#[a-z]+#", $wachtwoord)) {
                $errors['wachtwoord'] = "Je Wachtwoord moet minstens 1 kleine letter bevatten!";
            }

        }

        if (empty($_POST["wachtwoord1"])) {
            $errors['wachtwoord'] = "wachtwoord is Vereist";
        }
        if (!empty($_POST["wachtwoord1"])) {
            $wachtwoord1 = $_POST["wachtwoord1"];
            if (strlen($_POST["wachtwoord1"]) <= '8') {
                $errors['wachtwoord'] = "Je Wachtwoord moet minstens 8 karakters bevatten!";
            } elseif (!preg_match("#[0-9]+#", $wachtwoord)) {
                $errors['wachtwoord'] = "Je Wachtwoord moet minstens 1 cijfer bevatten!";
            } elseif (!preg_match("#[A-Z]+#", $wachtwoord)) {
                $errors['wachtwoord'] = "Wachtwoord moet minstens 1 hoofdletter bevatten!";
            } elseif (!preg_match("#[a-z]+#", $wachtwoord)) {
                $errors['wachtwoord'] = "Je Wachtwoord moet minstens 1 kleine letter bevatten!";
            }

            if ($wachtwoord !== $wachtwoord1) {

                $errors["wachtwoord"] = "Wachtwoorden komen niet overeen";

            }
        }

        if (!$errors) {

            $hwachtwoord = md5($wachtwoord);

            try {

                $sql = "INSERT INTO admin(gebruikersnaam, wachtwoord) VALUES (:gebruikersnaam,:wachtwoord)";

                $stmt = $db->prepare($sql);
                $stmt->execute(array(':gebruikersnaam' => $gebruikersnaam, 'wachtwoord' => $hwachtwoord));

                echo("<div id='melding'>Nieuwe administrator aangemaakt met gebruikersnaam: " . $gebruikersnaam . "!</div>");

            } catch (PDOException $e) {

                echo("<div id='melding'>");

                echo $e->getMessage();

                echo("</div>");

            }
        }
    }

    ?>

    <div class="form">

        <h2>Registratieformulier</h2>

        <form method="post" enctype="multipart/form-data">

            <div class="field">
                <span><?php if (isset($errors['gebruikersnaam'])) echo $errors['gebruikersnaam'] ?></span>
                <input type="text" id="input" name="gebruikersnaam" placeholder="Gebruikersnaam"
                       value="<?php if (isset($_POST['submit'])) {
                           echo($gebruikersnaam);
                       } ?>">
                <span><?php if (isset($errors['wachtwoord'])) echo $errors['wachtwoord'] ?></span>
                <input type="password" id="input" name="wachtwoord" placeholder="Wachtwoord">
                <input type="password" id="input" name="wachtwoord1" placeholder="Wachtwoord herhalen">

            </div>

            <div class="field">

                <input type="submit" id="submit" name="submit" value="Aanmaken">

            </div>

        </form>

    </div>

    <?php

} else {

    loginbarrier();

}

?>
