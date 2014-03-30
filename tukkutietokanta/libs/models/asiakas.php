<?php

require_once "libs/common.php";

class Asiakas {

    private $tunnus;
    private $salasana;

    public function __construct() {
    }

    /* Tähän gettereitä ja settereitä */

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

}