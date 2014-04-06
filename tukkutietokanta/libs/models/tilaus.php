<?php

require_once "libs/common.php";

class Tilaus {
    
    private $tilausnro;
    private $ostoviite;
    private $kokonaisarvo;
    private $saapumisaika;
    private $toimitettu;
    private $laskutettu;
    private $maksettu;
    private $asiakasnro;

    public function __construct() {
    }
    
    /* Tietokantafunktiot */
    
    /* Getterit ja setterit */
    
    public function getTilausnro() {
        return $this->tilausnro;
    }

    public function setTilausnro($tilausnro) {
        $this->tilausnro = $tilausnro;
    }

    public function getOstoviite() {
        return $this->ostoviite;
    }

    public function setOstoviite($ostoviite) {
        $this->ostoviite = $ostoviite;
    }

    public function getKokonaisarvo() {
        return $this->kokonaisarvo;
    }

    public function setKokonaisarvo($kokonaisarvo) {
        $this->kokonaisarvo = $kokonaisarvo;
    }

    public function getSaapumisaika() {
        return $this->saapumisaika;
    }

    public function setSaapumisaika($saapumisaika) {
        $this->saapumisaika = $saapumisaika;
    }

    public function getToimitettu() {
        return $this->toimitettu;
    }

    public function setToimitettu($toimitettu) {
        $this->toimitettu = $toimitettu;
    }

    public function getLaskutettu() {
        return $this->laskutettu;
    }

    public function setLaskutettu($laskutettu) {
        $this->laskutettu = $laskutettu;
    }

    public function getMaksettu() {
        return $this->maksettu;
    }

    public function setMaksettu($maksettu) {
        $this->maksettu = $maksettu;
    }

    public function getAsiakasnro() {
        return $this->asiakasnro;
    }

    public function setAsiakasnro($asiakasnro) {
        $this->asiakasnro = $asiakasnro;
    }

}
