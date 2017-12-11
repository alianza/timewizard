    <div class="form">

        <h1>Explore Project</h1>

        <script>

            function setTextField(ddl) {
            document.getElementById('omschrijving').value = ddl.options[ddl.selectedIndex].text;
            }

        </script>

        <?php

        if ($_SESSION['L_STATUS'] == 1) {

            if (isset($_GET['project_ID']) && isset($_GET['projectnaam'])) {

                $url = $_SERVER['REQUEST_URI'];
                $project_ID = $_GET['project_ID'];
                $projectnaam = $_GET['projectnaam'];

        echo ("<p>Taken voor: $projectnaam </p>");

    try {
            $sql = "SELECT * FROM `taak`";
            $stmt = $db->prepare($sql);
            $stmt->execute();

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

    ?>

        <?php

        $output .= "</select>
        <input type='text' id='input' onfocus=(this.type='number') name='uren' placeholder='uren' max='24' step='.25' value='1' min='.25'>
        <input id='input' name='projectid' type='hidden' value='$project_ID'>
        <input id='input' name='projectnaam' type='hidden' value='$projectnaam'>
        <input id='omschrijving' name='omschrijving' type='hidden' value=''>
        <input id='url' name='url' type='hidden' value='$url'>
        <input id='submit' name='submit' type='submit' value='Log'>
        </div>";

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
