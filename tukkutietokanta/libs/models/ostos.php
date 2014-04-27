<?php

require_once "libs/yhteys.php";
require_once "libs/models/tuote.php";

class Ostos extends Tuote {

    private $tilausrivi;
    private $ostohinta;
    private $allokoitumaara;
    private $tilattumaara;
    private $peruttu;

    /* tietokantafunktiot */

    /* hakee kaikki ostokset jotka liittyvät tiettyyn tilaukseen */
    public static function haeOstokset($tilausnro) {
        $sql = "SELECT ostos.tuotenro, koodi, valmistaja, saldo, tilausrivi, ostohinta, allokoitumaara, tilattumaara
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
        $ostos->setAllokoituMaara($tulos->allokoitumaara);
        $ostos->setTilattuMaara($tulos->tilattumaara);
        $ostos->setPeruttu(formatoi($tulos->peruttu));
        return $ostos;
    }

    /* Siirtaa varastosta saldoja ostokselle */
    public function siirraSaldot() {
        $varastossa = $this->getSaldo();
        $tilattu = $this->getTilattuMaara();
        if ($varastossa == 0) {
            $this->setAllokoituMaara(0);
        } elseif ($varastossa >= $tilattu) {
            self::paivitaSaldo($varastossa - $tilattu, $this->getTuotenro());
            $this->setAllokoituMaara($tilattu);
        } else {
            self::paivitaSaldo(0, $this->getTuotenro());
            $this->setAllokoituMaara($varastossa);
        }
    }

    /* lisää yksittäisen ostos-olion kantaan tietyllä tilausnumerolla */
    public function lisaaKantaan($tilausnro) {
        $parametrit = array(
            $tilausnro,
            $this->getTuotenro(),
            $this->getTilausrivi(),
            $this->getOstohinta(),
            $this->getAllokoituMaara(),
            $this->getTilattuMaara()
        );

        $sql = "INSERT INTO ostos (tilausnro, tuotenro, tilausrivi, ostohinta, allokoitumaara, tilattumaara) 
            VALUES(?, ?, ?, ?, ?, ?)";

        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute($parametrit);
    }
    
    public static function haeTuotenro($tilausnro, $tilausrivi) {
        $sql = "SELECT tuotenro FROM ostos WHERE tilausnro = ? AND tilausrivi = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tilausnro, $tilausrivi));
        return $kysely->fetchColumn();
    }

    /* getterit ja setterit */

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

    public function getAllokoituMaara() {
        return $this->allokoitumaara;
    }

    public function setAllokoituMaara($allokoitumaara) {
        $this->allokoitumaara = $allokoitumaara;
    }

    public function getTilattuMaara() {
        return $this->tilattumaara;
    }

    public function setTilattuMaara($tilattumaara) {
        $this->tilattumaara = $tilattumaara;
    }

    public function getPeruttu() {
        return $this->peruttu;
    }

    public function setPeruttu($peruttu) {
        $this->peruttu = $peruttu;
    }

}