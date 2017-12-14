<div class="form">

    <h1>Explore Taken</h1>

    <?php

        if ($_SESSION['L_STATUS'] == 1) {

            if (isset($_GET['project_ID']) && isset($_GET['projectnaam'])) {

                $url = $_SERVER['REQUEST_URI'];
                $project_ID = $_GET['project_ID'];
                $projectnaam = $_GET['projectnaam'];

        echo ("<p>Taken voor project: $projectnaam </p>");

    try {
            $sql = "SELECT * FROM `taak` WHERE project_ID_project_ID = :project_ID";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(':project_ID' => $project_ID));

        } catch(PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

        $output = "<div class='field'>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $omschrijving = $row['omschrijving'];
            $taak_ID = $row['taak_ID'];

        $output .= "<input type='text' id='input' value='$omschrijving' readonly>";

    }

            if ($stmt->rowCount() == 0) {
                echo("</div><div id='melding'>Nog geen Taken.</div>");
            }

        $output .= "</div>";

        echo ($output);

            } else {

                echo("<script>goto('index.php?page=log_time_choose_project');</script>");

                echo("<div id='melding'>Geen Project ID opgegeven ga <a href='index.php?page=log_time_choose_project'>Hier</a> terug!</div>");

            }

        } else {

            loginbarrier();

        }

        ?>

</div>
