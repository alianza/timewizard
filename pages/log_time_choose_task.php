    <div class="form">

        <h1>Log Time</h1>

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
                $current_date = date('Y-m-d');

        echo ("<p>Wat voor soort taak wilt u loggen voor project: $projectnaam </p>");

    try {
            $sql = "SELECT * FROM `taak`";
            $stmt = $db->prepare($sql);
            $stmt->execute();

        } catch(PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

        $output = "<form name='inloggen' action='index.php?page=log_time' method='post'>

        <div class='field'>

        <select id='input' name='taak' onchange='setTextField(this)' required>
         <option value='' disabled selected>Selecteer Taak</option>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $omschrijving = $row['omschrijving'];
            $taak_ID = $row['taak_ID'];

        $output .= "<option value='$taak_ID'>$omschrijving</option>";

    }

    ?>

        <?php

        $output .= "</select><input type='date' id='input'name='datum' placeholder='Datum' value='$current_date' required>
        <input type='text' id='input' onfocus=(this.type='number') name='uren' placeholder='uren' max='24' step='.25' value='1' min='.25' required>
        <textarea id='input' name='opmerking' rows='4' cols='50' maxlength='255' required></textarea>
        <input id='input' name='project_id' type='hidden' value='$project_ID'>
        <input id='input' name='projectnaam' type='hidden' value='$projectnaam'>
        <input id='omschrijving' name='omschrijving' type='hidden' value=''>
        <input id='url' name='url' type='hidden' value='$url'>
        <input id='submit' name='submit' type='submit' value='Log'>
        </div>
        </form>";

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
