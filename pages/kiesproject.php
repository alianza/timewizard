<div class="form">

    <h1>Projecten</h1>

    <p>Kies het Project waar u het rapport voor wilt weergeven.</p>

    <?php

    if ($_SESSION['L_STATUS'] == 2) {

        try {
            $sql = "SELECT * FROM `project`";
            $stmt = $db->prepare($sql);
            $stmt->execute();

        } catch (PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $project_ID = $row['project_ID'];
            $projectnaam = $row['projectnaam'];

            echo " <form action='index.php?page=rapport_2' method='post'>

        <div class='field'>

    <input id='input' name='projectnaam' type='submit' value='$projectnaam'>

    <input id='input' name='project_ID' type='hidden' value='$project_ID'>

         </div> </form>";

        }

        unset($project_ID);

    } else {

        loginbarrier();

    }

    ?>

</div>
