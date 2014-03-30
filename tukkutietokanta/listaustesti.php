<?php
require "libs/models/asiakas.php";
require "libs/models/yllapitaja.php";
$asiakaslista = Asiakas::haeKaikkiAsiakkaat();
$yllapitajalista = Yllapitaja::haeKaikkiYllapitajat();
?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Listaustestisivu</title>
    </head>
    <body>
        <h1>Listaustesti</h1>
        <h2>Asiakkaiden kirjautumistiedot</h2>
        <ul>
            <?php foreach ($asiakaslista as $asia) { ?>
                <li>Tunnus: <?php echo $asia->getTunnus(); ?>,
                    Salasana: <?php echo $asia->getSalasana(); ?></li>
            <?php } ?>
        </ul>
        <h2>Ylläpitäjien kirjautumistiedot</h2>
        <ul>
            <?php foreach ($yllapitajalista as $asia) { ?>
                <li>Tunnus: <?php echo $asia->getTunnus(); ?>,
                    Salasana: <?php echo $asia->getSalasana(); ?></li>
            <?php } ?>
        </ul>
    </body>
</html>