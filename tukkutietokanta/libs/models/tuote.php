<?php

require_once "libs/common.php";

class Tuote {

    protected $tuotenro;
    protected $koodi;
    private $kuvaus;
    protected $valmistaja;
    protected $hinta;
    protected $saldo;
    private $tilauskynnys;
    private $avoimiaTilauksia = 0; /* placeholder value */

    public function __construct() {
        
    }

    /* Tietokantafunktiot */

    public static function etsiTuoteTuotenumerolla($tuotenro) {
        if (!is_numeric($tuotenro) || !(floor($tuotenro) == $tuotenro)) return null;

        $sql = "SELECT tuotenro, koodi, kuvaus, valmistaja, hinta, saldo, tilauskynnys
            FROM tuote WHERE tuotenro = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tuotenro));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            $tuote = self::haettuTuote($tulos);
            $tuote->setTilauskynnys($tulos->tilauskynnys);
            return $tuote;
        }
    }

    public static function haeTuotteet($valmistaja, $hinta_min, $hinta_max, $saldo_min, $sivu, $tuloksia) {
        $sql = "SELECT tuotenro, koodi, kuvaus, valmistaja, hinta, saldo FROM tuote WHERE TRUE";
        $parametrit = array();
        
        if ($valmistaja != "") {
            $sql .= " AND valmistaja = ?";
            $parametrit[] = $valmistaja;
        }

        if (is_numeric($hinta_min)) {
            $sql .= " AND hinta >= ?";
            $parametrit[] = $hinta_min;
        }

        if (is_numeric($hinta_max)) {
            $sql .= " AND hinta <= ?";
            $parametrit[] = $hinta_max;
        }

        if (is_numeric($saldo_min)) {
            $sql .= " AND saldo >= ?";
            $parametrit[] = floor($saldo_min);
        }
        
        $parametrit[] = $tuloksia;
        $parametrit[] = ($sivu-1)*$tuloksia;

        $sql .= " ORDER BY valmistaja, koodi LIMIT ? OFFSET ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute($parametrit);

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $tuote = self::haettuTuote($tulos);
            $tulokset[] = $tuote;
        }
        
        return $tulokset;
    }
    
    public static function laskeLukumaara($valmistaja, $hinta_min, $hinta_max, $saldo_min) {
        $sql = "SELECT count(*) FROM tuote WHERE TRUE";
        $parametrit = array();
        
        if ($valmistaja != "") {
            $sql .= " AND valmistaja = ?";
            $parametrit[] = $valmistaja;
        }

        if (is_numeric($hinta_min)) {
            $sql .= " AND hinta >= ?";
            $parametrit[] = $hinta_min;
        }

        if (is_numeric($hinta_max)) {
            $sql .= " AND hinta <= ?";
            $parametrit[] = $hinta_max;
        }

        if (is_numeric($saldo_min)) {
            $sql .= " AND saldo >= ?";
            $parametrit[] = floor($saldo_min);
        }
        
        $kysely = getTietokantayhteys()->prepare($sql);
        if (empty($parametrit)) $kysely->execute();
        else $kysely->execute($parametrit);
        return $kysely->fetchColumn();
    }
//    
//    private function maaritaParametrit($valmistaja, $hinta_min, $hinta_max, $saldo_min, &$sql) {
//        $array = array();
//        
//        if ($valmistaja != "") {
//            $sql .= " AND valmistaja = ?";
//            $array[] = $valmistaja;
//        }
//
//        if (is_numeric($hinta_min)) {
//            $sql .= " AND hinta >= ?";
//            $array[] = $hinta_min;
//        }
//
//        if (is_numeric($hinta_max)) {
//            $sql .= " AND hinta <= ?";
//            $array[] = $hinta_max;
//        }
//
//        if (is_numeric($saldo_min)) {
//            $sql .= " AND saldo >= ?";
//            $array[] = floor($saldo_min);
//        }
//        
//        return $array;
//    }

    private function haettuTuote($tulos) {
        $tuote = new Tuote();
        $tuote->setTuotenro($tulos->tuotenro);
        $tuote->setKoodi($tulos->koodi);
        $tuote->setKuvaus($tulos->kuvaus);
        $tuote->setValmistaja($tulos->valmistaja);
        $tuote->setHinta($tulos->hinta);
        $tuote->setSaldo($tulos->saldo);
        return $tuote;
    }

    /* Getterit ja setterit */

    public function getTuotenro() {
        return $this->tuotenro;
    }

    public function setTuotenro($tuotenro) {
        $this->tuotenro = $tuotenro;
    }

    public function getKoodi() {
        return $this->koodi;
    }

    public function setKoodi($koodi) {
        $this->koodi = $koodi;
    }

    public function getKuvaus() {
        return $this->kuvaus;
    }

    public function setKuvaus($kuvaus) {
        $this->kuvaus = $kuvaus;
    }

    public function getValmistaja() {
        return $this->valmistaja;
    }

    public function setValmistaja($valmistaja) {
        $this->valmistaja = $valmistaja;
    }

    public function getHinta() {
        return $this->hinta;
    }

    public function setHinta($hinta) {
        $this->hinta = $hinta;
    }

    public function getSaldo() {
        return $this->saldo;
    }

    public function setSaldo($saldo) {
        $this->saldo = $saldo;
    }

    public function getTilauskynnys() {
        return $this->tilauskynnys;
    }

    public function setTilauskynnys($tilauskynnys) {
        $this->tilauskynnys = $tilauskynnys;
    }

    public function getAvoimiaTilauksia() {
        return $this->avoimiaTilauksia;
    }

    public function setAvoimiaTilauksia($avoimiaTilauksia) {
        $this->avoimiaTilauksia = $avoimiaTilauksia;
    }
}