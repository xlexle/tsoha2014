<?php

require_once "libs/models/ostos.php";
require_once "libs/common.php";
kirjautumisTarkistus();
asiakasTarkistus();

if (isset($_POST['tyhjenna'])) {
    if ($_POST['tyhjenna'] == 1) {
        unset($_SESSION['ostoskori']);
        siirryKontrolleriin("ostoskori", array(
            'success' => "Ostoskori on tyhjennetty."
        ));
    }
}

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
    $ostoskori[] = $ostos;
    $_SESSION['ostoskori'] = $ostoskori;

    siirryKontrolleriin('ostoskori', array(
        'success' => $kpl . ' kpl tuotetta ' . $tuotenro . ' lisätty ostoskoriin.'
    ));
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