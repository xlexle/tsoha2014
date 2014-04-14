<?php

require_once "libs/models/ostos.php";
require_once "libs/common.php";
kirjautumisTarkistus();
asiakasTarkistus();

/* tyhjennetään ostoskori ja ladataan sivu uudelleen */
if (isset($_POST['tyhjenna'])) {
    if ($_POST['tyhjenna'] == 1) {
        unset($_SESSION['ostoskori']);
        siirryKontrolleriin("ostoskori");
    }
}

/* päivitetään ostoskorin rivin kappalemäärä */
if (isset($_POST['rivi']) && isset($_POST['kpl'])) {
    $tilausrivi = $_POST['rivi'];
    $maara = $_POST['kpl'];
    $ostoskori = (array) $_SESSION['ostoskori'];
    
    if (empty($tilausrivi)) {
        siirryKontrolleriin("ostoskori");
    }
    
    if (!loytyyRivi($ostoskori, $tilausrivi)) {
        siirryKontrolleriin("ostoskori", array(
            'error' => "Tilausriviä ei löytynyt."
        ));
    }
    
    if ($maara == 0) {
        unset($ostoskori[$tilausrivi]);
        $ostoskori = jarjestaRivit($ostoskori);
    } else {
        $ostoskori = asetaOstoksenMaara($ostoskori, $tilausrivi, $maara);
    }
    
    if (empty($ostoskori)) {
        unset($_SESSION['ostoskori']);
        siirryKontrolleriin("ostoskori");
    }
    
    $_SESSION['ostoskori'] = $ostoskori;
    siirryKontrolleriin("ostoskori");
}

/* tarkistaa löytyykö tilausriviä korista */
function loytyyRivi($ostoskori, $tilausrivi) {
    $loytyy = false;
    
    foreach ($ostoskori as $ostos) {
        if ($ostos->getTilausrivi() == (int) $tilausrivi) {
            $loytyy = true;
            break;
        }
    }
    
    return $loytyy;
}

/* poistaa ostoksen korista */
function poistaOstoskorista($ostoskori, $tilausrivi) {
    $kori = (array) $ostoskori;
    foreach ($kori as $key => $ostos) {
        if ($ostos->getTilausrivi() == (int) $tilausrivi) {
            unset($kori[$key]);
            unset($ostos);
            break;
        }
    }
    
    return $kori;
}

function jarjestarivit($ostoskori) {
    $vanha = (array) $ostoskori;
    $uusi = array();
    $rivi = 0;
    foreach ($vanha as $ostos) {
        ++$rivi;
        $ostos->setTilausrivi($rivi);
        $uusi[$rivi] = $ostos;
    }
    
    return $uusi;
}

/* asettaa ostoksen kappalemaaran */
function asetaOstoksenMaara($ostoskori, $tilausrivi, $maara) {
    $kori = (array) $ostoskori;
    foreach ($kori as $ostos) {
        if ($ostos->getTilausrivi() == (int) $tilausrivi) {
            $ostos->setMaara($maara);
            break;
        }
    }
    
    return $kori;
}

/* lisätään uusi ostos ostoskoriin */
if (isset($_GET['lisaaostos'])) {
    $tuotenro = $_GET['lisaaostos'];
    $kpl = $_GET['kpl'];
    if (!is_numeric($kpl))
        $kpl = 1;

    if (empty($tuotenro)) {
        siirryKontrolleriin("ostoskori", array(
            'error' => "Tuotteen lisäys ostoskoriin epäonnistui, koska tuotenumero puuttui."
        ));
    }

    if (!empty($tuotenro) && !($tuotenro > 100000 && $tuotenro < 1000000)) {
        siirryKontrolleriin("ostoskori", array(
            'tuotenro' => $tuotenro,
            'error' => "Tuotteen lisäys ostoskoriin epäonnistui, koska tuotenumero ei ollut 6-numeroinen luku."
        ));
    }

    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);

    if (is_null($tuote)) {
        siirryKontrolleriin("ostoskori", array(
            'tuotenro' => $tuotenro,
            'error' => "Tuotenumerolla ei löytynyt tuotetta."
        ));
    }

    $riveja = 0;
    $ostoskori = array();
    if (isset($_SESSION['ostoskori'])) {
        $riveja = count($_SESSION['ostoskori']);
        $ostoskori = (array) $_SESSION['ostoskori'];
    }

    $ostos = luoUusiOstosOlio($tuote, $riveja, $kpl);
    $ostoskori[$ostos->getTilausrivi()] = $ostos;
    $_SESSION['ostoskori'] = $ostoskori;

    siirryKontrolleriin("ostoskori");
}

function luoUusiOstosOlio($tuote, $riveja, $kpl) {
    $ostos = new Ostos();
    $ostos->setTilausrivi($riveja + 1);
    $ostos->setTuotenro($tuote->getTuotenro());
    $ostos->setKoodi($tuote->getKoodi());
    $ostos->setValmistaja($tuote->getValmistaja());
    $ostos->setOstohinta($tuote->getHinta());
    $ostos->setSaldo($tuote->getSaldo());
    $ostos->setMaara($kpl);
    return $ostos;
}

naytaNakyma("tilaus_new", 2, $_SESSION['data']);