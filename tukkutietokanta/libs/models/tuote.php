<?php

require_once "libs/yhteys.php";

class Tuote {

    protected $tuotenro;
    protected $koodi;
    private $kuvaus;
    protected $valmistaja;
    protected $hinta;
    protected $saldo;
    private $tilauskynnys;
    private $avoimiaTilauksia;
    private $poistettu;

    public function __construct() {
        
    }

    /* Tietokantafunktiot */

    /* etsii yksittäisen tuotteen kannasta */
    public static function etsiTuoteTuotenumerolla($tuotenro) {
        if (!is_numeric($tuotenro) || !(floor($tuotenro) == $tuotenro))
            return null;
        $sql = "SELECT tuotenro, koodi, kuvaus, valmistaja, hinta, saldo, tilauskynnys, poistettu
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

    /* hakee tuotteet jotka vastaavat hakuehtoja, käyttää sivutustoimintoa */
    public static function haeTuotteet($lomaketiedot, $sivu, $tuloksia) {
        $sql = "SELECT tuotenro, koodi, kuvaus, valmistaja, hinta, saldo, poistettu
            FROM tuote WHERE TRUE";
        list($parametrit, $lisaasql) = self::maaritaParametrit((array) $lomaketiedot);
        $parametrit[] = $tuloksia;
        $parametrit[] = ($sivu - 1) * $tuloksia;
        $sql .= $lisaasql . " ORDER BY valmistaja, koodi LIMIT ? OFFSET ?";

        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute($parametrit);

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $tuote = self::haettuTuote($tulos);
            $tulokset[] = $tuote;
        }

        return $tulokset;
    }

    /* laskee niiden tuotteiden lukumäärän, jotka vastaavat hakuehtoja */
    public static function laskeLukumaara($lomaketiedot) {
        $sql = "SELECT count(*) FROM tuote WHERE TRUE";
        list($parametrit, $lisaasql) = self::maaritaParametrit($lomaketiedot);

        $sql .= $lisaasql;
        $kysely = getTietokantayhteys()->prepare($sql);
        if (empty($parametrit))
            $kysely->execute();
        else
            $kysely->execute($parametrit);
        return $kysely->fetchColumn();
    }

    /* määrittää sql-kyselyssä käytettävät parametrit hakuehtojen perusteella */
    private function maaritaParametrit($lomaketiedot) {
        $params = array();
        $lisaasql = "";

        $valmistaja = $lomaketiedot['valmistaja'];
        $hinta_min = $lomaketiedot['hinta_min'];
        $hinta_max = $lomaketiedot['hinta_max'];
        $saldo_min = $lomaketiedot['saldo_min'];
        $poistettu = $lomaketiedot['poistettu'];

        if (!empty($valmistaja)) {
            $lisaasql .= " AND valmistaja = ?";
            $params[] = $valmistaja;
        }

        if (is_numeric($hinta_min)) {
            $lisaasql .= " AND hinta >= ?";
            $params[] = $hinta_min;
        }

        if (is_numeric($hinta_max)) {
            $lisaasql .= " AND hinta <= ?";
            $params[] = $hinta_max;
        }

        if (is_numeric($saldo_min)) {
            $lisaasql .= " AND saldo >= ?";
            $params[] = floor($saldo_min);
        }

        if ($poistettu == 1) $lisaasql .= " AND poistettu IS NOT NULL";
        else $lisaasql .= " AND poistettu IS NULL";

        return array($params, $lisaasql);
    }

    private function haettuTuote($tulos) {
        $tuote = new Tuote();
        $tuote->setTuotenro($tulos->tuotenro);
        $tuote->setKoodi($tulos->koodi);
        $tuote->setKuvaus($tulos->kuvaus);
        $tuote->setValmistaja($tulos->valmistaja);
        $tuote->setHinta($tulos->hinta);
        $tuote->setSaldo($tulos->saldo);
        $tuote->setAvoimiaTilauksia(self::laskeAvoimetTilaukset($tulos->tuotenro));
        $tuote->setPoistettu($tulos->poistettu);
        return $tuote;
    }
    
    private function laskeAvoimetTilaukset($tuotenro) {
        $sql = "SELECT count(*) FROM tilaus 
            WHERE toimitettu IS NULL
            AND tilausnro in 
            (SELECT tilausnro FROM ostos
            WHERE tuotenro = ?
            AND tilattumaara > 0)";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tuotenro));
        return $kysely->fetchColumn();
    }

    /* lisää yksittäisen tuote-olion kantaan */
    public function lisaaKantaan() {
        $parametrit = array(
            $this->getKoodi(),
            $this->getKuvaus(),
            $this->getValmistaja(),
            $this->getHinta(),
            $this->getSaldo()
        );

        $arvo = "DEFAULT";
        $tilauskynnys = $this->getTilauskynnys();
        if ($tilauskynnys >= 0) {
            $parametrit[] = $tilauskynnys;
            $arvo = "?";
        }

        $sql = "INSERT INTO tuote (koodi, kuvaus, valmistaja, hinta, saldo, tilauskynnys) 
            VALUES(?, ?, ?, ?, ?, " . $arvo . ") RETURNING tuotenro";

        $kysely = getTietokantayhteys()->prepare($sql);
        $ok = $kysely->execute($parametrit);
        if ($ok) {
            $this->setTuotenro($kysely->fetchColumn());
        }
        return $this->tuotenro;
    }

    /* päivittää yksittäisen tuote-olion kantaan */
    public function paivitaKantaan() {
        $parametrit = array(
            $this->getKoodi(),
            $this->getKuvaus(),
            $this->getValmistaja(),
            $this->getHinta(),
            $this->getSaldo()
        );

        $tilauskynnys = $this->getTilauskynnys();
        $arvo = "DEFAULT";
        if ($tilauskynnys >= 0) {
            $parametrit[] = $tilauskynnys;
            $arvo = "?";
        }

        $sql = "UPDATE tuote SET koodi = ?, kuvaus = ?, valmistaja = ?, 
            hinta = ?, saldo = ?, tilauskynnys = " . $arvo . " WHERE tuotenro = ?";
        $parametrit[] = $this->getTuotenro();

        $kysely = getTietokantayhteys()->prepare($sql);
        $ok = $kysely->execute($parametrit);
        return $ok;
    }
    
    public static function paivitaSaldo($saldo, $tuotenro) {
        $sql = "UPDATE tuote SET saldo = ? WHERE tuotenro = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($saldo, $tuotenro));
    }

    /* tarkistaa, onko tuote poistettu valikoimasta */
    public static function onPoistettu($tuotenro) {
        $sql = "SELECT * FROM tuote WHERE tuotenro = ? AND poistettu IS NOT NULL LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tuotenro));
        return $kysely->fetchColumn();
    }

    /* poistaa tuotteen valikoimasta */
    public static function poistaValikoimasta($tuotenro) {
        $sql = "UPDATE tuote SET poistettu = LOCALTIMESTAMP WHERE tuotenro = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        return $kysely->execute(array($tuotenro));
    }

    /* palauttaa tuotteen valikoimaan */
    public static function palautaValikoimaan($tuotenro) {
        $sql = "UPDATE tuote SET poistettu = DEFAULT WHERE tuotenro = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        return $kysely->execute(array($tuotenro));
    }

    /* poistaa tuotteen lopullisesti kannasta */
    public static function poistaLopullisesti($tuotenro) {
        $sql = "DELETE FROM tuote WHERE tuotenro = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        return $kysely->execute(array($tuotenro));
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

    public function getPoistettu() {
        return $this->poistettu;
    }

    public function setPoistettu($poistettu) {
        $this->poistettu = $poistettu;
    }

}
