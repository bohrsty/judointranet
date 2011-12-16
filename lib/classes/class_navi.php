<?php

// sicherung abfragen
if(!defined('SICHERUNG')) {
	$meldung = 'Diese Datei darf nicht direkt aufgerufen werden! ['.basename($_SERVER['PHP_SELF']).']';
	$meldung .= '<br /><a href="../../index.php">Startseite</a>';
	die($meldung);
}


/**
 * klasse repraesentiert die seitennavigation 
 */

class NBnavi extends NBobject {
	
	/*
	 * klassenvariablen
	 */
	private $datei;
	private $get_id;
	private $seite;
	private $elter;
	private $gruppen;
	private $name;
	private $reihenfolge;
	
	
	/*
	 * konstruktor der klasse
	 */
	function __construct() {
		
		// klassenvariablen initialisieren
		$this->set_seite(null);
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	protected function get_datei($id='') {
		if($id == '') {
			return $this->datei;
		}
		if(isset($this->datei[$id])) {
			return $this->datei[$id];
		} else {
			return null;
		}
	}
	protected function set_datei($datei){
		$this->datei = $datei;
	}
	
	protected function get_get_id($id='') {
		if($id == '') {
			return $this->get_id;
		}
		if(isset($this->get_id[$id])) {
			return $this->get_id[$id];
		} else {
			return null;
		}
	}
	protected function set_get_id($get_id){
		$this->get_id = $get_id;
	}
	
	protected function get_seite() {
		return $this->seite;
	}
	public function set_seite($seite){
		$this->seite = $seite;
		
		// navigation neu laden
		$this->read_werte();
	}
	
	protected function get_elter($id='') {
		if($id == '') {
			return $this->elter;
		}
		if(isset($this->elter[$id])) {
			return $this->elter[$id];
		} else {
			return null;
		}
	}
	protected function set_elter($elter){
		$this->elter = $elter;
	}
	
	protected function get_gruppen($id='') {
		if($id == '') {
			return $this->gruppen;
		}
		if(isset($this->gruppen[$id])) {
			return $this->gruppen[$id];
		} else {
			return null;
		}
	}
	protected function set_gruppen($gruppen){
		$this->gruppen = $gruppen;
	}
	
	protected function get_name($id='') {
		if($id == '') {
			return $this->name;
		}
		if(isset($this->name[$id])) {
			return $this->name[$id];
		} else {
			return null;
		}
	}
	protected function set_name($name){
		$this->name = $name;
	}
	
	protected function get_reihenfolge($id='') {
		if($id == '') {
			return $this->reihenfolge;
		}
		if(isset($this->reihenfolge[$id])) {
			return $this->reihenfolge[$id];
		} else {
			return null;
		}
	}
	protected function set_reihenfolge($reihenfolge){
		$this->reihenfolge = $reihenfolge;
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
		return 'NBnavi';
	}
	
	
	
	
	
	
	
	/**
	 * read_werte liest die daten der navigation aus der datenbank
	 * 
	 * @param string $get_id aktueller wert von $_GET['id']
	 */
	private function read_werte() {
		
		// datenbankobjekt holen
		$db = new NBdb();

		// query vorbereiten
		$sql = '	SELECT *
					FROM navi';
		// query ausfuehren
		$resultat = $db->query($sql);
  
		// datenbankobjekt schliessen
		$db->close();
		
		// eintraege vorbereiten
		$array_datei = array();
		$array_get_id = array();
		$array_elter = array();
		$array_gruppen = array();
		$array_name = array();
		$array_reihenfolge = array();
		
		// resultat durchlaufen
		// pruefen, ob benutzer eingeloggt und in gruppen
		if(!isset($_SESSION['benutzer']) || !$_SESSION['benutzer']->check_login()) {
			
			// eintraege setzen
			$array_datei[1] = 'index.php';
			$array_get_id[1] = '';
			$array_elter[1] = 0;
			$array_gruppen[1] = 1;
			$array_name[1] = 'Login';
			$array_reihenfolge[1] = 1;
		} else {
			
			while(list($id,$datei,$get_id,$elter,$gruppen,$name,$reihenfolge) = $resultat->fetch_array(MYSQL_NUM)) {

				if($elter != -1) {
					// wenn eingeloggter user in $gruppen, verarbeiten
					$akt_gruppen = array();
					if(strpos($gruppen,',') === false) {
						$akt_gruppen = array($gruppen);
					} else {
						$akt_gruppen = explode(',',$gruppen);
					}
					
					if(isset($_SESSION['benutzer']) && $_SESSION['benutzer']->in_gruppe($akt_gruppen)) {
						
						// eintraege setzen
						$array_datei[$id] = $datei;
						$array_get_id[$id] = $get_id;
						$array_elter[$id] = $elter;
						$array_gruppen[$id] = $gruppen;
						$array_name[$id] = $name;
						$array_reihenfolge[$id] = $reihenfolge;
					}
				}
			}
		}
		
		// eintraege in klassenvariablen schreiben
		$this->set_datei($array_datei);
		$this->set_get_id($array_get_id);
		$this->set_elter($array_elter);
		$this->set_gruppen($array_gruppen);
		$this->set_name($array_name);
		$this->set_reihenfolge($array_reihenfolge);
	}
	
	
	
	
	
	
	
	/**
	 * erzeuge_baum generiert den baum der navi mit den abhaengigkeiten
	 * und gibt ihn zurueck
	 * 
	 * @return $array array mit den abhaengigkeiten der navi
	 */
	private function erzeuge_baum() {
		
		// eltern und reihenfolge zwischenspeichern
		$eltern = $this->get_elter();
		$reihenfolge = $this->get_reihenfolge();
		asort($eltern);
		
		// rueckgabe vorbereiten
		$baum = array();
		
		// eltern durchlaufen
		$zaehler = 0;
		$temp = array();
		foreach($eltern as $id => $elter) {
			
			// auf ebene 0 testen
			if($elter == 0) {
				
				$temp[$reihenfolge[$id]-1] = array($id,$elter);
			} else {
				
				$temp[$zaehler] = array($id,$elter);
			}
			
			// zaehler erhoehen
			$zaehler++;
		}
		
		for($i=0;$i<count($temp);$i++) {
					
			// array auflisten
			list($id,$elter) = $temp[$i];
			
			// auf ebene 0 testen
			if($elter == 0) {
				
				$baum[$id] = array();
			} else {
				
				$baum[$elter][] = $id;
			}
		}
		
		// rueckgabe
		return $baum;
	}
	
	
	
	
	
	
	
	
	/**
	 * to_html gibt die navigation als html zurueck
	 * 
	 * @return string navigation als html
	 */
	public function to_html() {
		
		//rueckgabe vorbereiten
		$html = '';
		
		// baum auslesen
		$baum = $this->erzeuge_baum();
		
		// baum durchlaufen
		foreach($baum as $elter => $subbaum) {
			
			// wrapper 0. ebene
			$args = array($this->get_datei($elter),$this->get_name($elter),$this->get_name($elter));
			$html .= $this->parse_wrapper($this->get_from_gc('navi_0','wrapper'),$args);
			unset($args);
			
			// subbaum durchlaufen
			foreach($subbaum as $id) {
				
				// auf aktiv pruefen
				if(!is_null($this->get_seite()) && basename($_SERVER['SCRIPT_NAME']) == $this->get_datei($id) && $this->get_get_id($id) == $this->get_seite()) {
					
					// aktiv
					$args = array($this->get_datei($id).'?id='.$this->get_get_id($id),$this->get_name($id),$this->get_name($id));
					$html .= $this->parse_wrapper($this->get_from_gc('navi_1_a','wrapper'),$args);
					unset($args);
				} else {
					
					// nicht aktiv
					$args = array($this->get_datei($id).'?id='.$this->get_get_id($id),$this->get_name($id),$this->get_name($id));
					$html .= $this->parse_wrapper($this->get_from_gc('navi_1_i','wrapper'),$args);
				}
			}
		}
		
		// rueckgabe
		return $html;
	}
	
	
	
	
	
	
	
	
}



?>
