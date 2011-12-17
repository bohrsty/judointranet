<?php
// sicherung abfragen
if(!defined('SICHERUNG')) {
	$meldung = 'Diese Datei darf nicht direkt aufgerufen werden! ['.basename($_SERVER['PHP_SELF']).']';
	$meldung .= '<br /><a href="../../index.php">Startseite</a>';
	die($meldung);
}



/**
 * klasse repraesentiert die konfiguration aus der db oder der ini-datei
 */

class NBkonfig {
	
	/*
	 * klassenvariablen
	 */
	private $konfig;
	
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct($inidatei) {
		
		// klassenvariablen initialisieren
		$this->read_konfig($inidatei);
		
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	private function get_konfig() {
		return $this->konfig;
	}
	private function set_konfig($konfig) {
		$this->konfig = $konfig;
	}
	
	
	
	/*
	 * methoden
	 */
	/**
	 * read_konfig liest die einstellungen aus datenbank und ini-datei ein
	 */
	private function read_konfig($inidatei) {
		
		// einstellungen aus ini-datei einlesen
		$ini = parse_ini_file($inidatei,true);
				
		// ini-einstellungen an konfig anhaengen
		foreach($ini as $bereich => $einst) {
			foreach($einst as $name => $wert) {
				$this->add_to_konfig($bereich,$name,$wert);
			}
		}
		
		// einstellungen aus der datenbank lesen
		// datenbankobjekt erstellen

		$db = new mysqli($this->return_konfig('db','host'), $this->return_konfig('db','username'), $this->return_konfig('db','password'), $this->return_konfig('db','database'));
		$db->set_charset('utf8');
		
		// abfrage vorbereiten
		$sql = 'SELECT name,bereich,wert
				FROM konfig';
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		// datenbankobjekt schliessen
		$db->close();
		
		// resultat verarbeiten
		while(list($name,$bereich,$wert) = $resultat->fetch_array(MYSQL_NUM)) {
			
			// einstellung anhaengen
			$this->add_to_konfig($bereich,$name,$wert);
		}
	}
	
	
	
	
	
	
	
	
	/**
	 * add_to_konfig haengt eine einstellung an die konfig an
	 * 
	 * @param string $bereich kategorie der einstellung
	 * @param string $name name der einstellung
	 * @param string $wert wert der einstellung
	 */
	public function add_to_konfig($bereich,$name,$wert) {
		
		// konfig holen
		$konfig = $this->get_konfig();
		
		// einstellung anhaengen
		$konfig[$bereich][$name] = $wert;
		
		// konfig zurueckschreiben
		$this->set_konfig($konfig);
	}
	
	
	
	
	
	
	
	
	/**
	 * return_konfig gibt den wert der einstellung laut $bereich und $name zurueck
	 * 
	 * @param string $bereich kategorie der einstellung
	 * @param string $name name der einstellung
	 * @return string wert der angefragten einstellung
	 */
	public function return_konfig($bereich,$name) {
		
		// konfig holen
		$konfig = $this->get_konfig();
		
		// wert der einstellung zurueckgeben
		return $konfig[$bereich][$name];
	}

}

?>
