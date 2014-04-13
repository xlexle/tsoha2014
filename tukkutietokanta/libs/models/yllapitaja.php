<?php

require_once "libs/yhteys.php";

class Yllapitaja {

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

    public static function etsiKirjautuja($tunnus, $salasana) {
        $sql = "SELECT tunnus, salasana FROM yllapitaja WHERE tunnus = ? AND salasana = ? LIMIT 1";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute(array($tunnus, $salasana));

        $tulos = $kysely->fetchObject();
        if ($tulos == null) {
            return null;
        } else {
            $kayttaja = new Yllapitaja();
            $kayttaja->setTunnus($tulos->tunnus);
            $kayttaja->setSalasana($tulos->salasana);
            return $kayttaja;
        }
    }

    public static function haeKaikkiYllapitajat() {
        $sql = "SELECT tunnus, salasana FROM yllapitaja ORDER BY tunnus";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kysely->execute();

        $tulokset = array();
        foreach ($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
            $yllapitaja = new Yllapitaja();
            $yllapitaja->setTunnus($tulos->tunnus);
            $yllapitaja->setSalasana($tulos->salasana);
            $tulokset[] = $yllapitaja;
        }
        return $tulokset;
    }
}