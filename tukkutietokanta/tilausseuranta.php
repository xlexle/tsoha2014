<?php

require_once "libs/common.php";
require_once "libs/models/asiakas.php";
require_once "libs/models/tilaus.php";
kirjautumisTarkistus();

/* ei testattu */
switch ($_GET['haku']) {
    case "listaa":
        $asiakasnro = $_POST["asiakasnro"];
        if (!onYllapitaja()) {
            $asiakasnro = $_SESSION['kirjautunut'];
        }
        $viite = $_POST["viite"];
        $tuotenro = $_POST["tuotenro"];
        $luontipvm_min = $_POST["luontipvm_min"];
        $luontipvm_max = $_POST["luontipvm_max"];
        $toimituspvm_min = $_POST["toimituspvm_min"];
        $toimituspvm_max = $_POST["toimituspvm_max"];
        $laskutuspvm_min = $_POST["laskutuspvm_min"];
        $laskutuspvm_max = $_POST["laskutuspvm_max"];
        $maksupvm_min = $_POST["maksupvm_min"];
        $maksupvm_max = $_POST["maksupvm_max"];

//        $tilauslista = Tilaus::haeTilaukset($viite, $tuotenro, $luontipvm_min, 
//                $luontipvm_max, $toimituspvm_min, $toimituspvm_max,
//                $laskutuspvm_min, $laskutuspvm_max, $maksupvm_min,
//                $maksupvm_max
//        );

        $data = array(
            'viite' => $viite,
            'tuotenro' => $tuotenro,
            'luontipvm_min' => $luontipvm_min,
            'luontipvm_max' => $luontipvm_max,
            'toimituspvm_min' => $toimituspvm_min,
            'toimituspvm_max' => $toimituspvm_max,
            'laskutuspvm_min' => $laskutuspvm_min,
            'laskutuspvm_max' => $laskutuspvm_max,
            'maksupvm_min' => $maksupvm_min,
            'maksupvm_max' => $maksupvm_max,
        );

        if ($tuotenro != "" && !is_numeric($tuotenro)) {
            unset($data['tuotenro']);
            $data['error'] = "Haku epäonnistui. Tuotenumero saa sisältää vain numeroita.";
            siirryKontrolleriin("tilausseuranta", $data);
        }

        naytaNakyma("tilaus_list", 3, $data);
}

if (isset($_GET['tilausnro'])) {
    naytaNakyma("tilausseuranta", 3, array('success' => "haetaan yksittäinen tilaus..."));
}

naytaNakyma("tilausseuranta", 3, $_SESSION['data']);