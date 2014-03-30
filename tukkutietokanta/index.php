<?php
require_once "libs/common.php";

if (kirjautunut()) {
    naytaNakyma("tuotevalikoima.php", array(
        'success' => "Olet kirjautunut sisään. Tervetuloa!",
        'admin' => $_SESSION['admin']
    ));
}

naytaNakyma("kirjautuminen.php");