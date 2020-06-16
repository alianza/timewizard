<head>
    <meta http-equiv="Content-Type" content="text/html;
              charset=UTF-8">
</head>
<script>

    function setTextField(ddl) {
        document.getElementById('projectnaam').value = ddl.options[ddl.selectedIndex].text;
    }

</script>

<?php

if ($_SESSION['L_STATUS'] == 2) {

    $errors = array();

    $omschrijving = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        if (empty($_POST["omschrijving"])) {

            $errors['omschrijving'] = "omschrijving is vereist!";

        } else {

            $omschrijving = test_input($_POST["omschrijving"]);

            $project_ID = test_input($_POST["project_ID"]);

            $projectnaam = test_input($_POST["projectnaam"]);

        }

        if (!$errors) {

            try {

                $sql = "INSERT INTO taak (project_ID_project_ID, omschrijving) VALUES (:project_ID_project_ID ,:omschrijving)";

                $stmt = $db->prepare($sql);
                $stmt->execute(array(':omschrijving' => $omschrijving, ':project_ID_project_ID' => $project_ID));

                echo("<div id='melding'>Nieuwe taak aangemaakt met omschrijving: '" . $omschrijving . "' onder project: '" . $projectnaam . "'!</div>");

            } catch (PDOException $e) {

                echo("<div id='melding'>");

                echo $e->getMessage();

                echo("</div>");

            }
        }

    }

    try {
        $sql = "SELECT * FROM `project` WHERE 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();

    } catch (PDOException $e) {

        echo("<div id='melding'>");

        echo $e->GetMessage();

        echo("</div>");

    }

    $output = "

        <select id='input' name='project_ID' onchange='setTextField(this)' required>
         <option value='' disabled selected>Selecteer Project</option>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $project_ID = $row['project_ID'];
        $projectnaam = $row['projectnaam'];

        $output .= "<option value='$project_ID'>$projectnaam</option>";

    }

    $output .= "</select>";

    ?>

    <div class="form">

        <h2>Nieuwe taak aanmaken</h2>

        <form method="post" enctype="multipart/form-data">

            <div class="field">
                <span><?php if (isset($errors['omschrijving'])) echo $errors['omschrijving'] ?></span>
                <input type="text" id="input" name="omschrijving" placeholder="Omschrijving" required>
                <?php echo($output); ?>
                <input id='projectnaam' name='projectnaam' type='hidden' value=''>
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
