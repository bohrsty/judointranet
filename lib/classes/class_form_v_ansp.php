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

class NBform_v_ansp extends NBform {
	
	/*
	 * klassenvariablen
	 */
	
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct($pfad,$action,$konfig) {
		
		// elter instanzieren
		parent::__construct($pfad,$action);
		$this->read_daten($konfig);
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	
	
	
	
	
	/*
	 * methoden
	 */
	/**
	 * trennt den datenstring aus der konfig und liest ihn als objekte in die klassenvariable daten ein
	 * 
	 */
	protected function read_daten($konfig) {
		
		// datenstring trennen
		$daten = $this->get_from_gc($konfig,'admin');
		$explode = explode('|',$daten);
		
		// daten durchlaufen
		$name = '';
		$typ = '';
		$erforderlich = null;
		$daten = array();
		for($i=0;$i<count($explode);$i++) {
			
			// erforderlich trennen
			list($feld,$erforderlich) = explode(';',$explode[$i],2);
			// felder trennen
			list($name,$name2) = explode('.',$feld,3);
			// datentyp?
			$datentyp ='';
			if(strpos($name2,':') !== false) {
				
				list($typ,$datentyp) = explode(':',$name2,2);
			} else {
				
				$typ = $name2;
			}
			
			// objekte je nach typ erzeugen
			switch($typ) {
				
				case 'NBtextarea':
					$daten[] = new NBtextarea($name,'class="textarea"',$erforderlich);
				break;
				
				case 'NBinput':
					$daten[] = new NBinput($name,'text','class="input"',$erforderlich,$datentyp);
				break;
				
				case 'NBselect':
					$daten[] = new NBselect($name,'class="select"',1,$this->read_optionen(),$erforderlich);
				break;
			}
		}
		
		// klassenvariable setzen
		$this->set_daten($daten);
	}
	
	
	
	
	
	
	
		/**
	 * liest die zugehoerigen werte aus der datenbank ein und gibt sie als array zurueck
	 * 
	 * @return array array mit den werten fuer die select-auswahlfelder (schluessel=id,wert= beschreibung)
	 */
	private function read_optionen() {
				
		// datenbankobjekt holen
		$db = new NBdb();
		
		// query vorbereiten
		$sql = '	SELECT id,bezeichnung
					FROM verein';
					
		// datenbankabfrage ausfuehren
		$ergebnis = $db->query($sql);
		
		// resultat durchlaufen und speichern
		$optionen = array(0 => '---ausw&auml;hlen---');
		while($zeile = $ergebnis->fetch_array(MYSQL_ASSOC)) {
			
//			$optionen[$zeile['id']] = utf8_encode($zeile['bezeichnung']);
			$optionen[$zeile['id']] = $zeile['bezeichnung'];
		}
		
		// datenbankobjekt schliessen
		$db->close();
		
		// rueckgabe
		return $optionen;
	}
	
	
	
	
	
	
	/**
	 * instance_of den namen der klasse zurueck
	 * 
	 * @return string name der klasse
	 */
	protected function instance_of() {
		return 'NBform_v_ansp';
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
	 * werte_in_db schreibt die ueberprueften werte in die datenbank
	 * 
	 * @param string $sql sql-statement zum eintragen der daten
	 */
	public function werte_in_db($tabelle,$konfig) {

		// konfig trennen
		$explode = explode('|',$this->get_from_gc($konfig,'admin'));
	
		$sql_into = 'INSERT INTO '.$tabelle.' (id, ';
		$sql_values = ' VALUES (NULL, ';
		for($i=0;$i<count($explode);$i++) {
					
			// erforderlich trennen
			list($feld,$erforderlich) = explode(';',$explode[$i],2);
			// felder trennen
			list($name,$name2) = explode('.',$feld,3);
			
			// sql zusammenbauen
			$sql_into .= $name;
			if($i != count($explode)-1) { 
				$sql_into .= ', ';
			}
//			$sql_values .= '"'.utf8_decode($this->get_daten_by_name($name)->get_wert()).'"';
			$sql_values .= '"'.$this->get_daten_by_name($name)->get_wert().'"';
			if($i != count($explode)-1) { 
				$sql_values .= ', ';
			}
		}
		$sql_into .= ')';
		$sql_values .= ')';
			
		$sql = $sql_into.$sql_values;
		
		// datenbankobjekt holen
		$db = new NBdb();
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
	
		// verbindung beenden
		$db->close();
	}
	
	
	
	
	
	
	
	/**
	 * read_werte liest die werte aus der datenbank und traegt ihn in die daten ein
	 * 
	 * @param int $anspid id des ansprechpartners
	 */
	public function read_werte($anspid) {
		
		// datenbank-objekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = '	SELECT name,bezeichnung,verein
					FROM v_ansp
					WHERE id='.$anspid;
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		// daten zwischenspeichern
		list($name,$bezeichnung,$verein) = $resultat->fetch_array(MYSQL_NUM);
				
		// datenbank-objekt schliessen
		$db->close();
				
		// daten setzen
		$this->get_daten_by_name('verein')->set_wert($verein);
		$this->get_daten_by_name('bezeichnung')->set_wert($bezeichnung);
		$this->get_daten_by_name('name')->set_wert($name);
		$this->set_checked(false);
	}
	
	
}



?>
