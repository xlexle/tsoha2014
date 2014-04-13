<?php

require_once "libs/models/asiakas.php";
require_once "libs/common.php";
kirjautumisTarkistus();
yllapitajaTarkistus();

/* haetaan kaikki asiakkaat */
switch ($_GET['haku']) {
    case "listaa":
        $sivu = 1;
        $naytetaan = 10;

        if (isset($_GET['sivu'])) {
            $sivu = (int) $_GET['sivu'];
            if ($sivu < 1) $sivu = 1;
        }
        
        listaaAsiakkaat($sivu, $naytetaan);
}

function listaaAsiakkaat($sivu, $naytetaan) {
    $asiakaslista = Asiakas::haeAsiakkaat($sivu, $naytetaan);
    $tuloksia = Asiakas::laskeLukumaara();
    $sivuja = ceil($tuloksia / $naytetaan);
    $rivi = $sivu * $naytetaan - ($naytetaan - 1);

    if (empty($asiakaslista)) {
        siirryKontrolleriin("asiakashallinta", array('error' => "Haku ei tuottanut tuloksia."));
    }

    $data = array(
        'asiakkaat' => $asiakaslista,
        'sivu' => $sivu,
        'tuloksia' => $tuloksia,
        'sivuja' => $sivuja,
        'rivi' => $rivi
    );

    naytaNakyma("asiakas_list", 4, $data);
}

/* Siirrytään asiakkaan katseluun */
if (isset($_GET['asiakasnro'])) {
    $asiakasnro = htmlspecialchars($_GET['asiakasnro'], ENT_QUOTES);

    if (empty($asiakasnro)) {
        siirryKontrolleriin("asiakashallinta", array(
            'error' => "Haku epäonnistui, koska asiakasnumero puuttui."
        ));
    }

    if (!is_numeric($asiakasnro)) {
        siirryKontrolleriin("asiakashallinta", array(
            'error' => "Haku epäonnistui, koska asiakasnumero saa sisältää vain numeroita."
        ));
    }
    $asiakas = Asiakas::etsiAsiakasAsiakasnumerolla($asiakasnro);

    if (is_null($asiakas)) {
        siirryKontrolleriin("asiakashallinta", array(
            'error' => "Asiakasnumerolla ei löytynyt asiakasta.",
            'asiakasnro' => $asiakasnro
        ));
    }

    $data = (array) $_SESSION['data'];
    $data['asiakas'] = $asiakas;

    naytaNakyma("asiakas", 4, $data);
}

/* Siirrytään asiakkaan muokkaustilaan */
if (isset($_GET['muokkaa'])) {
    $asiakasnro = htmlspecialchars($_GET['muokkaa'], ENT_QUOTES);
    $asiakas = Asiakas::etsiAsiakasAsiakasnumerolla($asiakasnro);

    if (empty($asiakasnro) || is_null($asiakas)) {
        siirryKontrolleriin("asiakashallinta", array(
            'error' => "Asiakasta ei löytynyt.",
        ));
    }

    $data = (array) $_SESSION['data'];
    $data['asiakas'] = $asiakas;
    $data['muokkaa'] = true;

    naytaNakyma("asiakas", 4, $data);
}

/* Tallennetaan asiakkaaseen tehdyt muutokset */
if (isset($_GET['tallenna'])) {
    $asiakasnro = htmlspecialchars($_GET['tallenna'], ENT_QUOTES);
    $asiakas = Asiakas::etsiAsiakasAsiakasnumerolla($asiakasnro);

    if (empty($asiakasnro) || is_null($asiakas)) {
        siirryKontrolleriin("asiakashallinta", array(
            'error' => "Asiakasta ei löytynyt.",
        ));
    }

    $yritysnimi = htmlspecialchars($_POST['yritysnimi'], ENT_QUOTES);
    $osoite = htmlspecialchars($_POST['osoite'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $yhteyshenkilo = htmlspecialchars($_POST['yhteyshenkilo'], ENT_QUOTES);
    $puhelinnumero = htmlspecialchars($_POST['puhelinnumero'], ENT_QUOTES);
    $luottoraja = htmlspecialchars($_POST['luottoraja'], ENT_QUOTES);

    $lomaketiedot = array(
        'yritysnimi' => $yritysnimi,
        'osoite' => $osoite,
        'email' => $email,
        'yhteyshenkilo' => $yhteyshenkilo,
        'puhelinnumero' => $puhelinnumero,
        'luottoraja' => $luottoraja
    );

    tarkistaTallennusLomake($lomaketiedot, 'muokkaa=' . $asiakasnro);
    
    $luottoraja = muunnahinnaksi($luottoraja);

    /* Luodaan uusi asiakas tietokantaan */
    $paivitettavaAsiakas = luoUusiAsiakasOlio($yritysnimi, $osoite, $email, $yhteyshenkilo, $puhelinnumero, $luottoraja);
    $paivitettavaAsiakas->setTunnus($asiakasnro);
    if ($paivitettavaAsiakas->paivitaKantaan()) {
        siirryKontrolleriin("asiakashallinta", array(
            'success' => 'Asiakas ' . $asiakasnro . ' on päivitetty onnistuneesti.'
        ));
    }

    siirryKontrolleriin("asiakashallinta", array(
        'error' => 'Virhe tietokantaoperaatiossa päivitettäessä asiakasta ' . $asiakasnro
    ));
}

if (isset($_GET['poista'])) {
    $asiakasnro = htmlspecialchars($_GET['poista'], ENT_QUOTES);
    $asiakas = Asiakas::etsiAsiakasAsiakasnumerolla($asiakasnro);

    if (empty($asiakasnro) || is_null($asiakas)) {
        siirryKontrolleriin("asiakashallinta", array(
            'error' => "Asiakasta ei löytynyt.",
        ));
    }

    if (Asiakas::poistaKannasta($asiakasnro)) {
        siirryKontrolleriin("asiakashallinta", array(
            'success' => 'Asiakkaan ' . $asiakasnro . ' poisto tietokannasta onnistui.'
        ));
    }

    siirryKontrolleriin("asiakashallinta", array(
        'error' => 'Virhe tietokantaoperaatiossa poistettaessa asiakasta ' . $asiakasnro
    ));
}

switch ($_GET['asiakas']) {
    /* Siirrytään uuden tuotteen lomakkeeseen */
    case "uusi":
        naytaNakyma("asiakas_new", 4, $_SESSION['data']);

    /* Tarkistetaan lomaketiedot ja tallennetaan uusi tuote */
    case "perusta":
        $yritysnimi = htmlspecialchars($_POST['yritysnimi'], ENT_QUOTES);
        $osoite = htmlspecialchars($_POST['osoite'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
        $yhteyshenkilo = htmlspecialchars($_POST['yhteyshenkilo'], ENT_QUOTES);
        $puhelinnumero = htmlspecialchars($_POST['puhelinnumero'], ENT_QUOTES);
        $luottoraja = htmlspecialchars($_POST['luottoraja'], ENT_QUOTES);

        $lomaketiedot = array(
            'yritysnimi' => $yritysnimi,
            'osoite' => $osoite,
            'email' => $email,
            'yhteyshenkilo' => $yhteyshenkilo,
            'puhelinnumero' => $puhelinnumero,
            'luottoraja' => $luottoraja
        );

        tarkistaTallennusLomake($lomaketiedot, "asiakas=uusi");

        $luottoraja = muunnahinnaksi($luottoraja);

        /* Luodaan uusi tuote tietokantaan */
        $uusiAsiakas = luoUusiAsiakasOlio($yritysnimi, $osoite, $email, $yhteyshenkilo, $puhelinnumero, $luottoraja);
        $asiakasnro = $uusiAsiakas->lisaaKantaan();

        siirryKontrolleriin("asiakashallinta.php", array(
            'success' => 'Asiakas ' . $asiakasnro . ' on perustettu onnistuneesti.'
        ));
}

function tarkistaTallennusLomake($data, $lomake) {
    if (empty($data['yritysnimi'])) {
        $data['error'] = "Tallennus epäonnistui, koska yrityksen nimi puuttui.";
        siirryKontrolleriin('asiakashallinta.php?' . $lomake, $data);
    }

    if (empty($data['osoite'])) {
        $data['error'] = "Tallennus epäonnistui, koska osoite puuttui.";
        siirryKontrolleriin('asiakashallinta.php?' . $lomake, $data);
    }

    if (empty($data['email'])) {
        $data['error'] = "Tallennus epäonnistui, koska email puuttui.";
        siirryKontrolleriin('asiakashallinta.php?' . $lomake, $data);
    }

    if (empty($data['yhteyshenkilo'])) {
        $data['error'] = "Tallennus epäonnistui, koska yhteyshenkilö puuttui.";
        siirryKontrolleriin('asiakashallinta.php?' . $lomake, $data);
    }

    if (empty($data['puhelinnumero'])) {
        $data['error'] = "Tallennus epäonnistui, koska puhelinnumero puuttui.";
        siirryKontrolleriin('asiakashallinta.php?' . $lomake, $data);
    }

    if (!empty($data['luottoraja']) && !muunnahinnaksi($data['luottoraja'])) {
        unset($data['luottoraja']);
        $data['error'] = "Tallennus epäonnistui, koska luottoraja ei ollut numero.";
        siirryKontrolleriin('asiakashallinta.php?' . $lomake, $data);
    }
}

function luoUusiAsiakasOlio($yritysnimi, $osoite, $email, $yhteyshenkilo, $puhelinnumero, $luottoraja) {
    $asiakas = new Asiakas();
    $asiakas->setYritysnimi($yritysnimi);
    $asiakas->setOsoite($osoite);
    $asiakas->setEmail($email);
    $asiakas->setYhteyshenkilo($yhteyshenkilo);
    $asiakas->setPuhelinnumero($puhelinnumero);
    $asiakas->setLuottoraja($luottoraja);
    return $asiakas;
}

naytaNakyma("asiakashallinta", 4, $_SESSION['data']);