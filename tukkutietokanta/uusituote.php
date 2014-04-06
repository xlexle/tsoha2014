<?php

require_once "libs/common.php";
kirjautumisTarkistus();
yllapitajaTarkistus();

naytaNakyma("tuote_new", 1, $_SESSION['data']);