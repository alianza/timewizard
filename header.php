<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/ico" href="./img/favicon.ico"/>
    <title>TimeWizard</title>
</head>

<div class="header">
    <a href="index.php?page=home"> <img src="./img/logo.png" class="logo"> </a>

    <?php

            if($_SESSION["L_ID"] !== "" && $_SESSION["L_NAME"] !== "" && $_SESSION["L_STATUS"] > 0) {

                echo "<p class='status'>Je bent ingelogd als: " . "<br>" . $_SESSION['L_NAME'] . " <br /> Log <a href='index.php?page=loguit'>Hier</a> uit!</p>";

            } else {

                echo "<p class='status'>Log <a href='index.php?page=login'>Hier</a> in!</p>";

            }

        ?>
    <div class="nav">
        <ul>
            <li class="home"><a id="home" href="index.php?page=home">Home</a></li>

            <?php

                            if($_SESSION["L_ID"] == "" && $_SESSION["L_NAME"] == "" && $_SESSION["L_STATUS"] == 0) {

                        ?>

				<li class="about"><a id="about" href="index.php?page=about">About TimeWizard</a></li>
                <li class="damscommerce"><a id="damscommerce" href="http://damscommerce.nl/">About DamsCommerce</a></li>

                <?php

                            }

                            if($_SESSION["L_ID"] !== "" && $_SESSION["L_NAME"] !== "" && $_SESSION["L_STATUS"] == 1) {

                        ?>

                    <li class="rapport_1"><a id="rapport_1" href="index.php?page=rapport_1">Overzicht uren</a></li>
                    <li class="log_time_choose_project"><a id="log_time_choose_project" href="index.php?page=log_time_choose_project">Log Time</a></li>
                    <li class="explore"><a id="explore" href="index.php?page=explore">Explore Projects & Tasks</a></li>
                    <li class="wachtwoord_wijzigen"><a id="wachtwoord_wijzigen" href="index.php?page=wachtwoord_wijzigen">Wachtwoord Wijzigen</a></li>

                    <?php

                            }

                     if($_SESSION["L_ID"] !== "" && $_SESSION["L_NAME"] !== "" && $_SESSION["L_STATUS"] == 2) {

                        ?>

                        <li class="rapport_1"><a id="rapport_1" href="index.php?page=rapport_1">Overzicht per User</a></li>
                        <li class="rapport_2"><a id="rapport_2" href="index.php?page=rapport_2">Overzicht per Project</a></li>
						<li class="explore"><a id="explore" href="index.php?page=explore">Explore Projects & Tasks</a></li>
                        <li class="kiestabel"><a id="kiestabel" href="index.php?page=kiestabel">Database Management</a></li>

                        <?php

                    }

                         ?>

        </ul>

    </div>
</div>
