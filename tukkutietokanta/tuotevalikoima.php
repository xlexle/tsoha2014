<?php

require_once "libs/models/tuote.php";
require_once "libs/common.php";
kirjautumisTarkistus();

/* listataan tuotteet lomakkeen tietojen perusteella */
switch ($_GET['haku']) {
    case "listaa":
        $sivu = 1;
        $naytetaan = 10;

        if (isset($_GET['sivu'])) {
            $sivu = (int) $_GET['sivu'];
            if ($sivu < 1)
                $sivu = 1;
        } else {
            $valmistaja = htmlspecialchars($_POST['valmistaja'], ENT_QUOTES);
            $hinta_min = htmlspecialchars($_POST['hinta_min'], ENT_QUOTES);
            $hinta_max = htmlspecialchars($_POST['hinta_max'], ENT_QUOTES);
            $saldo_min = $_POST['saldo_min'];
            $poistettu = $_POST['poistettu'];

            $lomaketiedot = array(
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

            $lomaketiedot['hinta_min'] = muunnahinnaksi($hinta_min);
            $lomaketiedot['hinta_max'] = muunnahinnaksi($hinta_max);
            $_SESSION['ehdot'] = $lomaketiedot;
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

function listaaTuotteet($lomaketiedot, $sivu, $naytetaan) {
    $tuotelista = Tuote::haeTuotteet($lomaketiedot, $sivu, $naytetaan);
    $tuloksia = Tuote::laskeLukumaara($lomaketiedot);
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

    if ($lomaketiedot['poistettu'] == 1) $data['poistettu'] = true;

    naytaNakyma("tuote_list", 1, $data);
}

/* Siirrytään tuotteen katseluun */
if (isset($_GET['tuotenro'])) {
    $tuotenro = htmlspecialchars($_GET['tuotenro'], ENT_QUOTES);

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
    $tuotenro = htmlspecialchars($_GET['muokkaa'], ENT_QUOTES);
    if (Tuote::onPoistettu($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => 'Muokkaus ei onnistunut, koska tuote ' . $tuotenro . ' on poistettu valikoimasta.',
        ));
    }
    
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
    $tuotenro = htmlspecialchars($_GET['tallenna'], ENT_QUOTES);
    if (Tuote::onPoistettu($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => 'Tallennus ei onnistunut, koska tuote ' . $tuotenro . ' on poistettu valikoimasta.',
        ));
    }
    
    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);
    if (empty($tuotenro) || is_null($tuote)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotetta ei löytynyt.",
        ));
    }

    $koodi = htmlspecialchars($_POST['koodi'], ENT_QUOTES);
    $kuvaus = htmlspecialchars($_POST['kuvaus'], ENT_QUOTES);
    $valmistaja = htmlspecialchars($_POST['valmistaja'], ENT_QUOTES);
    $hinta = htmlspecialchars($_POST['hinta'], ENT_QUOTES);
    $saldo = $_POST['saldo'];
    $tilauskynnys = $_POST['tilauskynnys'];

    $lomaketiedot = array(
        'koodi' => $koodi,
        'kuvaus' => $kuvaus,
        'valmistaja' => $valmistaja,
        'hinta' => $hinta,
        'saldo' => $saldo,
        'tilauskynnys' => $tilauskynnys
    );

    tarkistaTallennusLomake($lomaketiedot, 'muokkaa=' . $tuotenro);

    $hinta = muunnahinnaksi($hinta);

    /* Luodaan uusi tuote tietokantaan */
    $paivitettavaTuote = luoUusiTuoteOlio($koodi, $kuvaus, $valmistaja, $hinta, $saldo, $tilauskynnys);
    $paivitettavaTuote->setTuotenro($tuotenro);
    if ($paivitettavaTuote->paivitaKantaan()) {
        siirryKontrolleriin('tuotevalikoima.php?tuotenro=' . $tuotenro);
    }

    siirryKontrolleriin("tuotevalikoima", array(
        'error' => 'Virhe tietokantaoperaatiossa päivitettäessä tuotetta ' . $tuotenro
    ));
}

/* poistetaan tuote valikoimasta */
if (isset($_GET['poista'])) {
    yllapitajaTarkistus();
    $tuotenro = htmlspecialchars($_GET['poista'], ENT_QUOTES);
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
        siirryKontrolleriin('tuotevalikoima.php?tuotenro=' . $tuotenro);
    }

    siirryKontrolleriin("tuotevalikoima", array(
        'error' => 'Virhe tietokantaoperaatiossa poistettaessa tuotetta ' . $tuotenro
    ));
}

/* palautetaan tuote valikoimaan */
if (isset($_GET['palauta'])) {
    yllapitajaTarkistus();
    $tuotenro = htmlspecialchars($_GET['palauta'], ENT_QUOTES);
    $tuote = Tuote::etsiTuoteTuotenumerolla($tuotenro);
    
    if (empty($tuotenro) || is_null($tuote)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotetta ei löytynyt.",
        ));
    }

    if (!Tuote::onPoistettu($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => 'Tuotetta ' . $tuotenro . ' ei voida palauttaa, koska sitä ei ole poistettu valikoimasta.'
        ));
    }

    if (Tuote::palautaValikoimaan($tuotenro)) {
        siirryKontrolleriin('tuotevalikoima.php?tuotenro=' . $tuotenro);
    }

    siirryKontrolleriin("tuotevalikoima", array(
        'error' => 'Virhe tietokantaoperaatiossa poistettaessa tuotetta ' . $tuotenro
    ));
}

/* poistetaan tuote tietokannasta */
if (isset($_GET['poistafinal'])) {
    yllapitajaTarkistus();
    $tuotenro = htmlspecialchars($_GET['poistafinal'], ENT_QUOTES);
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
        naytaNakyma("tuote_new", 1, $_SESSION['data']);

    /* tallennetaan uusi tuote */
    case "perusta":
        yllapitajaTarkistus();
        $koodi = htmlspecialchars($_POST['koodi'], ENT_QUOTES);
        $kuvaus = htmlspecialchars($_POST['kuvaus'], ENT_QUOTES);
        $valmistaja = htmlspecialchars($_POST['valmistaja'], ENT_QUOTES);
        $hinta = htmlspecialchars($_POST['hinta'], ENT_QUOTES);
        $saldo = $_POST['saldo'];
        $tilauskynnys = $_POST['tilauskynnys'];

        $lomaketiedot = array(
            'koodi' => $koodi,
            'kuvaus' => $kuvaus,
            'valmistaja' => $valmistaja,
            'hinta' => $hinta,
            'saldo' => $saldo,
            'tilauskynnys' => $tilauskynnys
        );

        tarkistaTallennusLomake($lomaketiedot, "tuote=uusi");

        $hinta = muunnahinnaksi($hinta);

        /* Luodaan uusi tuote tietokantaan */
        $uusiTuote = luoUusiTuoteOlio($koodi, $kuvaus, $valmistaja, $hinta, $saldo, $tilauskynnys);
        $tuotenro = $uusiTuote->lisaaKantaan();

        siirryKontrolleriin("tuotevalikoima", array(
            'success' => 'Tuote ' . $tuotenro . ' on perustettu onnistuneesti.'
        ));
}

/* tarkistetaan tuotteen tallennuslomakkeen tiedot ja suoritetaan ohjaukset */
function tarkistaTallennusLomake($data, $lomake) {
    if (empty($data['koodi'])) {
        $data['error'] = "Tallennus epäonnistui, koska valmistajan tuotekoodi puuttui.";
        siirryKontrolleriin('tuotevalikoima.php?' . $lomake, $data);
    }

    if (empty($data['valmistaja'])) {
        $data['error'] = "Tallennus epäonnistui, koska valmistaja puuttui.";
        siirryKontrolleriin('tuotevalikoima.php?' . $lomake, $data);
    }

    if (empty($data['hinta'])) {
        $data['error'] = "Tallennus epäonnistui, koska hinta puuttui.";
        siirryKontrolleriin('tuotevalikoima.php?' . $lomake, $data);
    }

    if (!muunnahinnaksi($data['hinta'])) {
        unset($data['hinta']);
        $data['error'] = "Tallennus epäonnistui, koska hinta ei ollut numero.";
        siirryKontrolleriin('tuotevalikoima.php?' . $lomake, $data);
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