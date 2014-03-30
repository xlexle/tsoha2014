<?php

require_once "libs/common.php";
require_once "libs/models/asiakas.php";
require_once "libs/models/yllapitaja.php";

//Tarkistetaaan onko kirjauduttu jo sisään, jos on, nollataan sessio
if (kirjautunut()) {
    unset($_SESSION['kirjautunut']);
    naytaNakyma("kirjautuminen.php", array(
    'success' => "Olet kirjautunut ulos."
    ));
}

//Tarkistetaan että vaaditut kentät on täytetty:
if (empty($_POST["username"])) {
    naytaNakyma("kirjautuminen.php", array(
    'error' => "Kirjautuminen epäonnistui. Anna käyttäjätunnus."
    ));
}
$tunnus = $_POST["username"];

if (empty($_POST["password"])) {
    naytaNakyma("kirjautuminen.php", array(
        'tunnus' => $tunnus,
        'error' => "Kirjautuminen epäonnistui. Anna salasana."
    ));
}
$salasana = $_POST["password"];

/* Tarkistetaan onko parametrina saatu yllapitajan tai asiakkaan tunnukset */
$asiakas = Asiakas::etsiAsiakasTunnuksilla($tunnus, $salasana);
$yllapitaja = Yllapitaja::etsiYllapitajaTunnuksilla($tunnus, $salasana);
if (!is_null($asiakas)) {
    $_SESSION['kirjautunut'] = $asiakas->getTunnus();
    $_SESSION['admin'] = false;
    header("Location: index.php");
} else if (!is_null($yllapitaja)) {
    $_SESSION['kirjautunut'] = $yllapitaja->getTunnus();
    $_SESSION['admin'] = true;
    header("Location: index.php");
} else {
    naytaNakyma("kirjautuminen.php", array(
        'tunnus' => $tunnus,
        'error' => "Kirjautuminen epäonnistui. Antamasi tunnus tai salasana on väärä."
    ));
}