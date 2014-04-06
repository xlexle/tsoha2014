<?php

require_once "libs/common.php";

class Asiakas {

    private $tunnus;
    private $salasana;
    private $yritysnimi;
    private $osoite;
    private $email;
    private $yhteyshenkilo;
    private $puhelinnumero;
    private $luottoraja;

    public function __construct() {
    }

    /* Tietokantafunktiot */

    public static function etsiAsiakasTunnuksilla($tunnus, $salasana) {
        if (!is_numeric($tunnus) || !(floor($tunnus) == $tunnus)) {
            return null;
        }

        $sql = "SELECT tunnus, salasana FROM asiakas WHERE tunnus = ? AND salasana = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tunnus, $salasana));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            $kayttaja = new Asiakas();
            $kayttaja->setTunnus($tulos->tunnus);
            $kayttaja->setSalasana($tulos->salasana);
            return $kayttaja;
        }
    }

    public static function haeKaikkiAsiakkaat() {
        $sql = "SELECT tunnus, salasana FROM asiakas";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $asiakas = new Asiakas();
            $asiakas->setTunnus($tulos->tunnus);
            $asiakas->setSalasana($tulos->salasana);
            $tulokset[] = $asiakas;
        }
        return $tulokset;
    }

    /* Getterit ja setterit */

    public function getTunnus() {
        return $this->tunnus;
    }

    public function setTunnus($tunnus) {
        $this->tunnus = $tunnus;
    }

    public function getSalasana() {
        return $this->salasana;
    }

    public function setSalasana($salasana) {
        $this->salasana = $salasana;
    }
    
    public function getYritysnimi() {
        return $this->yritysnimi;
    }

    public function setYritysnimi($yritysnimi) {
        $this->yritysnimi = $yritysnimi;
    }

    public function getOsoite() {
        return $this->osoite;
    }

    public function setOsoite($osoite) {
        $this->osoite = $osoite;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getYhteyshenkilo() {
        return $this->yhteyshenkilo;
    }

    public function setYhteyshenkilo($yhteyshenkilo) {
        $this->yhteyshenkilo = $yhteyshenkilo;
    }

    public function getPuhelinnumero() {
        return $this->puhelinnumero;
    }

    public function setPuhelinnumero($puhelinnumero) {
        $this->puhelinnumero = $puhelinnumero;
    }

    public function getLuottoraja() {
        return $this->luottoraja;
    }

    public function setLuottoraja($luottoraja) {
        $this->luottoraja = $luottoraja;
    }
}