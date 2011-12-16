<?php


/*
 * administration 
 */
/*
 * lib einlesen
 */
require('lib/functions.php');

/*
 * konfig einlesen und diverse einstellungen festlegen
 */
$GC = init();

/*
 * session starten und konfig in session speichern
 */
session_start();
$_SESSION['GC'] = $GC;


/*
 * objekt erzeugen und inhalt anhaengen
 */
$main = new NBmain_admin();


$main->inhalt('<h3>Administration</h3>');
$main->inhalt('<p>zur Administration diverser Felder</p>');

$main->getids('gklasse','admin_aktionen',array('gklasse','a_gewichtsklassen','<p>Gewichtsklasse anlegen</p>'));
$main->getids('hallen','admin_aktionen',array('hallen','a_hallen','<p>Halle anlegen</p>'));
$main->getids('hinweis','admin_aktionen',array('hinweis','a_hinweis','<p>Hinweis anlegen</p>'));
$main->getids('mgeld','admin_aktionen',array('mgeld','a_meldegeld','<p>Meldung anlegen</p>'));
$main->getids('meldung','admin_aktionen',array('meldung','a_meldung','<p>Meldungstext anlegen</p>'));
$main->getids('modus','admin_aktionen',array('modus','a_modus','<p>Modus anlegen</p>'));
$main->getids('stberechtigt','admin_aktionen',array('stberechtigt','a_startberechtigt','<p>Startberechtigte anlegen</p>'));
$main->getids('veranst','admin_aktionen',array('veranst','a_veranstalter','<p>Veranstalter anlegen</p>'));
$main->getids('wiegen','admin_aktionen',array('wiegen','a_wiegen','<p>Wiegen anlegen</p>'));
$main->getids('kategorie','admin_aktionen',array('kategorie','t_kategorie','<p>Kategorie anlegen</p>'));

$main->to_html();

?>