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
            $poistettu = $_POST['poistettu'];

            $ehdot = array(
                'valmistaja' => $valmistaja,
                'saldo_min' => $saldo_min,
                'poistettu' => $poistettu
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

        if (!isset($_SESSION['ehdot'])) {
            siirryKontrolleriin("tuotevalikoima", array(
                'error' => "Sivunvaihto epäonnistui, koska hakuehtoja ei ollut asetettu."
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
    $tuotelista = Tuote::haeTuotteet($ehdot['valmistaja'], $ehdot['hinta_min'], $ehdot['hinta_max'], $ehdot['saldo_min'], $ehdot['poistettu'], $sivu, $naytetaan);
    $tuloksia = Tuote::laskeLukumaara($ehdot['valmistaja'], $ehdot['hinta_min'], $ehdot['hinta_max'], $ehdot['saldo_min'], $ehdot['poistettu']);
    $sivuja = ceil($tuloksia / $naytetaan);
    $rivi = $sivu * $naytetaan - ($naytetaan - 1);

    if (empty($tuotelista)) {
        unset($_SESSION['ehdot']);
        siirryKontrolleriin("tuotevalikoima", array('error' => "Haku ei tuottanut tuloksia."));
    }

    $data = array(
        'tuotteet' => $tuotelista,
        'sivu' => $sivu,
        'tuloksia' => $tuloksia,
        'sivuja' => $sivuja,
        'rivi' => $rivi
    );

    if ($ehdot['poistettu'] == 1) {
        $data['poistettu'] = true;
    }

    naytaNakyma("tuote_list", 1, $data);
}

/* Siirrytään tuotteen katseluun */
if (isset($_GET['tuotenro'])) {
    $tuotenro = $_GET['tuotenro'];

    if (empty($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Haku epäonnistui, koska tuotenumero puuttui."
        ));
    }

    if (!is_numeric($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Haku epäonnistui, koska tuotenumero saa sisältää vain numeroita."
        ));
    }
    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);

    if (is_null($tuote)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotenumerolla ei löytynyt tuotetta.",
            'tuotenro' => $tuotenro
        ));
    }

    $data = (array) $_SESSION['data'];
    $data['tuote'] = $tuote;
    
    $poistettu = (string) $tuote->getPoistettu();
    if (!empty($poistettu)) {
        if (!$_SESSION['admin']) {
            siirryKontrolleriin("tuotevalikoima", array(
                'error' => 'Tuote ' . $tuotenro . ' on poistettu valikoimasta.'
            ));
        }
        
        $data['poistettu'] = true;
    }

    naytaNakyma("tuote", 1, $data);
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
    
    $data = (array) $_SESSION['data'];
    $data['tuote'] = $tuote;
    $data['muokkaa'] = true;

    naytaNakyma("tuote", 1, $data);
}

/* Tallennetaan tuotteeseen tehdyt muutokset */
if (isset($_GET['tallenna'])) {
    yllapitajaTarkistus();
    $tuotenro = $_GET['tallenna'];
    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);
    
    if (empty($tuotenro) || is_null($tuote)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotetta ei löytynyt.",
        ));
    }

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

    tarkistaTallennusLomake($data, 'muokkaa=' . $tuotenro);

    $hinta = muunnahinnaksi($hinta);

    /* Luodaan uusi tuote tietokantaan */
    $paivitettavaTuote = luoUusiTuoteOlio($koodi, $kuvaus, $valmistaja, $hinta, $saldo, $tilauskynnys);
    $paivitettavaTuote->setTuotenro($tuotenro);
    if ($paivitettavaTuote->paivitaKantaan()) {
        siirryKontrolleriin("tuotevalikoima.php", array(
            'success' => 'Tuote ' . $tuotenro . ' on päivitetty onnistuneesti.'
        ));
    }

    siirryKontrolleriin("tuotevalikoima", array(
        'error' => 'Virhe tietokantaoperaatiossa päivitettäessä tuotetta ' . $tuotenro
    ));
}

if (isset($_GET['poista'])) {
    yllapitajaTarkistus();
    $tuotenro = $_GET['poista'];
    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);
    
    if (empty($tuotenro) || is_null($tuote)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotetta ei löytynyt.",
        ));
    }

    if (Tuote::onPoistettu($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => 'Tuote ' . $tuotenro . ' on jo poistettu valikoimasta.'
        ));
    }

    if (Tuote::poistaValikoimasta($tuotenro)) {
        siirryKontrolleriin('tuotevalikoima.php?tuotenro=' . $tuotenro, array(
            'success' => "Tuotteen poisto valikoimasta onnistui."
        ));
    }

    siirryKontrolleriin("tuotevalikoima", array(
        'error' => 'Virhe tietokantaoperaatiossa poistettaessa tuotetta ' . $tuotenro
    ));
}

if (isset($_GET['poistafinal'])) {
    yllapitajaTarkistus();
    $tuotenro = $_GET['poistafinal'];
    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);
    
    if (empty($tuotenro) || is_null($tuote)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotetta ei löytynyt.",
        ));
    }

    if (!Tuote::onPoistettu($tuotenro)) {
        siirryKontrolleriin('tuotevalikoima.php?muokkaa=' . $tuotenro, array(
            'error' => 'Tuotetta ' . $tuotenro . ' ei voida poistaa lopullisesti, sillä tuote on vielä valikoimassa.',
        ));
    }

    if (Tuote::poistaLopullisesti($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'success' => "Tuotteen lopullinen poisto tietokannasta onnistui."
        ));
    }

    siirryKontrolleriin("tuotevalikoima", array(
        'error' => 'Virhe tietokantaoperaatiossa poistettaessa tuotetta ' . $tuotenro
    ));
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

        tarkistaTallennusLomake($data, "tuote=uusi");

        $hinta = muunnahinnaksi($hinta);

        /* Luodaan uusi tuote tietokantaan */
        $uusiTuote = luoUusiTuoteOlio($koodi, $kuvaus, $valmistaja, $hinta, $saldo, $tilauskynnys);
        $tuotenro = $uusiTuote->lisaaKantaan();

        siirryKontrolleriin("tuotevalikoima.php", array(
            'success' => 'Tuote ' . $tuotenro . ' on perustettu onnistuneesti.'
        ));
}

function tarkistaTallennusLomake($data, $get) {
    if (empty($data['koodi'])) {
        $data['error'] = "Tallennus epäonnistui, koska valmistajan tuotekoodi puuttui.";
        siirryKontrolleriin('tuotevalikoima.php?' . $get, $data);
    }

    if (empty($data['valmistaja'])) {
        $data['error'] = "Tallennus epäonnistui, koska valmistaja puuttui.";
        siirryKontrolleriin('tuotevalikoima.php?' . $get, $data);
    }

    if (empty($data['hinta'])) {
        $data['error'] = "Tallennus epäonnistui, koska hinta puuttui.";
        siirryKontrolleriin('tuotevalikoima.php?' . $get, $data);
    }

    if (!muunnahinnaksi($data['hinta'])) {
        unset($data['hinta']);
        $data['error'] = "Tallennus epäonnistui, koska hinta ei ollut numero.";
        siirryKontrolleriin('tuotevalikoima.php?' . $get, $data);
    }
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