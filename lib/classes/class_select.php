<?php

// sicherung abfragen
if(!defined('SICHERUNG')) {
	$meldung = 'Diese Datei darf nicht direkt aufgerufen werden! ['.basename($_SERVER['PHP_SELF']).']';
	$meldung .= '<br /><a href="../../index.php">Startseite</a>';
	die($meldung);
}


/**
 * klasse repraesentiert ein html select-tag und die zugehoerigen werte 
 */

class NBselect extends NBobject {
	
	/*
	 * klassenvariablen
	 */
	private $name;
	private $css;
	private $groesse;
	private $optionen;
	private $wert;
	private $error;
	private $erforderlich;
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct($name,$css,$groesse,$optionen,$erforderlich) {
		
		// elter instanzieren
		parent::__construct();
		
		// klassenvariablen initialisieren
		$this->set_name($name);
		$this->set_css($css);
		$this->set_groesse($groesse);
		$this->set_optionen($optionen);
		$this->set_wert(0);
		$this->set_error('');
		// bool
		if($erforderlich == 'false'){
			$this->set_erforderlich(false);
		} else {
			$this->set_erforderlich(true);
		}	
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	public function get_name() {
		return $this->name;
	}
	private function set_name($name) {
		$this->name = $name;
	}
	
	private function get_css() {
		return $this->css;
	}
	private function set_css($css) {
		$this->css = $css;
	}
	
	private function get_groesse() {
		return $this->groesse;
	}
	private function set_groesse($groesse) {
		$this->groesse = $groesse;
	}
	
	private function get_optionen() {
		return $this->optionen;
	}
	private function set_optionen($optionen) {
		$this->optionen = $optionen;
	}
	
	public function get_wert() {
		return $this->wert;
	}
	public function set_wert($wert) {
		$this->wert = $wert;
	}
	
	private function get_error() {
		return $this->error;
	}
	private function set_error($error) {
		$this->error = $error;
	}
	
	private function get_erforderlich() {
		return $this->erforderlich;
	}
	private function set_erforderlich($erforderlich) {
		$this->erforderlich = $erforderlich;
	}
	
	
	
	
	/*
	 * methoden
	 */
	/**
	 * to_html bettet das objekt in html-code ein
	 * 
	 * @return string html-repraesentation des objekts als string
	 */
	public function to_html() {
		
		// rueckgabe vorbereiten
		$optionen = '';
		
		// options-array durchlaufen
		$opt_args = $this->get_optionen();
		foreach($opt_args as $i => $o_werte) {
			
			$args = array();
			// pruefen, ob selected
			if($i == $this->get_wert()) {
				
				// selected einfuegen
				$args = array('selected',$i,$o_werte);
			} else {
				
				// ohne selected
				$args = array('',$i,$o_werte);
			}
			
			// wrapper parsen
			$optionen .= $this->parse_wrapper($this->get_from_gc('select_option','wrapper'),$args);
		}
		unset($args);
				
		// auf fehler pruefen
		if($this->get_error() == '') {
			
			// css anpassen
			$this->set_css('class="input"');
		} else {
			
			// css anpassen
			$this->set_css('class="input error"');
		}
		
		// erforderlich
		$erforderlich = '';
		if($this->get_erforderlich() === true) {
			$erforderlich = $this->get_from_gc('erforderlich','global');
		}
		
		// in select-wrapper einbetten
		$args = array($erforderlich,$this->get_name(),$this->get_css(),$optionen,$this->get_error());
		$select = $this->parse_wrapper($this->get_from_gc('select','wrapper'),$args);
		
		// rueckgabe
		return $select;
	}
	
	
	
	
	
	
	/**
	 * instance_of den namen der klasse zurueck
	 * 
	 * @return string name der klasse
	 */
	protected function instance_of() {
		return 'NBselect';
	}
	
	
	
	
	
	
	/**
	 * wert_to_html gibt den wert als htmlformatierten string zurueck
	 * 
	 * @return string wert als htmlformatierten string
	 */
	public function wert_to_html() {
		
		// argumente vorbereiten
		$args = array($this->get_from_db($this->get_name(),$this->get_wert()));
		$html = $this->parse_wrapper($this->get_from_gc('fett','wrapper'),$args);
		
		// rueckgabe
		return $html;
	}
	
	
	
	
	
	
	/**
	 * check prueft den uebergebenen wert auf verbotenen zeichen und gibt das ergebnis als bool zurueck
	 * 
	 * @param mixed $wert zu pruefender wert
	 * @return bool true, wenn erfolgreich, sonst false
	 */
	public function check($wert) {
		
		// wert reinigen
		$cleaned_wert = $this->clean_wert($wert,'zahl');
		// erforderlich?
		if($this->get_erforderlich() === true) {
		
			if($cleaned_wert === false || $cleaned_wert == 0) {
				
				// fehler setzen und false zureckgeben
				$this->set_error($this->read_errormsg($this->get_from_gc('select','error')));
				return false;
			} else {
				
				// wert eintragen und true zurueckgeben
				$this->set_wert($cleaned_wert);
				$this->set_error('');
				return true;
			}
		} else {
			
			// wert eintragen und true zurueckgeben
			$this->set_wert($cleaned_wert);
			$this->set_error('');
			return true;
		}
	}
	
	
	
	
	
	
	/**
	 * get_from_db liest den wert als id aus der datenbank aus
	 * 
	 * @param string $name name des elements (entspricht dem tabellennamen)
	 * @param int $wert der zu suchende wert als id
	 * @return string der aus der datenbank gelesene wert als string
	 */
	private function get_from_db($name,$wert) {
		
		// rueckgabe vorbereiten
		$return = '';
		// datenbankobjekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = '';
		if(substr($name,0,-1) == 'a.meldung-an') {
				
			$sql = '	SELECT bezeichnung
						FROM benutzer
						WHERE id = '.$wert;
		} elseif($name == 'v.ansp') {
				
			$sql = '	SELECT a.bezeichnung AS abez, v.bezeichnung AS vbez
						FROM verein AS v,v_ansp AS a
						WHERE a.id = '.$wert.' AND a.verein = v.id';
		} else {
				
			$sql = '	SELECT bezeichnung
						FROM ' . preg_replace('/\./','_',$name).'
						WHERE id = '.$wert;
		}
		
		// datenbankabfrage ausfuehren
		$ergebnis = $db->query($sql);
		
		// resultat durchlaufen und speichern
		while($zeile = $ergebnis->fetch_array(MYSQL_ASSOC)) {
			
			// wenn v.ansp, verein anhaengen
			if($name == 'v.ansp') {
				
				$return = utf8_encode($zeile['abez']).' ('.utf8_encode($zeile['vbez']).')';
			} else {
				
				$return = utf8_encode($zeile['bezeichnung']);
			}
		}
		
		// rueckgabe
		return $return;
	}
	
	
	
	
}



?>