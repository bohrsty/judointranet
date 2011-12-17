<?php


/*
 * vereine und ansprechpartner administieren
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


/*
 * objekt erzeugen und inhalt anhaengen
 */
$main = new NBmain_verein();


$main->inhalt('<h3>Vereine</h3>');

$main->inhalt($main->read_vereine());

$main->getids('vansp','ansp',array('vansp','v_ansp','<p>Ansprechpartner anlegen</p>'));

$main->to_html();

?>