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
            $hinta_min = pilkuton($_POST['hinta_min']);
            $hinta_max = pilkuton($_POST['hinta_max']);
            $saldo_min = pilkuton($_POST['saldo_min']);

            $_SESSION['ehdot'] = array(
                'valmistaja' => $valmistaja,
                'hinta_min' => $hinta_min,
                'hinta_max' => $hinta_max,
                'saldo_min' => $saldo_min
            );

            if ((!empty($hinta_min) && !is_numeric($hinta_min)) ||
                    (!empty($hinta_max) && !is_numeric($hinta_max))) {
                unset($_SESSION['hinta_min'], $_SESSION['hinta_max']);
                siirryKontrolleriin("tuotevalikoima", array(
                    'error' => "Haku epäonnistui, koska hinta ei ollut numero.",
                    'valmistaja' => $valmistaja,
                    'saldo_min' => $saldo_min
                ));
            }
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

if (isset($_GET['tuotenro'])) {
    $tuotenro = $_GET['tuotenro'];

    if (empty($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Haku epäonnistui, koska et antanut tuotenumeroa.",
        ));
    }

    if (!is_numeric($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Haku epäonnistui. Tuotenumero saa sisältää vain numeroita.",
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

if (isset($_GET['tallenna'])) {
    yllapitajaTarkistus();
    $tuotenro = $_GET['tallenna'];

    if (empty($tuotenro)) {
        siirryKontrolleriin("tuotevalikoima", array(
            'error' => "Tuotetta ei löytynyt.",
        ));
    }

    naytaNakyma("tuote", 1, array('tuote' => $tuote));
}

naytaNakyma("tuotevalikoima", 1, $_SESSION['data']);