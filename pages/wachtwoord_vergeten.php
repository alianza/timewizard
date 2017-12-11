<?php

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = array();

    $email = htmlspecialchars($_POST["email"]);

    if(empty($email)) {
        $errors["email"] = "Je hebt geen email adres ingevuld.<br>";
    }

    if(!$errors) {

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

            if($result) {

                $chars = "abcdefghijkmnopqrstuvwxyz023456789";
                srand((double)microtime()*1000000);
                $i = 0;
                $salt = '';

                while ($i <= 31) {
                    $num = rand() % 33;
                    $tmp = substr($chars, $num, 1);
                    $salt = $salt . $tmp;
                    $i++;
                }

                $_SESSION['salt'] = $salt;

                $to  = $email;

                $subject = 'Wachtwoord-Reset';

                $message = "
<html>
<head>
  <title>Wachtwoord-Reset E-Mail</title>
</head>
<body>
  <p>Hieronder de link naar de Wachtwoord-Reset pagina:</p>
  <table>
    <tr>
      <th><a href='timewizard.damscommerce.nl/index.php?page=wachtwoord_wijzigen&salt=$salt'>Wachtwoord vergeten</a></th>
    </tr>
  </table>
</body>
</html>
";

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Mail it
mail($to, $subject, $message, $headers);

                echo("<div id='melding'>Email verzonden.</div>");

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

                <span><?php  if(isset($errors['email'])) echo $errors['email'] ?></span>
                    <input type="text" id="input" name="email" placeholder="E-mail Adres" />

                    <input type="submit" id="submit" value="Verzend" />

            </div>

    </form>

</div>
