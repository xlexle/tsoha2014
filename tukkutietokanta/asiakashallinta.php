<?php

require_once "libs/common.php";
require_once "libs/models/asiakas.php";
kirjautumisTarkistus();
yllapitajaTarkistus();

/* ei testattu */
if (isset($_GET['asiakasnro'])) {
        siirryKontrolleriin("asiakashallinta", array(
            'success' => "haetaan yksittäisen asiakas..."
        ));
}

switch ($_GET['haku']) {
    case "kaikki":
        siirryKontrolleriin("asiakashallinta", array(
            'success' => "listataan kaikki asiakkaat..."
        ));
    case "luotto":
        siirryKontrolleriin("asiakashallinta", array(
            'success' => "listataan luottolimiitin ylittäneet asiakkaat..."
        ));
}

naytaNakyma("asiakashallinta", 4, $_SESSION['data']);