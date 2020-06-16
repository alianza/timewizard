<div class="form">

    <?php

    if ($_SESSION['L_STATUS'] == 1 || $_SESSION['L_STATUS'] == 2) {

        ?>

        <h1>Explore Projecten</h1>

        <p>Kies project om te exploreren</p>

        <?php

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

            $projectnaam = $row['projectnaam'];
            $project_ID = $row['project_ID'];

            echo " <form action='index.php?page=explore_project&project_ID=$project_ID&projectnaam=$projectnaam' method='post'>

        <div class='field'>

            <input id='input' type='submit' value='$projectnaam ID: $project_ID'>

            <input id='input' name='projectnaam' type='hidden' value='$projectnaam'>

            <input id='input' name='project_ID' type='hidden' value='$project_ID'>

         </div>

    </form>";

        }

        if ($stmt->rowCount() == 0) {
            echo("</div><div id='melding'>Nog geen Projecten.</div>");
        }

    } else {

        loginbarrier();

    }

    ?>

</div>
