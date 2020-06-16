<?php

DEFINE("DB_USER", "root");
DEFINE("DB_PASS", "");

try {

    $db = new PDO("mysql:host=localhost;dbname=timewizard",DB_USER,DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    echo $e->getMessage();

}

function loginbarrier () {

     echo("<div id='melding'>Je moet ingelogd zijn om deze pagina weer te geven. Log <a href='index.php?page=login'>Hier</a> in!</div>");

//    echo('<script>loginbarrier();</script>');

}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

?>
