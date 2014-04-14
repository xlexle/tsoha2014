<?php

require_once "libs/models/asiakas.php";
require_once "libs/models/yllapitaja.php";
require_once "libs/common.php";

/* Tarkistetaan kirjautumistila */
if (kirjautunut() && $_GET['kirjaudu'] != "ulos") {
    siirryKontrolleriin("tuotevalikoima");
}

switch ($_GET['kirjaudu']) {
    /* kirjaudutaan ulos */
    case "ulos":
        session_unset();
        siirryKontrolleriin("kirjautuminen", array(
            'success' => "Olet kirjautunut ulos."
        ));

    /* kirjaudutaan sisään */
    case "sisaan":
        if (empty($_POST['tunnus'])) {
            siirryKontrolleriin("kirjautuminen", array(
                'error' => "Kirjautuminen epäonnistui, koska et antanut käyttäjätunnusta."
            ));
        }
        $tunnus = htmlspecialchars($_POST['tunnus'], ENT_QUOTES);

        if (empty($_POST['salasana'])) {
            siirryKontrolleriin("kirjautuminen", array(
                'error' => "Kirjautuminen epäonnistui, koska et antanut salasanaa.",
                'tunnus' => $tunnus
            ));
        }
        $salasana = htmlspecialchars($_POST['salasana'], ENT_QUOTES);

        /* Tarkistetaan onko parametrina saatu yllapitajan tai asiakkaan tunnukset */
        $asiakas = Asiakas::etsiKirjautuja($tunnus, $salasana);
        $yllapitaja = Yllapitaja::etsiKirjautuja($tunnus, $salasana);

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
}

naytaNakyma("kirjautuminen", 0, $_SESSION['data']);