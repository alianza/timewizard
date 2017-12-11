<div class="form">
    <?php

        if ($_SESSION['L_STATUS'] == 2) {

            ?>

        <h1>Element</h1>
        <p>Kies het element dat u wilt wijzigen.</p>

        <?php

            if (isset($_GET['tabelnaam'])) {

        $value = "";
        $sql_addition = "";
        $tabelnaam = $_GET['tabelnaam'];
        $items = array();
        $output = "";

        if ($tabelnaam == "admin") {

            echo("<form action='index.php?page=newadmin' method='post'><div class='field'><input type='submit' id='submit' value='Nieuwe administrator aanmaken'></div></form>");

            $items = [
                "admin_ID" => "admin_ID",
                "gebruikersnaam" => "gebruikersnaam",
            ];

        } elseif ($tabelnaam == "user") {

            echo("<form action='index.php?page=registreren' method='post'><div class='field'><input type='submit' id='submit' value='Nieuwe user aanmaken'></div></form>");

            $items = [
                "user_ID" => "user_ID",
                "gebruikersnaam" => "gebruikersnaam",
            ];

        } elseif ($tabelnaam == "log") {

            echo("<form><div class='field'><input readonly id='submit' value='Maak met een user een nieuwe log aan'></div></form>");

            $items = [
                "log_ID" => "log_ID",
                "datum" => "datum",
                "uren" => "uren",
            ];

        } elseif ($tabelnaam == "taak") {

            echo("<form action='index.php?page=newtaak' method='post'><div class='field'><input type='submit' id='submit' value='Nieuwe taak aanmaken'></div></form>");

            $items = [
                "taak_ID" => "taak_ID",
                "omschrijving" => "omschrijving",
            ];

        } elseif ($tabelnaam == "project") {

             echo("<form action='index.php?page=newproject' method='post'><div class='field'><input type='submit' id='submit' value='Nieuw project aanmaken'></div></form>");

            $items = [
                "project_ID" => "project_ID",
                "projectnaam" => "projectnaam",
            ];

        } else {

            echo("<div id='melding'>");

            echo "Ongeldige Tabelnaam opgegeven";

            echo("</div>");

        }

            $sql_addition = $tabelnaam . "_ID";

    try {
            $sql = "SELECT * FROM `$tabelnaam` WHERE 1 ORDER BY `$sql_addition` DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute();

        } catch(PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $id = $row[array_values($items)[0]];

            $output .= "<form action='index.php?page=edititem&$tabelnaam=$id&tabelnaam=$tabelnaam' method='post'> <div class='field'>";

            foreach ($items as $for) {

                $value .= $items[$for] . ": " . $row[$for] . " ";

            }

            $output .= "<input id='input' name='item' type='submit' value='$value'>";

            $output .= "<input id='input' name='$tabelnaam' type='hidden' value='$id'>";

            $output .= "<input id='input' name='tabelnaam' type='hidden' value='$tabelnaam'>";

            $output .= "</div> </form>";

            $value = "";

    }

            echo($output);

        } else {

                echo("<script>goto('index.php?page=kiestabel');</script>");

                echo("<div id='melding'>Geen tabelnaam opgegeven ga <a href='index.php?page=kiestabel'>Hier</a> terug!</div>");

            }

        } else {

            loginbarrier();
        }

    ?>
</div>
