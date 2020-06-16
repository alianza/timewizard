<?php

require_once "recaptchalib.php";
$secret = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";
$response = null;
$reCaptcha = new ReCaptcha($secret);
if (isset($_POST["g-recaptcha-response"])) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}

if (isset($_POST["submit"])) {

    $errors = array();
    $gebruikersnaam = htmlspecialchars($_POST["gebruikersnaam"]);
    $wachtwoord = $_POST["wachtwoord"];

    if (empty($gebruikersnaam)) {
        $errors["gebruikersnaam"] = "Je heeft geen gebruikersnaam ingevuld.<br>";
    }

    if (empty($wachtwoord)) {
        $errors["wachtwoord"] = "Je heeft geen wachtwoord ingevuld.<br>";
    }

    if ($response == null || !$response->success) {
        $errors["recaptcha"] = "Incorrecte recaptcha input<br>";
    }

    $hwachtwoord = md5($wachtwoord);

    if (!$errors) {

        try {

            $sql = "SELECT * FROM user WHERE gebruikersnaam = :gebruikersnaam AND wachtwoord = :wachtwoord";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':gebruikersnaam' => $gebruikersnaam, ':wachtwoord' => $hwachtwoord));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {

                $_SESSION['L_ID'] = $result['user_ID'];
                $_SESSION['L_NAME'] = $result['gebruikersnaam'];
                $_SESSION['L_STATUS'] = 1;

                echo "<script> location.href='index.php?page=home'; </script>";

            } else {

                echo("<div id='melding'>Onjuiste gebruikersnaam en/of wachtwoord.</div>");

            }

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div>");

        }

    }

}

?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="form">

    <h2>Inloggen user</h2>

    <form name="inloggen" method="POST" enctype="multipart/form-data">

        <div class="field">

            <span><?php if (isset($errors['gebruikersnaam'])) echo $errors['gebruikersnaam'] ?></span>
            <input type="text" id="input" name="gebruikersnaam" placeholder="Gebruikersnaam"/>

            <span><?php if (isset($errors['wachtwoord'])) echo $errors['wachtwoord'] ?></span>
            <input type="password" id="input" name="wachtwoord" placeholder="Wachtwoord"/>

            <input type="hidden" name="submit" value="true"/>

            <span><?php if (isset($errors['recaptcha'])) echo $errors['recaptcha'] ?></span>
            <div class="g-recaptcha" data-sitekey="6LePozwUAAAAADOnywJdOfA4iRiBQ15oRuYKvLbq"></div>

            <input type="submit" id="submit" value="inloggen"/>

        </div>

        <a href="index.php?page=wachtwoord_vergeten">Wachtwoord vergeten</a>

        <a href="index.php?page=adminlogin">Administrator login</a>

    </form>

</div>
