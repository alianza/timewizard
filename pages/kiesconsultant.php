    <div class="form">

        <h1>Consultants</h1>

        <p>Kies de consultant waar u het rapport voor wilt weergeven.</p>

    <?php

        if ($_SESSION['L_STATUS'] == 2) {


    try {
            $sql = "SELECT * FROM `consultant`";
            $stmt = $db->prepare($sql);
            $stmt->execute();

        } catch(PDOException $e) {

        echo("<div id='melding'>");

            echo $e->GetMessage();

        echo("</div>");

        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultant_ID = $row['consultant_ID'];
            $voornaam = $row['voornaam'];
            $tussenvoegsels = $row['tussenvoegsels'];
            $achternaam = $row['achternaam'];

        echo " <form action='index.php?page=rapport_1' method='post'>

        <div class='field'>

    <input id='input' name='consultant' type='submit' value='$voornaam $tussenvoegsels $achternaam'>

    <input id='input' name='consultant_ID' type='hidden' value='$consultant_ID'>

         </div> </form>";

    }

        unset($consultant_ID);

        } else {

            loginbarrier();
        }

    ?>

        </div>
