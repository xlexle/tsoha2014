<?php

require_once "libs/yhteys.php";

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

    /* etsii yksittäisen asiakkaan tunnuksen ja salasanan perusteella */
    public static function etsiKirjautuja($tunnus, $salasana) {
        if (!is_numeric($tunnus) || !(floor($tunnus) == $tunnus)) {
            return null;
        }

        $sql = "SELECT tunnus, salasana FROM asiakas
            WHERE tunnus = ? AND salasana = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tunnus, $salasana));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            $asiakas = new Asiakas();
            $asiakas->setTunnus($tulos->tunnus);
            $asiakas->setSalasana($tulos->salasana);
            return $asiakas;
        }
    }

    /* etsii yksittäisen asiakkaan tunnuksen perusteella */
    public static function etsiAsiakasAsiakasnumerolla($asiakasnro) {
        if (!is_numeric($asiakasnro) || !(floor($asiakasnro) == $asiakasnro)) {
            return null;
        }

        $sql = "SELECT tunnus, yritysnimi, osoite, email, yhteyshenkilo, puhelinnumero, luottoraja
            FROM asiakas WHERE tunnus = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($asiakasnro));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            $asiakas = self::haettuAsiakas($tulos);
            $asiakas->setOsoite($tulos->osoite);
            $asiakas->setYhteyshenkilo($tulos->yhteyshenkilo);
            return $asiakas;
        }
    }

    /* luo arrayn kaikista asiakkaista kirjautumistietojen listausta varten */
    public static function haeKaikkiAsiakkaat() {
        $sql = "SELECT tunnus, salasana FROM asiakas ORDER BY tunnus";
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

    /* hakee listan asiakkaita sivutustoiminnon avulla */
    public static function haeAsiakkaat($sivu, $tuloksia) {
        $sql = "SELECT tunnus, yritysnimi, email, puhelinnumero, luottoraja
            FROM asiakas ORDER BY tunnus LIMIT ? OFFSET ?";
        $parametrit = array(
            $tuloksia,
            ($sivu - 1) * $tuloksia
        );

        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute($parametrit);

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $asiakas = self::haettuAsiakas($tulos);
            $tulokset[] = $asiakas;
        }

        return $tulokset;
    }

    /* laskee kaikkien asiakkaiden lukumäärän */
    public static function laskeLukumaara() {
        $sql = "SELECT count(*) FROM asiakas";

        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();
        return $kysely->fetchColumn();
    }

    private function haettuAsiakas($tulos) {
        $asiakas = new Asiakas();
        $asiakas->setTunnus($tulos->tunnus);
        $asiakas->setYritysnimi($tulos->yritysnimi);
        $asiakas->setEmail($tulos->email);
        $asiakas->setPuhelinnumero($tulos->puhelinnumero);
        $asiakas->setLuottoraja($tulos->luottoraja);
        return $asiakas;
    }

    /* lisää yksittäisen asiakas-olion kantaan */
    public function lisaaKantaan() {
        $parametrit = array(
            self::generoiSalasana(8),
            $this->getYritysnimi(),
            $this->getOsoite(),
            $this->getEmail(),
            $this->getYhteyshenkilo(),
            $this->getPuhelinnumero(),
        );

        $arvo = "DEFAULT";
        $luottoraja = $this->getLuottoraja();
        if ($luottoraja >= 0) {
            $parametrit[] = round($luottoraja);
            $arvo = "?";
        }

        $sql = "INSERT INTO asiakas (salasana, yritysnimi, osoite, email, yhteyshenkilo, puhelinnumero, luottoraja) 
            VALUES (?, ?, ?, ?, ?, ?, " . $arvo . ") RETURNING tunnus";

        $kysely = getTietokantayhteys()->prepare($sql);
        $ok = $kysely->execute($parametrit);
        if ($ok) {
            $this->setTunnus($kysely->fetchColumn());
        }
        return $this->tunnus;
    }

    /* generoi uudelle asiakkaalle salasanan */
    private function generoiSalasana($pituus) {
        $merkit = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
        $kpl = mb_strlen($merkit);

        for ($i = 0, $salasana = ''; $i < $pituus; $i++) {
            $index = rand(0, $kpl - 1);
            $salasana .= mb_substr($merkit, $index, 1);
        }

        return $salasana;
    }

    /* päivittää yksittäisen asiakas-olion kantaan */
    public function paivitaKantaan() {
        $parametrit = array(
            $this->getYritysnimi(),
            $this->getOsoite(),
            $this->getEmail(),
            $this->getYhteyshenkilo(),
            $this->getPuhelinnumero(),
        );

        $luottoraja = $this->getLuottoraja();
        $arvo = "DEFAULT";
        if ($luottoraja >= 0) {
            $parametrit[] = round($luottoraja);
            $arvo = "?";
        }

        $parametrit[] = $this->getTunnus();

        $sql = "UPDATE asiakas SET yritysnimi = ?, osoite = ?, email = ?, 
            yhteyshenkilo = ?, puhelinnumero = ?, luottoraja = " . $arvo .
                " WHERE tunnus = ?";

        $kysely = getTietokantayhteys()->prepare($sql);
        $ok = $kysely->execute($parametrit);
        return $ok;
    }
    
    public static function avoimiaTilauksia($asiakasnro) {
        $sql = "SELECT count(*) FROM tilaus
            WHERE asiakasnro = ? AND toimitettu IS NULL";
        
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($asiakasnro));
        return $kysely->fetchColumn();
    }
    
    public static function maksamattomiaLaskuja($asiakasnro) {
        $sql = "SELECT count(*) FROM tilaus
            WHERE asiakasnro = ? 
            AND laskutettu IS NOT NULL
            AND maksettu IS NULL";
        
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($asiakasnro));
        return $kysely->fetchColumn();
    }

    /* poistaa asiakkaan kokonaan */
    public static function poistaKannasta($asiakasnro) {
        $sql = "DELETE FROM asiakas WHERE tunnus = ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        return $kysely->execute(array($asiakasnro));
    }
    
    public static function haeYritysnimi($tilausnro) {
        $sql = "SELECT yritysnimi FROM asiakas, tilaus
            WHERE asiakas.tunnus=tilaus.asiakasnro
            AND tilausnro = ?";
        
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tilausnro));
        return $kysely->fetchColumn();
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