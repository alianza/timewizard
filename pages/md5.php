<?php

if (isset($_POST['submit'])) {

    $text = $_POST['text'];

    $text = md5($text);

    echo($text);

}

?>

<form method="post">

    <input type="text" name="text">

    <input type="submit" name="submit">

</form>
