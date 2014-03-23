<?php

class Yllapitaja {

    private $tunnus;
    private $salasana;

    public function __construct($tunnus, $salasana) {
        $this->tunnus = $tunnus;
        $this->salasana = $salasana;
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

    public static function etsiYllapitajat() {
        $sql = "SELECT tunnus, salasana FROM yllapitaja";
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