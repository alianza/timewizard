<div class="form">

    <?php

    if ($_SESSION['L_STATUS'] == 1) {

        ?>

        <h1>Projecten</h1>

        <p>Voor welk project wilt je tijd loggen?</p>

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

            echo " <form action='index.php?page=log_time_choose_task&project_ID=$project_ID&projectnaam=$projectnaam' method='post'>

        <div class='field'>

            <input id='input' name='projectnaam' type='submit' value='$projectnaam'>

            <input id='input' name='project_ID' type='hidden' value='$project_ID'>

         </div>

    </form>";

        }

    } else {

        loginbarrier();

    }

    ?>

</div>
