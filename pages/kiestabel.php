    <div class="form">

    <?php

        if ($_SESSION['L_STATUS'] == 2) {

            ?>

        <h1>Tabellen</h1>

        <p>Kies de tabel dat u wilt bewerken</p>

        <?php


    try {
            $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = 'timewizard'";
            $stmt = $db->prepare($sql);
            $stmt->execute();

        } catch(PDOException $e) {

            echo("<div id='melding'>");

            echo $e->GetMessage();

            echo("</div>");

        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $tabelnaam = $row['TABLE_NAME'];

        echo " <form action='index.php?page=kiesitem&tabelnaam=$tabelnaam' method='post'>

        <div class='field'>

        <input id='input' name='tabelnaam' type='submit' value='$tabelnaam'>

         </div> </form>";

    }

        } else {

            loginbarrier();
        }

    ?>

        </div>
