    <div class="form">

    <?php

        if ($_SESSION['L_STATUS'] == 2) {

            ?>

        <h1>Bewerk</h1>

        <p>Wijzig de velden</p>


        <?php

            if (isset($_GET['tabelnaam'])) {

            $tabelnaam = $_GET['tabelnaam'];
            $output = "";
            $key = "";
            $value = "";
            $url = $_SERVER['REQUEST_URI'];

        if (isset($_GET['project'])) {

            $ID = $_GET['project'];

            $kolom = "project_ID";

            $query = "UPDATE `project` SET `project_ID`= :newproject_ID,`projectnaam`= :projectnaam WHERE `project_ID` = :project_ID";

        }

        if (isset($_GET['user'])) {

            $ID = $_GET['user'];

            $kolom = "user_ID";

            $query = "UPDATE `user` SET `user_ID`= :newuser_ID,`voornaam`= :voornaam,`tussenvoegsels`= :tussenvoegsels,`achternaam`= :achternaam,`geboortedatum`= :geboortedatum,`email`= :email,`gebruikersnaam`= :gebruikersnaam WHERE `user_ID` = :user_ID";

        }

        if (isset($_GET['admin'])) {

            $ID = $_GET['admin'];

            $kolom = "admin_ID";

            $query = "UPDATE `admin` SET `admin_ID`= :newadmin_ID,`gebruikersnaam`= :gebruikersnaam WHERE `admin_ID` = :admin_ID";

        }


        if (isset($_GET['log'])) {

            $ID = $_GET['log'];

            $kolom = "log_ID";

            $query = "UPDATE `log` SET `log_ID`= :log_ID,`datum`= :datum,`uren`= :uren, `opmerking`= :opmerking, `taak_taak_ID`= :taak_ID,`user_user_ID`= :user_ID,`project_project_ID`= :project_ID WHERE `log_ID` = :log_ID";

        }

        if (isset($_GET['taak'])) {

            $ID = $_GET['taak'];

            $kolom = "taak_ID";

            $query = "UPDATE `taak` SET `taak_ID`= :taak_ID,`omschrijving`= :omschrijving WHERE `taak_ID` = :taak_ID";

        }

    try {
            $sql = "SELECT * FROM `$tabelnaam` WHERE $kolom = $ID";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

        $output .= "<form action='index.php?page=$tabelnaam-update' method='post'> <div class='field'>";

            foreach ($result as $key => $value) {

                if ($key !== "wachtwoord") {

                    if (is_numeric($value) == 1) {

                        $output .= "<label class='label' for='$key'>$key</label>";

                        $output .= "<input id='input' name='$key' type='number' value='$value'>";

                    } elseif ($key == "geboortedatum" || $key == "datum") {

                        $output .= "<label class='label' for='$key'>$key</label>";

                        $output .= "<input id='input' name='$key' type='date' value='$value'>";

                    } else {

                        $output .= "<label class='label' for='$key'>$key</label>";

                        $output .= "<input id='input' name='$key' type='text' value='$value'>";

                    }

                }

            }

            $output .= "<input id='input' name='sql' type='hidden' value='$query'>";

            $output .= "<input id='input' name='ID' type='hidden' value='$ID'>";

            $output .= "<input id='input' name='url' type='hidden' value='$url'>";

            $output .= "<input id='submit' name='submit' type='submit' value='Update'>";

            $output .= "<input id='submit' name='verwijder' type='submit' value='Verwijder'>";

            $output .= "</div> </form>";

            echo($output);

        } else {

                echo("<script>goto('index.php?page=kiestabel');</script>");

                echo("<div id='melding'>Geen tabelnaam en item ID opgegeven ga <a href='index.php?page=kiestabel'>Hier</a> terug!</div>");

            }

        } else {

            loginbarrier();
        }

    ?>

        </div>
