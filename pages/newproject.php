<head>
    <meta http-equiv="Content-Type" content="text/html;
              charset=UTF-8">
</head>

<?php

if ($_SESSION['L_STATUS'] == 2) {

    $errors = array();

    $projectnaam = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        if (empty($_POST["projectnaam"])) {
            $errors['projectnaam'] = "projectnaam is vereist!";
        } else {

            $projectnaam = test_input($_POST["projectnaam"]);

            try {

                $sql = "SELECT * FROM project WHERE projectnaam = :projectnaam";

                $stmt = $db->prepare($sql);
                $stmt->execute(array(':projectnaam' => $projectnaam));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {

                    $errors['projectnaam'] = "projectnaam moet uniek zijn!";

                }

            } catch (PDOException $e) {

                echo("<div id='melding'>");

                echo $e->getMessage();

                echo("</div");

            }

        }

        if (!$errors) {

            try {

                $sql = "INSERT INTO project (projectnaam) VALUES (:projectnaam)";

                $stmt = $db->prepare($sql);
                $stmt->execute(array(':projectnaam' => $projectnaam));

                echo("<div id='melding'>Nieuw project aangemaakt met naam: " . $projectnaam . "!</div>");

            } catch (PDOException $e) {

                echo("<div id='melding'>");

                echo $e->getMessage();

                echo("</div>");

            }
        }

    }

    ?>

    <div class="form">

        <h2>Nieuw project aanmaken</h2>

        <form method="post" enctype="multipart/form-data">

            <div class="field">
                <span><?php if (isset($errors['projectnaam'])) echo $errors['projectnaam'] ?></span>
                <input type="text" id="input" name="projectnaam" placeholder="Projectnaam">

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
