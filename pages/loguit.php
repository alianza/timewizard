<div id="melding">

    <?php

    if ($_SESSION['L_ID'] !== "" && $_SESSION['L_NAME'] !== "" && $_SESSION['L_STATUS'] > 0) {

        echo("Weet u zeker dat je wilt uitloggen?<div class='form'><form action='index.php?page=loguit' method='post'><div class='field'><input type='submit' name='confirm_logout' id='submit' value='Uitloggen' /></div></form></div>");

    } else {

        echo("U bent uitgelogd, klik <a href=index.php?page=home >hier</a> om naar de homepage te gaan.");

    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_logout'])) {

        $_SESSION['L_ID'] = "";
        $_SESSION['L_NAME'] = "";
        $_SESSION['L_STATUS'] = 0;

        echo "Je bent uitgelogd, klik <a href=index.php?page=home >hier</a> om naar de homepage te gaan.";

        header("Refresh:0");

    }

    ?>

</div>
