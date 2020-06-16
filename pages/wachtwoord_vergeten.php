<?php

require("./lib/PHPMailer/src/PHPMailer.php");
require("./lib/PHPMailer/src/SMTP.php");
require("./lib/PHPMailer/src/Exception.php");

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use SMTP\SMTP\SMTP;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = array();

    $email = htmlspecialchars($_POST["email"]);

    if (empty($email)) {
        $errors["email"] = "Je hebt geen email adres ingevuld.<br>";
    }

    if (!$errors) {

        try {

            $sql = "SELECT * FROM user WHERE email = :email";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':email' => $email));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div>");

        }

        if ($result) {

            $chars = "abcdefghijkmnopqrstuvwxyz023456789";
            srand((double)microtime() * 1000000);
            $i = 0;
            $salt = '';

            while ($i <= 31) {
                $num = rand() % 33;
                $tmp = substr($chars, $num, 1);
                $salt = $salt . $tmp;
                $i++;
            }

            $_SESSION['salt'] = $salt;

            $name = $result['voornaam'] . " " . $result['tussenvoegsels'] . " " . $result['achternaam'];

            $to = $email;

            $subject = 'TimeWizard - Wachtwoord-Reset';

            $message = "
<html>
<head>
  <title>Wachtwoord-Reset E-Mail</title>
</head>
<body>
    <h1>Wachtwoord Reset</h1>
  <p>Hieronder de link naar de Wachtwoord-Reset pagina:</p>
  <a href='timewizard.damscommerce.nl/index.php?page=wachtwoord_wijzigen&salt=$salt'>Wachtwoord vergeten</a>
  <br>
  <p>TimeWizard</p>
</body>
</html>";

// OLD Mail it
//mail($to, $subject, $message, $headers);


            $mail = new PHPMailer;
//Tell PHPMailer to use SMTP
            $mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
            $mail->SMTPDebug = 0;
//Set the hostname of the mail server
            $mail->Host = 'webmail.damscommerce.nl';
//Set Port
            $mail->Port = 587;
//Whether to use SMTP authentication
            $mail->SMTPAuth = true;
//Username to use for SMTP authentication
            $mail->Username = 'info@damscommerce.nl';
//Password to use for SMTP authentication
            $mail->Password = 'DamsCommerce12_INFO';
//Set who the message is to be sent from
            $mail->setFrom('info@damscommerce.nl', 'DamsCommerce');
//Set who the message is to be sent to
            $mail->addAddress($to, $name);
//Set the subject line
            $mail->Subject = $subject;
// Set email format to HTML
            $mail->isHTML(true);
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
            $mail->Body = $message;

//send the message, check for errors
            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo("<div id='melding'>Email verzonden.</div>");
            }
        } else {

            echo("<div id='melding'>Email adres niet bekend!</div>");

        }

    }

}

?>

<div class="form">

    <h2>Wachtwoord Vergeten</h2>

    <p>Vul uw E-mail adres in om een link naar de wachtwoord wijzig pagina te ontvangen!</p>

    <form name="inloggen" method="POST" enctype="multipart/form-data">

        <div class="field">

            <span><?php if (isset($errors['email'])) echo $errors['email'] ?></span>
            <input type="text" id="input" name="email" placeholder="E-mail Adres"/>

            <input type="submit" id="submit" value="Verzend"/>

        </div>

    </form>

</div>
