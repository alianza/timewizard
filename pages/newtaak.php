<head>
        <meta http-equiv="Content-Type" content="text/html;
              charset=UTF-8">
</head>

        <?php

if ($_SESSION['L_STATUS'] == 2) {

$errors = array();

$omschrijving = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    if (empty($_POST["omschrijving"])) {
     $errors['omschrijving'] = "omschrijving is vereist!";
   } else {

        $omschrijving = test_input($_POST["omschrijving"]);

         try {

            $sql = "SELECT * FROM taak WHERE omschrijving = :omschrijving";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':omschrijving' => $omschrijving));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result) {

                $errors['omschrijving'] = "omschrijving moet uniek zijn!";

            }

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div");

        }

   }

 if (!$errors) {

        try {

            $sql = "INSERT INTO taak (omschrijving) VALUES (:omschrijving)";

            $stmt = $db->prepare($sql);
            $stmt->execute(array(':omschrijving' => $omschrijving));

            echo("<div id='melding'>Nieuwe taak aangemaakt met omschrijving: " . $omschrijving . "!</div>");

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->getMessage();

            echo("</div>");

        }
    }

}

    ?>

    <div class="form">

                <h2>Nieuwe taak aanmaken</h2>

                <form method="post" enctype="multipart/form-data">

                    <div class="field">
                        <span><?php  if(isset($errors['omschrijving'])) echo $errors['omschrijving'] ?></span>
                        <input type="text" id="input" name="omschrijving" placeholder="Omschrijving">

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
