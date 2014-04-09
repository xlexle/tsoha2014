<?php

session_start();

function getTietokantayhteys() {
    static $yhteys = null;

    if ($yhteys === null) {
        $yhteys = new PDO('pgsql:');
        $yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $yhteys;
}

function siirryKontrolleriin($kontrolleri, $data = array()) {
    $_SESSION['data'] = (object) $data;
    if (!strpos($kontrolleri, ".php")) {
        header("Location: $kontrolleri.php");
    } else {
        header("Location: $kontrolleri");
    }
    exit();
}

function naytaNakyma($sivu, $tab, $data = array()) {
    $data = (object) $data;
    require 'views/pohja.php';
    unset($_SESSION['data']);
    exit();
}

function kirjautunut() {
    if (isset($_SESSION['kirjautunut'])) {
        return true;
    }

    return false;
}

function kirjautumisTarkistus() {
    if (!isset($_SESSION['kirjautunut'])) {
        siirryKontrolleriin("kirjautuminen", array(
            'error' => "Sivu vaatii sisäänkirjautumisen."
        ));
    }
}

function onYllapitaja() {
    if ($_SESSION['admin']) {
        return true;
    }

    return false;
}

function yllapitajaTarkistus() {
    if (!$_SESSION['admin']) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Sivu vaatii ylläpitäjän oikeudet."
        ));
    }
}

function asiakasTarkistus() {
    if ($_SESSION['admin']) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Sivu vaatii asiakkaan kirjautumistunnukset."
        ));
    }
}

function muunnahinnaksi($merkkijono) {
    $luku = str_replace(",", ".", $merkkijono);
    if (!is_numeric($luku)) {
        return false;
    }
    
    return round($luku, 2);
}