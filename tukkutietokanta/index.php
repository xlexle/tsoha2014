<?php

require_once "libs/common.php";

if (!kirjautunut()) {
    siirryKontrolleriin("kirjautuminen");
} else {
    siirryKontrolleriin("tuotevalikoima");
}