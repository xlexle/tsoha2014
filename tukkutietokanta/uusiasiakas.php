<?php

require_once "libs/common.php";
kirjautumisTarkistus();
yllapitajaTarkistus();

naytaNakyma("asiakas_new", 4, $_SESSION['data']);