<?php

require_once "libs/common.php";
require_once "libs/models/tuote.php";
kirjautumisTarkistus();

switch ($_GET['haku']) {
    case "listaa":
        $sivu = 1;
        $naytetaan = 5;

        if (isset($_GET['sivu'])) {
            $sivu = (int) $_GET['sivu'];
            if ($sivu < 1)
                $sivu = 1;
        } else {
            $valmistaja = $_POST['valmistaja'];
            $hinta_min = $_POST['hinta_min'];
            $hinta_max = $_POST['hinta_max'];
            $saldo_min = $_POST['saldo_min'];

            $ehdot = array(
                'valmistaja' => $valmistaja,
                'saldo_min' => $saldo_min
            );

            if ((!empty($hinta_min) && !muunnahinnaksi($hinta_min)) ||
                    (!empty($hinta_max) && !muunnahinnaksi($hinta_max))) {
                siirryKontrolleriin("tuotevalikoima", array(
                    'error' => "Haku epäonnistui, koska hinta ei ollut numero.",
                    'valmistaja' => $valmistaja,
                    'saldo_min' => $saldo_min
                ));
            }

            $ehdot['hinta_min'] = muunnahinnaksi($hinta_min);
            $ehdot['hinta_max'] = muunnahinnaksi($hinta_max);
            $_SESSION['ehdot'] = $ehdot;
        }

        /* suojataan tietylle sivulle siirtyminen URL:llä */
        if (!isset($_SESSION['ehdot'])) {
            siirryKontrolleriin("tuotevalikoima", array(
                'error' => "Sivunvaihto epäonnistui, koska hakuehtoja ei ollut asetettu.",
            ));
        }

        listaaTuotteet($_SESSION['ehdot'], $sivu, $naytetaan);

    case "uusi":
        unset($_SESSION['ehdot']);
        siirryKontrolleriin("tuotevalikoima");

    case "avoimet":
        yllapitajaTarkistus();
        naytaNakyma("tuotevalikoima", 1, array('success' => "listataan tuotteet joista avoimia tilauksia..."));
}

function listaaTuotteet($ehdot, $sivu, $naytetaan) {
    $tuotelista = Tuote::haeTuotteet($ehdot['valmistaja'], $ehdot['hinta_min'], $ehdot['hinta_max'], $ehdot['saldo_min'], $sivu, $naytetaan);
    $tuloksia = Tuote::laskeLukumaara($ehdot['valmistaja'], $ehdot['hinta_min'], $ehdot['hinta_max'], $ehdot['saldo_min']);
    $sivuja = ceil($tuloksia / $naytetaan);
    $rivi = $sivu * $naytetaan - ($naytetaan - 1);

    if (empty($tuotelista)) {
        $data['error'] = "Haku ei tuottanut tuloksia.";
        siirryKontrolleriin("tuotevalikoima", $data);
    }

    naytaNakyma("tuote_list", 1, array(
        'tuotteet' => $tuotelista,
        'sivu' => $sivu,
        'tuloksia' => $tuloksia,
        'sivuja' => $sivuja,
        'rivi' => $rivi
    ));
}

/* Siirrytään tuotteen katseluun */
if (isset($_GET['tuotenro'])) {
    $tuotenro = $_GET['tuotenro'];

    if (empty($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Haku epäonnistui, koska tuotenumero puuttui.",
        ));
    }

    if (!is_numeric($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Haku epäonnistui, koska tuotenumero saa sisältää vain numeroita.",
        ));
    }
    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);

    if (is_null($tuote)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotenumerolla ei löytynyt tuotetta.",
            'tuotenro' => $tuotenro
        ));
    }

    naytaNakyma("tuote", 1, array('tuote' => $tuote));
}

/* Siirrytään tuotteen muokkaustilaan */
if (isset($_GET['muokkaa'])) {
    yllapitajaTarkistus();
    $tuotenro = $_GET['muokkaa'];
    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);

    if (empty($tuotenro) || is_null($tuote)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotetta ei löytynyt.",
        ));
    }

    naytaNakyma("tuote", 1, array('tuote' => $tuote, 'muokkaa' => true));
}

/* Tallennetaan tuotteeseen tehdyt muutokset */
if (isset($_GET['tallenna'])) {
    yllapitajaTarkistus();
    $tuotenro = $_GET['tallenna'];
    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);
    // tallennetaan muutokset tietokantaan

    if (empty($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotetta ei löytynyt.",
        ));
    }

    naytaNakyma("tuote", 1, array('tuote' => $tuote));
}

switch ($_GET['tuote']) {
    /* Siirrytään uuden tuotteen lomakkeeseen */
    case "uusi":
        yllapitajaTarkistus();
        $data = (array) $_SESSION['data'];
        naytaNakyma("tuote_new", 1, $data);

    /* Tarkistetaan lomaketiedot ja tallennetaan uusi tuote */
    case "perusta":
        yllapitajaTarkistus();
        $koodi = $_POST['koodi'];
        $kuvaus = $_POST['kuvaus'];
        $valmistaja = $_POST['valmistaja'];
        $hinta = $_POST['hinta'];
        $saldo = $_POST['saldo'];
        $tilauskynnys = $_POST['tilauskynnys'];

        $data = array(
            'koodi' => $koodi,
            'kuvaus' => $kuvaus,
            'valmistaja' => $valmistaja,
            'hinta' => $hinta,
            'saldo' => $saldo,
            'tilauskynnys' => $tilauskynnys
        );

        /* tarkistetaan POST-paluuarvot */
        if (empty($koodi)) {
            $data['error'] = "Tuotteen luonti epäonnistui, koska valmistajan tuotekoodi puuttui.";
            siirryKontrolleriin("tuotevalikoima.php?tuote=uusi", $data);
        }

        if (empty($valmistaja)) {
            $data['error'] = "Tuotteen luonti epäonnistui, koska valmistaja puuttui.";
            siirryKontrolleriin("tuotevalikoima.php?tuote=uusi", $data);
        }

        if (empty($hinta)) {
            $data['error'] = "Tuotteen luonti epäonnistui, koska hinta puuttui.";
            siirryKontrolleriin("tuotevalikoima.php?tuote=uusi", $data);
        }

        if (!muunnahinnaksi($hinta)) {
            unset($data['hinta']);
            $data['error'] = "Tuotteen luonti epäonnistui, koska hinta ei ollut numero.";
            siirryKontrolleriin("tuotevalikoima.php?tuote=uusi", $data);
        }
        $hinta = muunnahinnaksi($hinta);

//        if (empty($kuvaus)) {
//            $kuvaus = "";
//        }

        /* Luodaan uusi tuote tietokantaan */
        $tuote = luoUusiTuoteOlio($koodi, $kuvaus, $valmistaja, $hinta, $saldo, $tilauskynnys);
        $tuotenro = $tuote->lisaaKantaan();

        siirryKontrolleriin("tuotevalikoima.php", array(
            'success' => 'Tuote ' . $tuotenro . ' perustettu onnistuneesti.'
        ));
}

function luoUusiTuoteOlio($koodi, $kuvaus, $valmistaja, $hinta, $saldo, $tilauskynnys) {
    $tuote = new Tuote();
    $tuote->setKoodi($koodi);
    $tuote->setKuvaus($kuvaus);
    $tuote->setValmistaja($valmistaja);
    $tuote->setHinta($hinta);
    $tuote->setSaldo($saldo);
    $tuote->setTilauskynnys($tilauskynnys);
    return $tuote;
}

naytaNakyma("tuotevalikoima", 1, $_SESSION['data']);