<?php

require_once "libs/yhteys.php";

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
    
    /* hakee yksittäisen tilauksen kannasta */
    public static function etsiTilausTilausnumerolla($tilausnro) {
        if (!is_numeric($tilausnro) || !(floor($tilausnro) == $tilausnro)) return null;
        return self::haeTilaus(array('tilausnro' => $tilausnro));
    }
    
    /* hakee tietyn asiakkaan yksittäisen tilauksen */
    public static function etsiAsiakkaanTilaus($tilausnro, $asiakasnro) {
        if (!is_numeric($tilausnro) || !(floor($tilausnro) == $tilausnro)) return null;
        if (!is_numeric($asiakasnro) || !(floor($asiakasnro) == $asiakasnro)) return null;
        return self::haeTilaus(array('tilausnro' => $tilausnro, 'asiakasnro' => $asiakasnro));
    }
    
    private function haeTilaus($array) {
        $sql = "SELECT tilausnro, ostoviite, kokonaisarvo, saapumisaika, toimitettu, laskutettu, maksettu, asiakasnro
            FROM tilaus WHERE TRUE";
        
        $hakuehdot = (array) $array;
        $parametrit = array();
        if (isset($hakuehdot['tilausnro'])) {
            $parametrit[] = $hakuehdot['tilausnro'];
            $sql .= " AND tilausnro = ?";
        }
        if (isset($hakuehdot['asiakasnro'])) {
            $parametrit[] = $hakuehdot['asiakasnro'];
            $sql .= " AND asiakasnro = ?";
        }
        $sql .= " LIMIT 1";
        
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute($parametrit);

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            $tilaus = self::haettuTilaus($tulos);
            $tilaus->setToimitettu($tulos->toimitettu);
            $tilaus->setLaskutettu($tulos->laskutettu);
            $tilaus->setMaksettu($tulos->maksettu);
            return $tilaus;
        }
    }
    
    /* hakee kaikki tilaukset jotka vastaavat hakuehtoja, käyttää sivutustoimintoa */
    public static function haeTilaukset($lomaketiedot, $sivu, $tuloksia) {
        $sql = "SELECT tilausnro, ostoviite, kokonaisarvo, saapumisaika, asiakasnro 
            FROM tilaus WHERE TRUE";
        list($parametrit, $lisaasql) = self::maaritaParametrit((array) $lomaketiedot);
        $parametrit[] = $tuloksia;
        $parametrit[] = ($sivu - 1) * $tuloksia;
        $sql .= $lisaasql . " ORDER BY saapumisaika desc LIMIT ? OFFSET ?";
        
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute($parametrit);

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $tilaus = self::haettuTilaus($tulos);
            $tulokset[] = $tilaus;
        }

        return $tulokset;
    }
    
    /* laskee niiden tilausten lukumäärän, jotka vastaavat hakuehtoja */
    public static function laskeLukumaara($lomaketiedot) {
        $sql = "SELECT count(*) FROM tilaus WHERE TRUE";
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

        $asiakasnro = $lomaketiedot['asiakasnro'];
        $viite = $lomaketiedot['viite'];
        $tuotenro = $lomaketiedot['tuotenro'];
        $toimitettu = $lomaketiedot['toimitettu'];
        $laskutettu = $lomaketiedot['laskutettu'];
        $maksettu = $lomaketiedot['maksettu'];

        if (!empty($asiakasnro)) {
            $lisaasql .= " AND asiakasnro = ?";
            $params[] = $asiakasnro;
        }
        
        if (!empty($viite)) {
            $lisaasql .= " AND ostoviite = ?";
            $params[] = $viite;
        }
        
        if ($toimitettu == 1) $lisaasql .= " AND toimitettu IS NOT NULL";
        if ($laskutettu == 1) $lisaasql .= " AND laskutettu IS NOT NULL";
        if ($maksettu == 1) $lisaasql .= " AND maksettu IS NOT NULL";
        
        if (is_numeric($tuotenro)) {
            $lisaasql .= " AND tilausnro in 
                (SELECT tilausnro from ostos
                WHERE tuotenro = ? 
                AND tilattumaara > 0)";
            $params[] = $tuotenro;
        }

        return array($params, $lisaasql);
    }
    
    private function haettuTilaus($tulos) {
        $tilaus = new Tilaus();
        $tilaus->setTilausnro($tulos->tilausnro);
        $tilaus->setOstoviite($tulos->ostoviite);
        $tilaus->setKokonaisarvo($tulos->kokonaisarvo);
        $tilaus->setSaapumisaika($tulos->saapumisaika);
        $tilaus->setAsiakasnro($tulos->asiakasnro);
        return $tilaus;
    }
    
    /* lisää yksittäisen tilaus-olion kantaan */
    public function lisaaKantaan() {
        $parametrit = array(
            $this->getOstoviite(),
            $this->getKokonaisarvo(),
            $this->getAsiakasnro()
        );
        
        $sql = "INSERT INTO tilaus (ostoviite, kokonaisarvo, asiakasnro)
            VALUES (?, ?, ?) RETURNING tilausnro";
        
        $kysely = getTietokantayhteys()->prepare($sql);
        $ok = $kysely->execute($parametrit);
        if ($ok) {
            $this->setTilausnro($kysely->fetchColumn());
        }
        return $this->tilausnro;
    }
    
    /* päivittää yksittäisen tilaus-olion kantaan */
    public function paivitaKantaan() {
        $parametrit = array(
            $this->getOstoviite(),
            $this->getKokonaisarvo(),
            $this->getTilausnro()
        );
        
        $sql = "UPDATE tilaus SET ostoviite = ?, kokonaisarvo = ? 
            WHERE tilausnro = ?";
        
        $kysely = getTietokantayhteys()->prepare($sql);

        $ok = $kysely->execute($parametrit);
        return $ok;
    }
    
    /* asettaa tilauksen toimitetuksi */
    public static function asetaToimitetuksi($tilausnro) {
        $sql = "UPDATE tilaus SET toimitettu = LOCALTIMESTAMP WHERE tilausnro = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        return $kysely->execute(array($tilausnro));
    }
    
    /* asettaa tilauksen laskutetuksi */
    public static function asetaLaskutetuksi($tilausnro) {
        $sql = "UPDATE tilaus SET laskutettu = LOCALTIMESTAMP WHERE tilausnro = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        return $kysely->execute(array($tilausnro));
    }
    
    /* asettaa tilauksen maksetuksi */
    public static function asetaMaksetuksi($tilausnro) {
        $sql = "UPDATE tilaus SET maksettu = LOCALTIMESTAMP WHERE tilausnro = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        return $kysely->execute(array($tilausnro));
    }
    
    /* muuttaa tilauksen ostoviitteen */
    public static function asetaViite($tilausnro, $ostoviite) {
        $sql = "UPDATE tilaus SET ostoviite = ? WHERE tilausnro = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        return $kysely->execute(array($ostoviite, $tilausnro));
    }
    
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

    public function getYritysnimi() {
        return Asiakas::haeYritysnimi($this->tilausnro);
    }
}
