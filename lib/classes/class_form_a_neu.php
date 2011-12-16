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

class NBform_a_neu extends NBform {
	
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
		$daten = $this->get_from_gc('a_neu_daten','termin');
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
		$sql = '';
		if(substr($name,0,-1) == 'a.meldung-an') {
				
			$sql = '	SELECT id,bezeichnung
						FROM benutzer';
		} elseif($name == 'v.ansp') {
				
			$sql = '	SELECT a.id,a.bezeichnung AS abez, v.bezeichnung AS vbez
						FROM verein AS v,v_ansp AS a
						WHERE a.verein = v.id';
		} else {
				
			$sql = '	SELECT id,bezeichnung
						FROM ' . preg_replace('/\./','_',$name);
		}
		
		// datenbankabfrage ausfuehren
		$ergebnis = $db->query($sql);
		
		// resultat durchlaufen und speichern
		$optionen = array(0 => '---ausw&auml;hlen---');
		while($zeile = $ergebnis->fetch_array(MYSQL_ASSOC)) {
			
			// wenn v.ansp, verein anhaengen
			if($name == 'v.ansp') {
				
				$optionen[$zeile['id']] = utf8_encode($zeile['abez']).' ('.utf8_encode($zeile['vbez']).')';
			} else {
				
				$optionen[$zeile['id']] = utf8_encode($zeile['bezeichnung']);
			}
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
		return 'NBform_a_neu';
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
			
			// hinweis auf 1 setzen, wenn 0
			if($name == 'a.hinweis' && $wert == 0) {
				$wert = 1;
			}
			
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
	 */
	public function werte_in_db($tid) {

		// meldung-an
		$meldung_an = '';
		if($this->get_daten_by_name('a.meldung-an1')->get_wert() != 0) {
			$meldung_an = $this->get_daten_by_name('a.meldung-an0')->get_wert().','.$this->get_daten_by_name('a.meldung-an1')->get_wert();
		} else {
			$meldung_an = $this->get_daten_by_name('a.meldung-an0')->get_wert();
		}
		
		// daten fuer datenbank vorbereiten
		$meldeschluss_datum = date('Y-m-d',strtotime($this->get_daten_by_name('a.meldeschluss')->get_wert()));
		
		// datenbankobjekt holen
		$db = new NBdb();
		
		// sql fuer ausschreibung
		$sql_ausschreibung =	'INSERT INTO ausschreibung 
								(id,
								veranstalter_id,
								hallen_id,
								wiegen_id,
								startberechtigt_id,
								gewichtsklassen_id,
								modus_id,
								meldegeld_id,
								meldung_id,
								meldung_an,
								meldeschluss,
								hinweis_id,
								ansp_id,
								vorlagen_id)
							VALUES 
								(NULL,"'
								.$this->get_daten_by_name('a.veranstalter')->get_wert().'","'
								.$this->get_daten_by_name('a.hallen')->get_wert().'","'
								.$this->get_daten_by_name('a.wiegen')->get_wert().'","'
								.$this->get_daten_by_name('a.startberechtigt')->get_wert().'","'
								.$this->get_daten_by_name('a.gewichtsklassen')->get_wert().'","'
								.$this->get_daten_by_name('a.modus')->get_wert().'","'
								.$this->get_daten_by_name('a.meldegeld')->get_wert().'","'
								.$this->get_daten_by_name('a.meldung')->get_wert().'","'
								.$meldung_an.'","'
								.$meldeschluss_datum.'","'
								.$this->get_daten_by_name('a.hinweis')->get_wert().'","'
								.$this->get_daten_by_name('v.ansp')->get_wert().'","'
								.$this->get_daten_by_name('a.vorlagen')->get_wert().'")';
																
		// abfrage ausfuehren
		$resultat = $db->query($sql_ausschreibung);
		
		// eingefuegte id auslesen
		$ausschreibung_id = $db->read_insertid();

		
		//	sql fuer termin
		$sql_termin =	'UPDATE termin
						SET ausschreibungs_id="'.$ausschreibung_id.'"
						WHERE id="'.$tid.'"';
		
		// abfrage ausfuehren
		$resultat = $db->query($sql_termin);
		
		// verbindung beenden
		$db->close();
	}
	
	
	
	
	
	
	
	/**
	 * read_werte liest die werte aus der datenbank und traegt ihn in die daten ein
	 * 
	 * @param int $aid id der ausschreibung
	 */
	public function read_werte($aid) {
				
		$sql =	'SELECT 
				veranstalter_id AS "a.veranstalter", 
				hallen_id AS "a.hallen", 
				wiegen_id AS "a.wiegen", 
				startberechtigt_id AS "a.startberechtigt", 
				gewichtsklassen_id AS "a.gewichtsklassen", 
				modus_id AS "a.modus", 
				meldegeld_id AS "a.meldegeld", 
				meldung_id AS "a.meldung", 
				meldung_an, 
				meldeschluss AS "a.meldeschluss", 
				hinweis_id AS "a.hinweis", 
				ansp_id AS "v.ansp", 
				vorlagen_id AS "a.vorlagen" 
				FROM ausschreibung 
				WHERE id='.$aid;	
		
		// datenbank-objekt holen
		$db = new NBdb();
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		// daten zwischenspeichern
		$werte = $resultat->fetch_array(MYSQL_ASSOC);
				
		// datenbank-objekt schliessen
		$db->close();
				
		// daten setzen
		foreach($werte as $name => $wert) {
		
			// wenn datum, umwandeln
			if($name == 'a.meldeschluss') {
				$this->get_daten_by_name($name)->set_wert(utf8_encode(date('d.m.Y',strtotime($wert))));
			} elseif($name == 'meldung_an') {
				// pruefen, ob mehrere
				if(strpos($wert,',') === false) {
					$this->get_daten_by_name('a.meldung-an0')->set_wert(utf8_encode($wert));
					$this->get_daten_by_name('a.meldung-an1')->set_wert(utf8_encode(0));
				} else {
					list($m1,$m2) = explode(',',$wert);
					$this->get_daten_by_name('a.meldung-an0')->set_wert(utf8_encode($m1));
					$this->get_daten_by_name('a.meldung-an1')->set_wert(utf8_encode($m2));
				}
			} else {
				$this->get_daten_by_name($name)->set_wert(utf8_encode($wert));
			}
		}
		
		$this->set_checked(false);
		
	}
	
	
	
	
	
	
	/**
	 * update_werte_in_db updated die ueberprueften werte in die datenbank
	 * 
	 * @param int $tid id des termins
	 */
	public function update_werte_in_db($tabelle,$konfig,$aid) {

		// konfig trennen
		$explode = explode('|',$this->get_from_gc($konfig,'termin'));
	
		$sql = 'UPDATE '.$tabelle.' SET ';
		
		// meldung_an vorbereiten
		$meldung_an = '';
		
		for($i=0;$i<count($explode);$i++) {
					
			// erforderlich trennen
			list($feld,$erforderlich) = explode(';',$explode[$i],2);
			// felder trennen
			list($name1,$name2,$name3) = explode('.',$feld,3);
			$name = "$name1.$name2";
			
			// sql zusammenbauen
			// datum formatieren
			if($name2 == 'meldeschluss') {
				$sql .= $name2.'="'.utf8_decode(date('Y-m-d',strtotime($this->get_daten_by_name($name)->get_wert()))).'"';
			} elseif(strpos($name2,'meldung-an') !== false) {
				// nach 1. und 2. referenten trennen
				if($name2 == 'meldung-an0') {
					$meldung_an = $this->get_daten_by_name($name)->get_wert();
				} else {
					// wenn 2. nicht angegeben
					if($this->get_daten_by_name($name)->get_wert() != 0) {
						$meldung_an .= ','.$this->get_daten_by_name($name)->get_wert();
					}
					// an sql anhaengen
					$sql .= 'meldung_an ="'.$meldung_an.'", ';
				}
			} else {
				$sql .= $name2.'_id ="'.utf8_decode($this->get_daten_by_name($name)->get_wert()).'"';
			}
			if($i != count($explode)-1 && strpos($name2,'meldung-an') === false) { 
				$sql .= ', ';
			}
		}
		$sql .= ' WHERE id='.$aid;
				
		// datenbankobjekt holen
		$db = new NBdb();
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
	
		// verbindung beenden
		$db->close();
	}
	
	
}



?>
