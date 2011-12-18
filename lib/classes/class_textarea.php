<?php


// sicherung abfragen
if(!defined('SICHERUNG')) {
	$meldung = 'Diese Datei darf nicht direkt aufgerufen werden! ['.basename($_SERVER['PHP_SELF']).']';
	$meldung .= '<br /><a href="../../index.php">Startseite</a>';
	die($meldung);
}


/**
 * klasse repraesentiert ein html textarea-tag und die zugehoerigen werte 
 */

class NBtextarea extends NBobject {
	
	/*
	 * klassenvariablen
	 */
	private $name;
	private $css;
	private $wert;
	private $error;
	private $erforderlich;
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct($name,$css,$erforderlich) {
		
		// elter instanzieren
		parent::__construct();
		
		// klassenvariablen initialisieren
		$this->set_name($name);
		$this->set_css($css);
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
		
		// auf fehler pruefen
		if($this->get_error() != '') {
			
			// css anpassen
			$css = $this->get_css();
			
			// pruefen, ob class
			if(substr($css,0,5) == 'class') {
				
				// an = trennen
				list($class,$werte) = explode('=',$css,2);
				
				// aktuelle klasse auslesen
				$klasse = substr($werte,1,-1);
				
				// neues class-tag zusammensetzen
				$css = $class . '="' . $klasse . ' error"';
			}
			$this->set_css($css);
		}
		
		// erforderlich?
		$erforderlich = '';
		if($this->get_erforderlich() === true) {
			$erforderlich = $this->get_from_gc('erforderlich','global');
		}
		
		// argumente in array speichern
		$args = array($erforderlich,$this->get_name(), $this->get_css(), $this->get_wert(),$this->get_error());
		
		// wrapper einbetten
		$input = $this->parse_wrapper($this->get_from_gc('textarea','wrapper'),$args);
		
		// rueckgabe
		return $input;
	}
	
	
	
	
	
	
	/**
	 * instance_of den namen der klasse zurueck
	 * 
	 * @return string name der klasse
	 */
	protected function instance_of() {
		return 'NBtextarea';
	}
	
	
	
	
	
	
	/**
	 * wert_to_html gibt den wert als htmlformatierten string zurueck
	 * 
	 * @return string wert als htmlformatierten string
	 */
	public function wert_to_html() {
		
		// argumente vorbereiten
		$args = array(nl2br($this->get_wert()));
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
		$cleaned_wert = $this->clean_wert($wert,'text_nt');
		// erforderlich?
		if($this->get_erforderlich() === true) {
		
			// erforderlich und ausgefuellt
			if($cleaned_wert === false || $cleaned_wert == '') {
				
				// fehler setzen und false zureckgeben
				$this->set_error($this->read_errormsg($this->get_from_gc('textarea','error')));
				
				// auf magic_quotes pruefen
			    if(get_magic_quotes_gpc() == 0) {
					$this->set_wert(trim(addslashes($wert)));
			    } else {
					$this->set_wert(trim($wert));
			    }
			    
				return false;
			} else {
				
				// wert eintragen und true zurueckgeben
				$this->set_wert($cleaned_wert);
				$this->set_error('');
				return true;
			}
		} elseif($wert == '') {
			
			// nicht erforderlich und leer
			$this->set_wert('');
			$this->set_error('');
			return true;
		} else {
			
			// nicht erforderlich und ausgefuellt
			// auf magic_quotes pruefen
		    if(get_magic_quotes_gpc() == 0) {
				$this->set_wert(trim(addslashes($wert)));
		    } else {
				$this->set_wert(trim($wert));
		    }
		    $this->set_error('');
			return true;
		}
	}
}



?>