<?php

require_once "libs/common.php";
require_once "libs/models/ostos.php";
kirjautumisTarkistus();
asiakasTarkistus();

switch ($_GET['action']) {
    case "add":
        if (empty($_POST["tuote"])) {
            siirryKontrolleriin("ostoskori", array(
                'error' => "Tuotteen lisäys tilaukselle epäonnistui, koska tuotenumero tai -koodi puuttui."
            ));
        }
        $tuote = $_POST["tuote"];
        
        siirryKontrolleriin("ostoskori", array(
            'success' => "lisätään tilaukselle ostos..."
        ));

    case "send":
        if (empty($_POST["viite"])) {
            siirryKontrolleriin("ostoskori", array(
                'error' => "Tilaus vaatii ostoviitteen.",
            ));
        }
        $viite = $_POST["viite"];
        
        siirryKontrolleriin("ostoskori", array(
            'success' => "lähetetään tilaus.."
        ));
        
    case "cancel":
        
        siirryKontrolleriin("ostoskori", array(
            'success' => "perutaan tilaus..."
        ));
        break;
}

naytaNakyma("tilaus_new", 2, $_SESSION['data']);