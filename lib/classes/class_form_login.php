<?php

// sicherung abfragen
if(!defined('SICHERUNG')) {
	$meldung = 'Diese Datei darf nicht direkt aufgerufen werden! ['.basename($_SERVER['PHP_SELF']).']';
	$meldung .= '<br /><a href="../../index.php">Startseite</a>';
	die($meldung);
}


/**
 * klasse die das formular darstellt und checkt
 */

class NBform_login extends NBform {
	
	/*
	 * klassenvariablen
	 */
	
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct($pfad,$action) {
		
		// elter instanzieren
		parent::__construct($pfad,$action);
		$this->read_daten();
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	
	
	
	
	
	/*
	 * methoden
	 */
	/**
	 * erzeugt die formularfelder
	 */
	protected function read_daten() {
		
		
		$daten[] = new NBinput('benutzername','text','class="input"',true,'userpass');
		$daten[] = new NBinput('passwort','password','class="input"',true,'userpass');
		
		// klassenvariable setzen
		$this->set_daten($daten);
	}
	
	
	
	
	
	
	/**
	 * instance_of den namen der klasse zurueck
	 * 
	 * @return string name der klasse
	 */
	protected function instance_of() {
		return 'NBform_login';
	}
	
	
	
	
	
	
	/**
	 * read_post liest die werte aus $_POST ein und gibt sie zur pruefung an das zugehoerige
	 * objekt weiter und gibt dann das ergebnis aus
	 * 
	 * @return string ergebnis des formulars als html
	 */
	public function read_post() {
		
		// checked zuruecksetzen
		$this->set_checked(true);
		
		// post durchlaufen
		foreach($_POST as $name => $wert) {
						
			// pruefen, ob submit
			if($name != $this->get_from_gc('submit_login_name','intern')) {
				
				// daten holen und pruefen
				$daten = $this->get_daten_by_name($name);
				// pruefen, ob gefunden
				if($daten !== false) {
					$daten->check($wert);
					$this->set_checked(false);
				}
				unset($daten);
			}
		}
		
		// ausgabe
		return $this->to_html();
	}






	/**
	 * read_template liest das template aus der datei ein und speichert
	 * es als string in der klassenvariable
	 */
	protected function read_template($pfad,$action) {
		
		// aus datei einlesen
		$fh = fopen($pfad,'r');
		
		$tpl = fread($fh,filesize($pfad));
		
		fclose($fh);
		
		// in wrapper einbetten
		// array vorbereiten
		$werte = array(	'###method###' => 'POST',
						'###action###' => $action,
						'###submit_login_name###' => $this->get_from_gc('submit_login_name','intern'),
						'###submit_login_wert###' => $this->get_from_gc('submit_login_wert','intern')
						);
		foreach($werte as $platzhalter => $wert) {
			// werte ersetzen
			$tpl = str_replace($platzhalter,$wert,$tpl);
		}
		
		// template setzen
		$this->set_template($tpl);
	}
	
	
	
	
	
	
	/**
	 * to_html gibt das objekt als html-repraesentation ins template eingebettet zurueck
	 * 
	 * @param string $meldung im loginform aus zu gebende meldung
	 * @return string html-code des objekts
	 */
	public function to_html() {
		
		// template und daten holen
		$template = $this->get_template();
		$daten = $this->get_daten();
		
		// marker bearbeiten
		$template = $this->marker($template);
		
		// daten durchlaufen
		for($i=0;$i<count($daten);$i++) {
			
			// pruefen welche klasse
			$typ = $daten[$i]->instance_of();
			
			// mit formularelemente
			$template = str_replace('###'.$daten[$i]->get_name().'.'.$typ.'###', $daten[$i]->to_html(), $template);
		}
		
		// rueckgabe
		return $template;
	}
	
	
	
	
	
	
	/**
	 * marker setzt entfernt die markierungen oder deren inhalt, wenn checked === true
	 * 
	 * @param string $template das template in dem die markierungen/inhalt entfernt werden
	 */
	protected function marker($template,$deakt=false) {
		
		// pruefen, ob checked === true
		if(isset($_POST[$this->get_from_gc('submit_login_name','intern')]) && $this->get_checked() === true) {
			
			// markierungen und inhalt entfernen
			// erforderlich
			if(strpos($template,'###marker') !== false) {
				preg_match('/###marker_erforderlich_start###(.*)###marker_erforderlich_ende###/s',$template,$preg_match);
				$template = str_replace($preg_match[0],'',$template);
				// buttons
				preg_match('/###marker_buttons_start###(.*)###marker_buttons_ende###/s',$template,$preg_match);
				$template = str_replace($preg_match[0],'',$template);
			}
		} else {
			
			// markierungen und inhalt entfernen
			// erforderlich
			if(strpos($template,'###marker') !== false) {
				preg_match('/###marker_erforderlich_start###(.*)###marker_erforderlich_ende###/s',$template,$preg_match);
				$template = str_replace($preg_match[0],$preg_match[1],$template);
				// buttons
				preg_match('/###marker_buttons_start###(.*)###marker_buttons_ende###/s',$template,$preg_match);
				$template = str_replace($preg_match[0],$preg_match[1],$template);
			}
		}
		
		// rueckgabe
		return $template;
	}
	
	
}



?>
