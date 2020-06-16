<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/timewizard.css"/>
    <script src="js/before.js"></script>
    <title>TimeWizard</title>
</head>

<body id="body">
<?php

session_cache_limiter('private, must-revalidate');
session_cache_expire(60);

session_start();

if (!isset($_SESSION["L_ID"]) || !isset($_SESSION["L_STATUS"]) || !isset($_SESSION["L_NAME"])) {

    $_SESSION["L_ID"] = "";
    $_SESSION["L_STATUS"] = 0;
    $_SESSION["L_NAME"] = "";

}

include("dbconfig.php");

include("header.php");

if (isset($_GET['page'])) {

    $page = $_GET['page'];

} else {

    $page = "home";
}

if ($page) {

    include("pages/" . $page . ".php");
}

include("footer.php");

?>
<script src="js/after.js"></script>
</body>

</html>
