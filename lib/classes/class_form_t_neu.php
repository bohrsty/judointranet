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

class NBform_t_neu extends NBform {
	
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
	 * trennt den datenstring aus der konfig und liest ihn als objekte in die klassenvariable daten ein
	 * 
	 */
	protected function read_daten() {
		
		// datenstring trennen
		$daten = $this->get_from_gc('t_neu_daten','termin');
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
			list($name1,$name2,$name3) = explode('.',$feld,3);
			$name = "$name1.$name2";
			// datentyp?
			$datentyp ='';
			if(strpos($name3,':') !== false) {
				
				list($typ,$datentyp) = explode(':',$name3,2);
			} else {
				
				$typ = $name3;
			}
			
			// objekte je nach typ erzeugen
			switch($typ) {
				
				case 'NBselect':
					$daten[] = new NBselect($name,'class="select"',1,$this->read_optionen($name),$erforderlich);
				break;
				
				case 'NBinput':
					$daten[] = new NBinput($name,'text','class="input"',$erforderlich,$datentyp);
				break;
			}
		}
		
		// klassenvariable setzen
		$this->set_daten($daten);
	}
	
	
	
	
	
	
	
	/**
	 * liest die zugehoerigen werte aus der datenbank ein und gibt sie als array zurueck
	 * 
	 * @param string $name name des auswahlfeldes
	 * @return array array mit den werten fuer die select-auswahlfelder (schluessel=id,wert= beschreibung)
	 */
	private function read_optionen($name) {
		
		// datenbankobjekt holen
		$db = new NBdb();
		
		// query vorbereiten
		$sql = '	SELECT id,bezeichnung
					FROM ' . str_replace('.','_',$name);
				
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
		return 'NBform_t_neu';
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
			
			// _ ersetzen
			$name = str_replace('_','.',$name);
			
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
	 * @return int id des eingefuegten termins
	 */
	public function werte_in_db() {

		// daten fuer datenbank vorbereiten
		$termin_datum = date('Y-m-d',strtotime($this->get_daten_by_name('t.datum')->get_wert()));
		
		// datenbankobjekt holen
		$db = new NBdb();
		
		//	sql fuer termin
		$sql = '	INSERT INTO termin
						(id,
						datum,
						name,
						kurzname,
						kategorie,
						ausschreibungs_id)
					VALUES
						(NULL,"'
						.$termin_datum.'","'
//						.utf8_decode($this->get_daten_by_name('t.name')->get_wert()).'","'
//						.utf8_decode($this->get_daten_by_name('t.kurzname')->get_wert()).'","'
						.$this->get_daten_by_name('t.name')->get_wert().'","'
						.$this->get_daten_by_name('t.kurzname')->get_wert().'","'
						.$this->get_daten_by_name('t.kategorie')->get_wert().'",
						"")';
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		$tid = $db->read_insertid();
		
		// verbindung beenden
		$db->close();
		
		// rueckgabe der eingefuegten tid
		return $tid;
	}
	
	
	
	
	
	
	
	/**
	 * read_werte liest die werte aus der datenbank und traegt ihn in die daten ein
	 * 
	 * @param int $tid id des termins
	 */
	public function read_werte($tid) {
				
		$sql = 'SELECT *';
		$sql .= ' FROM termin';
		$sql .= ' WHERE id='.$tid;	
		
		// datenbank-objekt holen
		$db = new NBdb();
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		// daten zwischenspeichern
		$werte = $resultat->fetch_array(MYSQL_ASSOC);
				
		// datenbank-objekt schliessen
		$db->close();
				
		// daten setzen
//		$this->get_daten_by_name('t.datum')->set_wert(utf8_encode(date('d.m.Y',strtotime($werte['datum']))));
//		$this->get_daten_by_name('t.name')->set_wert(utf8_encode($werte['name']));
//		$this->get_daten_by_name('t.kurzname')->set_wert(utf8_encode($werte['kurzname']));
//		$this->get_daten_by_name('t.kategorie')->set_wert(utf8_encode($werte['kategorie']));
		$this->get_daten_by_name('t.datum')->set_wert(date('d.m.Y',strtotime($werte['datum'])));
		$this->get_daten_by_name('t.name')->set_wert($werte['name']);
		$this->get_daten_by_name('t.kurzname')->set_wert($werte['kurzname']);
		$this->get_daten_by_name('t.kategorie')->set_wert($werte['kategorie']);
		
		$this->set_checked(false);
		
		// rueckgabe der ausschreibungs-id
		return $werte['ausschreibungs_id'];
	}
	
	
	
	
	
	
	/**
	 * update_werte_in_db updated die ueberprueften werte in die datenbank
	 * 
	 * @param int $tid id des termins
	 */
	public function update_werte_in_db($tabelle,$konfig,$tid) {

		// konfig trennen
		$explode = explode('|',$this->get_from_gc($konfig,'termin'));
	
		$sql = 'UPDATE '.$tabelle.' SET ';
		
		for($i=0;$i<count($explode);$i++) {
					
			// erforderlich trennen
			list($feld,$erforderlich) = explode(';',$explode[$i],2);
			// felder trennen
			list($name1,$name2,$name3) = explode('.',$feld,3);
			$name = "$name1.$name2";
			
			// sql zusammenbauen
			// datum formatieren
			if($name2 == 'datum') {
//				$sql .= $name2.'="'.utf8_decode(date('Y-m-d',strtotime($this->get_daten_by_name($name)->get_wert()))).'"';
				$sql .= $name2.'="'.date('Y-m-d',strtotime($this->get_daten_by_name($name)->get_wert())).'"';
			} else {
//				$sql .= $name2.'="'.utf8_decode($this->get_daten_by_name($name)->get_wert()).'"';
				$sql .= $name2.'="'.$this->get_daten_by_name($name)->get_wert().'"';
			}
			if($i != count($explode)-1) { 
				$sql .= ', ';
			}
		}
		$sql .= ' WHERE id='.$tid;
				
		// datenbankobjekt holen
		$db = new NBdb();
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
	
		// verbindung beenden
		$db->close();
	}
	
	
}



?>
