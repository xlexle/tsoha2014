<?php

require_once "libs/common.php";
require_once "libs/models/tuote.php";

class Ostos extends Tuote {
    
    private $maara;
    private $peruttu;
    
    /* Tietokantafunktiot */
    
    /* Getterit ja setterit */
    
    public function getMaara() {
        return $this->maara;
    }

    public function setMaara($maara) {
        $this->maara = $maara;
    }

    public function getPeruttu() {
        return $this->peruttu;
    }

    public function setPeruttu($peruttu) {
        $this->peruttu = $peruttu;
    }

}