<?php

require_once "libs/yhteys.php";
require_once "libs/models/tuote.php";

class Ostos extends Tuote {
    
    private $tilausrivi;
    private $ostohinta;
    private $maara;
    private $peruttu;
    
    /* Tietokantafunktiot */
    
    public static function haeOstokset($tilausnro) {
        $sql = "SELECT ostos.tuotenro, koodi, valmistaja, saldo, tilausrivi, ostohinta, maara, peruttu
            FROM ostos, tuote WHERE ostos.tilausnro = ? AND ostos.tuotenro = tuote.tuotenro
            ORDER BY tilausrivi";

        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tilausnro));

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $ostos = self::haettuOstos($tulos);
            $tulokset[] = $ostos;
        }

        return $tulokset;
    }
    
    private function haettuOstos($tulos) {
        $ostos = new Ostos();
        $ostos->setTuotenro($tulos->tuotenro);
        $ostos->setKoodi($tulos->koodi);
        $ostos->setValmistaja($tulos->valmistaja);
        $ostos->setOstohinta($tulos->ostohinta);
        $ostos->setSaldo($tulos->saldo);
        $ostos->setTilausrivi($tulos->tilausrivi);
        $ostos->setMaara($tulos->maara);
        $ostos->setPeruttu(formatoi($tulos->peruttu));
        return $ostos;
    }
    
    public function lisaaKantaan($tilausnro) {
        $parametrit = array(
            $tilausnro,
            $this->getTuotenro(),
            $this->getTilausrivi(),
            $this->getOstohinta(),
            $this->getMaara()
        );

        $sql = "INSERT INTO ostos (tilausnro, tuotenro, tilausrivi, ostohinta, maara) 
            VALUES(?, ?, ?, ?, ?)";

        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute($parametrit);
    }
    
    /* Getterit ja setterit */
    
    public function getTilausrivi() {
        return $this->tilausrivi;
    }

    public function setTilausrivi($tilausrivi) {
        $this->tilausrivi = $tilausrivi;
    }
    
    public function getOstohinta() {
        return $this->ostohinta;
    }

    public function setOstohinta($ostohinta) {
        $this->ostohinta = $ostohinta;
    }

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