<?php








/**
 * setzt einige standardeinstellungen fuer das laufende skript liest die
 * konfig aus und gibt sie zurueck
 * 
 * @return array konfigurationsreinstellungen als array
 */
function init() {
	
	// sicherung der skripte definieren
	define("SICHERUNG", 'Sicherung');
	
	// konfiguration einlesen
	$GC = parse_ini_file('cnf/config.ini',true);
	// locale fuer datum auf deutsch setzen
	setlocale(LC_ALL, 'de_DE@euro');
	// zeitzone setzen
	date_default_timezone_set($GC['global']['timezone']);
	
	// admin-button-beschriftungen setzen
	$GC['intern']['submit_name'] = 'submit';
	$GC['intern']['submit_wert'] = 'Speichern';
	
	// login-button-beschriftungen setzen
	$GC['intern']['submit_login_name'] = 'submit_login';
	$GC['intern']['submit_login_wert'] = 'Login';
	
	// konfig zurueckgeben
	return $GC;
}






/**
 * laed die klassendefinition der uebergebenen klasse
 */
function __autoload($klasse) {
	
	// klassen laden
	$dateiname = 'class_'.substr($klasse,2).'.php';
	include('lib/classes/'.$dateiname);
}






?>