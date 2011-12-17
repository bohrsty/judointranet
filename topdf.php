<?php

/*
 * erzeugt das pdf der ausschreibung
 */
/*
 * lib einlesen
 */
require('lib/functions.php');

/*
 * konfig einlesen
 */
init();

/*
 * session starten
 */
session_start();


// pdml-felder setzen
$PDML_AutoStart = 0;
$PDML_FileName = $_SESSION['pdml']['dateiname'];

require_once('lib/pdml/pdml.php');

print ob_pdml($_SESSION['pdml']['pdml']);

unset($_SESSION['pdml']);


?>
