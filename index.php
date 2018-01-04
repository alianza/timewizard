<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/timewizard.css" />
    <script src="js/before.js"></script>
</head>

<body id="body">
    <?php

        header('Cache-Control: no cache');
        session_cache_limiter('private_no_expire');

        session_start();

        if(!isset($_SESSION["L_ID"]) || !isset($_SESSION["L_STATUS"]) || !isset($_SESSION["L_NAME"])) {

            $_SESSION["L_ID"] = "";
            $_SESSION["L_STATUS"] = 0;
            $_SESSION["L_NAME"] = "";

        }

        include("dbconfig.php");

        include("header.php");

        if(isset($_GET['page'])) {

            $page = $_GET['page'];

        } else {

            $page = "home";
        }

        if($page) {

            include("pages/".$page.".php");
        }

            include("footer.php");

?>
        <script src="js/after.js"></script>
</body>

</html>
