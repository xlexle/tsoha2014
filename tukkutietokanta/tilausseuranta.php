<?php

require_once "libs/models/asiakas.php";
require_once "libs/models/tilaus.php";
require_once "libs/models/ostos.php";
require_once "libs/common.php";
kirjautumisTarkistus();

/* listataan tilaukset lomakkeen tietojen perusteella */
switch ($_GET['haku']) {
    case "listaa":
        $sivu = 1;
        $naytetaan = 10;

        if (isset($_GET['sivu'])) {
            $sivu = (int) $_GET['sivu'];
            if ($sivu < 1)
                $sivu = 1;
        } else {
            $asiakasnro = $_SESSION['kirjautunut'];
            if (onYllapitaja()) {
                $asiakasnro = htmlspecialchars($_POST["asiakasnro"], ENT_QUOTES);
            }
            $viite = htmlspecialchars($_POST["viite"], ENT_QUOTES);
            $tuotenro = htmlspecialchars($_POST["tuotenro"], ENT_QUOTES);
            $toimitettu = $_POST['toimitettu'];
            $laskutettu = $_POST['laskutettu'];
            $maksettu = $_POST['maksettu'];

            $data = array(
                'asiakasnro' => $asiakasnro,
                'viite' => $viite,
                'tuotenro' => $tuotenro,
                'toimitettu' => $toimitettu,
                'laskutettu' => $laskutettu,
                'maksettu' => $maksettu
            );

            if (!empty($asiakasnro) && !($asiakasnro > 1000 && $asiakasnro < 10000)) {
                unset($data['asiakasnro']);
                $data['error'] = "Haku epäonnistui, koska asiakasnumero ei ollut 4-numeroinen luku.";
                siirryKontrolleriin("tilausseuranta", $data);
            }

            $_SESSION['ehdot'] = $data;
        }

        if (!isset($_SESSION['ehdot'])) {
            siirryKontrolleriin("tilausseuranta", array(
                'error' => "Sivunvaihto epäonnistui, koska hakuehtoja ei ollut asetettu."
            ));
        }

        if (!empty($tuotenro) && !($tuotenro > 100000 && tuotenro < 1000000)) {
            unset($data['tuotenro']);
            $data['error'] = "Haku epäonnistui, koska tuotenumero ei ollut 6-numeroinen luku.";
            siirryKontrolleriin("tilausseuranta", $data);
        }

        listaaTilaukset($_SESSION['ehdot'], $sivu, $naytetaan);

    case "uusi":
        unset($_SESSION['ehdot']);
        siirryKontrolleriin("tilausseuranta");
}

function listaaTilaukset($lomaketiedot, $sivu, $naytetaan) {
    $tilauslista = Tilaus::haeTilaukset($lomaketiedot, $sivu, $naytetaan);
    $tuloksia = Tilaus::laskeLukumaara($lomaketiedot);
    $sivuja = ceil($tuloksia / $naytetaan);
    $rivi = $sivu * $naytetaan - ($naytetaan - 1);

    if (empty($tilauslista)) {
        unset($_SESSION['ehdot']);
        siirryKontrolleriin("tilausseuranta", array('error' => "Haku ei tuottanut tuloksia."));
    }

    $data = array(
        'tilaukset' => $tilauslista,
        'sivu' => $sivu,
        'tuloksia' => $tuloksia,
        'sivuja' => $sivuja,
        'rivi' => $rivi
    );

    naytaNakyma("tilaus_list", 3, $data);
}

/* haetaan yksittäinen tilaus */
if (isset($_GET['tilausnro'])) {
    $tilausnro = htmlspecialchars($_GET['tilausnro'], ENT_QUOTES);

    if (empty($tilausnro)) {
        siirryKontrolleriin("tilausseuranta", array(
            'error' => "Haku epäonnistui, koska tilausnumero puuttui."
        ));
    }

    if (!($tilausnro > 10000000 && $tilausnro < 100000000)) {
        siirryKontrolleriin("tilausseuranta", array(
            'error' => "Haku epäonnistui, koska tilausnumero ei ollut 8-numeroinen luku."
        ));
    }

    if (onYllapitaja()) {
        $tilaus = Tilaus::etsiTilausTilausnumerolla($tilausnro);
        naytaTilaus($tilaus, $tilausnro);
    } else {
        $asiakasnro = $_SESSION['kirjautunut'];
        $tilaus = Tilaus::etsiAsiakkaanTilaus($tilausnro, $asiakasnro);
        naytaTilaus($tilaus, $tilausnro);
    }
}

function naytaTilaus($tilaus, $tilausnro) {
    if (is_null($tilaus)) {
        siirryKontrolleriin("tilausseuranta", array(
            'error' => "Tilausnumerolla ei löytynyt tilausta.",
            'tilausnro' => $tilausnro
        ));
    }
    
    $data = (array) maaritaSivuMuuttujat($tilaus, $tilausnro);

    naytaNakyma("tilaus", 3, $data);
}

/* siirrytään tilauksen muokkaustilaan */
if (isset($_GET['muokkaa'])) {
    yllapitajaTarkistus();
    $tilausnro = htmlspecialchars($_GET['muokkaa'], ENT_QUOTES);
    $tilaus = Tilaus::etsiTilausTilausnumerolla($tilausnro);

    if (empty($tilausnro) || is_null($tilaus)) {
        siirryKontrolleriin("tilausseuranta", array(
            'error' => "Tilausta ei löytynyt.",
        ));
    }

    $toimitettu = $tilaus->getToimitettu();
    if (!empty($toimitettu)) {
        siirryKontrolleriin('tilausseuranta.php?tilausnro=' . $tilausnro, array(
            'error' => "Tilausta ei voi enää muokata.",
        ));
    }

    /* päivitetään ostoviite */
    if (isset($_POST['ostoviite'])) {
        $ostoviite = htmlspecialchars($_POST['ostoviite'], ENT_QUOTES);
        if (empty($ostoviite)) {
            siirryKontrolleriin('tilausseuranta.php?muokkaa=' . $tilausnro, array(
                'error' => "Tallennus epäonnistui, koska ostoviite puuttui.",
            ));
        }

        if (Tilaus::asetaViite($tilausnro, substr($ostoviite, 0, 50))) {
            siirryKontrolleriin('tilausseuranta.php?tilausnro=' . $tilausnro);
        }

        siirryKontrolleriin('tilausseuranta.php?tilausnro=' . $tilausnro, array(
            'error' => 'Virhe tietokantaoperaatiossa päivitettäessä tilausta ' . $tilausnro
        ));
    }

    $data = (array) maaritaSivuMuuttujat($tilaus, $tilausnro);
    $data['muokkaa'] = true;

    naytaNakyma("tilaus", 3, $data);
}

function maaritaSivuMuuttujat($tilaus, $tilausnro) {
    $data = (array) $_SESSION['data'];
    $data['tilaus'] = $tilaus;
    $data['ostokset'] = Ostos::haeOstokset($tilausnro);

    $toimitettu = $tilaus->getToimitettu();
    if (!empty($toimitettu))
        $data['toimitettu'] = true;
    $laskutettu = $tilaus->getLaskutettu();
    if (!empty($laskutettu))
        $data['laskutettu'] = true;
    $maksettu = $tilaus->getMaksettu();
    if (!empty($maksettu))
        $data['maksettu'] = true;

    return $data;
}

/* luodaan uusi tilaus ostoskorin sisältämien ostosten perusteella */
switch ($_GET['ostoskori']) {
    case "laheta":
        asiakasTarkistus();
        $ostoviite = htmlspecialchars($_POST["ostoviite"], ENT_QUOTES);
        if (empty($ostoviite)) {
            siirryKontrolleriin("ostoskori", array(
                'error' => "Tilaus vaatii ostoviitteen.",
            ));
        }

        $ostokset = (array) $_SESSION['ostoskori'];
        unset($_SESSION['ostoskori']);
        $lisattavaTilaus = luoUusiTilausOlio($ostoviite, laskeKokonaisArvo($ostokset));
        $tilausnro = $lisattavaTilaus->lisaaKantaan();
        foreach ($ostokset as $ostos) {
            $ostos->siirraSaldot();
            $ostos->lisaaKantaan($tilausnro);
        }

        siirryKontrolleriin('tilausseuranta.php?tilausnro=' . $tilausnro);
}

function tarkistaTallennusLomake($data, $get) {
    if (empty($data['ostoviite'])) {
        $data['error'] = "Tallennus epäonnistui, koska ostoviite puuttui.";
        siirryKontrolleriin('tilausseuranta.php?' . $get, $data);
    }
}

/* lasketaan ostosten yhteisarvo tilaustaulun kokonaisarvo-saraketta varten */
function laskeKokonaisArvo($ostokset) {
    $kokonaisarvo = 0;
    foreach ($ostokset as $ostos) {
        $kokonaisarvo += $ostos->getTilattuMaara() * $ostos->getOstohinta();
    }

    return $kokonaisarvo;
}

function luoUusiTilausOlio($ostoviite, $kokonaisarvo) {
    $tilaus = new Tilaus();
    $tilaus->setOstoviite($ostoviite);
    $tilaus->setKokonaisarvo($kokonaisarvo);
    $tilaus->setAsiakasnro($_SESSION['kirjautunut']);
    return $tilaus;
}

naytaNakyma("tilausseuranta", 3, $_SESSION['data']);