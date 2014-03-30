<?php
session_start();

function getTietokantayhteys() {
    static $yhteys = null; //Muuttuja, jonka sisältö säilyy getTietokantayhteys-kutsujen välillä.

    if ($yhteys === null) {
        //Tämä koodi suoritetaan vain kerran, sillä seuraavilla 
        //funktion suorituskerroilla $yhteys-muuttujassa on sisältöä.
        $yhteys = new PDO('pgsql:');
        $yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $yhteys;
}

function naytaNakyma($sivu, $data = array()) {
    $data = (object) $data;
    require 'views/pohja.php';
    exit();
}

function kirjautunutTunnuksella($tunnus) {
    if (isset($_SESSION['kirjautunut'])) {
        $kayttaja = $_SESSION['kayttaja'];
        if ($kayttaja == $tunnus) {
            return true;
        }
    }

    return false;
}/*
 * tarpeellinen?
 */

function kirjautunut() {
    if (isset($_SESSION['kirjautunut'])) {
        return true;
    }
    
    return false;
}

function onAdminOikeudet($tunnus) {
    if ($_SESSION['admin'] == true) {
        return true;
    }
    
    return false;
}/*
 * tarpeellinen?
 */
