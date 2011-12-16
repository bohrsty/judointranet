<?php

// sicherung abfragen
if(!defined('SICHERUNG')) {
	$meldung = 'Diese Datei darf nicht direkt aufgerufen werden! ['.basename($_SERVER['PHP_SELF']).']';
	$meldung .= '<br /><a href="../../index.php">Startseite</a>';
	die($meldung);
}


/**
 * klasse repraesentiert ein mysqli-datenbankobjekt 
 */

class NBdb extends NBobject {
	
	/*
	 * klassenvariablen
	 */
	private $db;
	
	
	/*
	 * konstruktor der klasse
	 */
	function __construct() {
		
		// klassenvariablen initialisieren
		$db = new mysqli($this->get_from_gc('host','db'), $this->get_from_gc('username','db'), $this->get_from_gc('password','db'), $this->get_from_gc('database','db'));
		$this->set_db($db);
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	private function get_db() {
		return $this->db;
	}
	private function set_db($db) {
		$this->db = $db;
	}
	
	
	
	
	/*
	 * methoden
	 */
		/**
	 * instance_of den namen der klasse zurueck
	 * 
	 * @return string name der klasse
	 */
	protected function instance_of() {
		return 'NBdb';
	}
	
	
	
	
	
	
	
	/**
	 * query fuehrt die abfrage auf der datenbank aus
	 * 
	 * @param string $sql sql-abfrage die auf der datenbankverbindung ausgefuehrt werden soll
	 * @return object mysqli-resultat-objekt mit dem ergebnis
	 */
	public function query($sql) {
		
		// abfrage ausfuehren
		$resultat = $this->get_db()->query($sql);
		
		// rueckgabe
		return $resultat;
	}
	
	
	
	
	
	
	/**
	 * close schliesst die datenbankverbindung wieder
	 */
	public function close() {
		
		// datenbankverbindung schliessen
		$this->get_db()->close();
	}
	
	
	
	
	
	
	/**
	 * read_insertid gibt den wert von insert_id zurueck
	 */
	public function read_insertid() {
		
		// wert von insert_id zurueckgeben
		return $this->get_db()->insert_id;
	}
}



?>
