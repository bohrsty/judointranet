<?php

/*
 * startseite mit willkommen und navi
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


$main = new NBmain();

$main->inhalt('<div class="align_right"><p class="fsize_07">Eingeloggt als: '.$_SESSION['benutzer']->get_bezeichnung(). ' ['.$_SESSION['benutzer']->get_login().']</p></div>');
$main->inhalt('<p><h2>Herzlich Willkommen</h2></p>');
$main->inhalt('<p>auf den Intranetseiten des Bezirksfachverbandes L&uuml;neburg/Stade</p>');

$main->to_html();



?>