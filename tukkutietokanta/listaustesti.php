<?php
require_once "libs/tietokantayhteys.php";
require_once "libs/models/yllapitaja.php";
$lista = Yllapitaja::etsiYllapitajat();
?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Listaustestisivu</title>
    </head>
    <body>
        <h1>Listaustesti</h1>
        <ul>
            <?php foreach ($lista as $asia) { ?>
                <li><?php echo $asia->getTunnus(); ?></li>
            <?php } ?>
        </ul>
    </body>
</html>
