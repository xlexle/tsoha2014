<?php

require_once "libs/models/asiakas.php";
require_once "libs/common.php";
kirjautumisTarkistus();
asiakasTarkistus();

$asiakasnro = $_SESSION['kirjautunut'];
$asiakas = Asiakas::etsiAsiakasAsiakasnumerolla($asiakasnro);
$data['asiakas'] = $asiakas;
naytaNakyma("asiakas", 5, $data);