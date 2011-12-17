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

class NBform extends NBobject {
	
	/*
	 * klassenvariablen
	 */
	private $template;
	private $daten;
	private $checked;
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct($pfad,$action) {
		
		// elter instanzieren
		parent::__construct();
		
		// klassenvariablen initialisieren
		$this->read_template($pfad,$action);
		$this->set_checked(true);
		
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	protected function get_template() {
		return $this->template;
	}
	protected function set_template($template){
		$this->template = $template;
	}
	
	public function get_daten() {
		return $this->daten;
	}
	protected function set_daten($daten){
		$this->daten = $daten;
	}
	
	public function get_checked() {
		return $this->checked;
	}
	protected function set_checked($checked){
		$this->checked = $checked;
	}
	
	
	
	
	/*
	 * methoden
	 */
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
						'###submit_name###' => $this->get_from_gc('submit_name','intern'),
						'###submit_wert###' => $this->get_from_gc('submit_wert','intern'),
						'###reset_name###' => 'reset',
						'###reset_value###' => 'zur&uuml;cksetzen'
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
			
			// pruefen, ob erfolgereich gechecked
			if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $this->get_checked() === true) {
				
				// nur werte, ohne formularelemente
				$template = str_replace('###'.$daten[$i]->get_name().'.'.$typ.'###', $daten[$i]->wert_to_html(), $template);
			} else {
				
				// mit formularelemente
				$template = str_replace('###'.$daten[$i]->get_name().'.'.$typ.'###', $daten[$i]->to_html(), $template);
			}
		}
		
		// rueckgabe
		return $template;
	}
	
	
	
	
	
	
	/**
	 * instance_of den namen der klasse zurueck
	 * 
	 * @return string name der klasse
	 */
	protected function instance_of() {
		return 'NBform';
	}
	
	
	
	
	
	
	/**
	 * get_daten_by_name gibt das daten-objekt nach namen zurueck
	 * 
	 * @param string $name name des zurueckzugebenden objekts
	 * @return mixed gefundenes daten-objekt, oder false, wenn nicht gefunden
	 */
	public function get_daten_by_name($name) {
		
		// daten durchlaufen
		$daten = $this->get_daten();
		
		for($i=0;$i<count($daten);$i++) {
			
			// pruefen, ob aktueller name = gesuchter, dann rueckgabe
			if($daten[$i]->get_name() == $name) {
				return $daten[$i];
			}
		}
		
		// nicht gefunden, false zurueckgeben
		return false;
	}
	
	
	
	
	
	
	/**
	 * read_post liest die werte aus $_POST ein und gibt sie zur pruefung an das zugehoerige
	 * objekt weiter und gibt dann das ergebnis aus
	 * 
	 * @return string ergebnis des formulars als html
	 */
	public function read_post() {
		
		// checked zuruechsetzen
		$this->set_checked(true);
		
		// post durchlaufen
		foreach($_POST as $name => $wert) {
			
			// pruefen, ob submit
			if($name != $this->get_from_gc('submit_name','intern')) {
				
				// daten holen und pruefen
				$daten = $this->get_daten_by_name($name);
				// pruefen, ob gefunden
				if($daten !== false) {
					$this->put_checked($daten->check($wert));
				}
				unset($daten);
			}
		}
		
		// ausgabe
		return $this->to_html();
	}
	
	
	
	
	
	
	/**
	 * put_checked setzt die klassenvariable je nach parameterwert und aktuellem wert
	 * 
	 * @param bool $checked rueckgabewert der check-funktionen
	 */
	protected function put_checked($checked) {
		
		// parameter pruefen, wenn fehler (false)
		if(!$checked) {
			
			// klassenvariable false setzen
			$this->set_checked(false);
		}
	}
	
	
	
	
	
	
	/**
	 * marker setzt entfernt die markierungen oder deren inhalt, wenn checked === true
	 * 
	 * @param string $template das template in dem die markierungen/inhalt entfernt werden
	 * @param bool $deakt deaktiviert das ersetzen der formularfelder
	 */
	protected function marker($template,$deakt=false) {
		
		// pruefen, ob checked === true
		if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $this->get_checked() === true && $deakt === false) {
			
			// markierungen und inhalt entfernen
			// erforderlich
			preg_match('/###marker_erforderlich_start###(.*)###marker_erforderlich_ende###/s',$template,$preg_match);
			$template = str_replace($preg_match[0],'',$template);
			// buttons
			preg_match('/###marker_buttons_start###(.*)###marker_buttons_ende###/s',$template,$preg_match);
			$template = str_replace($preg_match[0],'',$template);
		} else {
			
			// markierungen und inhalt entfernen
			// erforderlich
			preg_match('/###marker_erforderlich_start###(.*)###marker_erforderlich_ende###/s',$template,$preg_match);
			$template = str_replace($preg_match[0],$preg_match[1],$template);
			// buttons
			preg_match('/###marker_buttons_start###(.*)###marker_buttons_ende###/s',$template,$preg_match);
			$template = str_replace($preg_match[0],$preg_match[1],$template);
		}
		
		// rueckgabe
		return $template;
	}
	
	
	
	
	
	
	/**
	 * update_werte_in_db updated die ueberprueften werte in die datenbank
	 * 
	 * @param string $sql sql-statement zum eintragen der daten
	 */
	public function update_werte_in_db($tabelle,$konfig,$aid) {

		// konfig trennen
		$explode = explode('|',$this->get_from_gc($konfig,'admin'));
	
		$sql = 'UPDATE '.$tabelle.' SET ';
		
		for($i=0;$i<count($explode);$i++) {
					
			// erforderlich trennen
			list($feld,$erforderlich) = explode(';',$explode[$i],2);
			// felder trennen
			list($name,$name2) = explode('.',$feld,3);
			
			// sql zusammenbauen
//			$sql .= $name.'="'.utf8_decode($this->get_daten_by_name($name)->get_wert()).'"';
			$sql .= $name.'="'.$this->get_daten_by_name($name)->get_wert().'"';
			if($i != count($explode)-1) { 
				$sql .= ', ';
			}
		}
		$sql .= 'WHERE id='.$aid;
				
		// datenbankobjekt holen
		$db = new NBdb();
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
	
		// verbindung beenden
		$db->close();
	}
	
	
}



?>
