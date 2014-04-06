<?php

require_once "libs/common.php";
require_once "libs/models/asiakas.php";
require_once "libs/models/yllapitaja.php";

/* Tarkistetaan kirjautumistila */
if (kirjautunut() && $_GET['kirjaudu'] != "ulos") {
    siirryKontrolleriin("tuotevalikoima");
}

switch ($_GET['kirjaudu']) {
    case "ulos":
        session_unset();
        siirryKontrolleriin("kirjautuminen", array(
            'success' => "Olet kirjautunut ulos."
        ));

    case "sisaan":
        if (empty($_POST['username'])) {
            siirryKontrolleriin("kirjautuminen", array(
                'error' => "Kirjautuminen epäonnistui, koska et antanut käyttäjätunnusta."
            ));
        }
        $tunnus = $_POST['username'];

        if (empty($_POST["password"])) {
            siirryKontrolleriin("kirjautuminen", array(
                'error' => "Kirjautuminen epäonnistui, koska et antanut salasanaa.",
                'tunnus' => $tunnus
            ));
        }
        $salasana = $_POST['password'];

        /* Tarkistetaan onko parametrina saatu yllapitajan tai asiakkaan tunnukset */
        $asiakas = Asiakas::etsiAsiakasTunnuksilla($tunnus, $salasana);
        $yllapitaja = Yllapitaja::etsiYllapitajaTunnuksilla($tunnus, $salasana);

        if (!is_null($asiakas)) {
            $_SESSION['kirjautunut'] = $asiakas->getTunnus();
            $_SESSION['admin'] = false;
            siirryKontrolleriin("tuotevalikoima", array(
                'success' => "Olet kirjautunut sisään. Tervetuloa!"
            ));
        } else if (!is_null($yllapitaja)) {
            $_SESSION['kirjautunut'] = $yllapitaja->getTunnus();
            $_SESSION['admin'] = true;
            siirryKontrolleriin("tuotevalikoima", array(
                'success' => "Olet kirjautunut sisään. Tervetuloa!"
            ));
        } else {
            siirryKontrolleriin("kirjautuminen", array(
                'error' => "Kirjautuminen epäonnistui, koska annoit väärän tunnuksen tai salasanan."
            ));
        }

    default:
        break;
}

naytaNakyma("kirjautuminen", 0, $_SESSION['data']);