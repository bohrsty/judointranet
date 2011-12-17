<?php

// sicherung abfragen
if(!defined('SICHERUNG')) {
	$meldung = 'Diese Datei darf nicht direkt aufgerufen werden! ['.basename($_SERVER['PHP_SELF']).']';
	$meldung .= '<br /><a href="../../index.php">Startseite</a>';
	die($meldung);
}


/**
 * klasse repraesentiert ein html input-tag und die zugehoerigen werte 
 */

class NBmain_verein extends NBmain {
	
	/*
	 * klassenvariablen
	 */
	
	
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct() {
		
		// elter instanzieren
		parent::__construct();
		
		// klassenvariablen initialisieren
		
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	
	
	
	/*
	 * methoden
	 */
	/**
	 * instance_of den namen der klasse zurueck
	 * 
	 * @return string name der klasse
	 */
	protected function instance_of() {
		return 'NBmain_verein';
	}
	
	
	
	
	
	
	/**
	 * admin_aktionen ruft den inhalt entsprechend der uebergebenen werte auf
	 * 
	 * @param string $id wert des id-parameters
	 * @param string $tabelle name der tabelle, aus der/in die die informationen gelesen/geschrieben werden
	 */
	protected function ansp($id,$tabelle,$text) {
		
		// ueberschrift setzen
		$this->inhalt('<h3>Ansprechpartner</h3>');
		$this->inhalt($text);
		
		// rueckgabe vorbereiten
		$html = '';
		// konfig festlegen
		$konfig = 'neu_'.$id;
			
		// pruefen, ob bearbeiten oder loeschen gesetzt
		if(!is_null($this->get_get('aktion')) && $this->get_get('aktion') === 'bearbeiten' && $this->aid_ok($this->get_get('aid'))) {
			
			// bearbeiten
			// pruefen, ob formular abgeschickt
			if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $_POST[$this->get_from_gc('submit_name','intern')] == $this->get_from_gc('submit_wert','intern')) {
			
				// post einlesen
				$html .= $_SESSION['form_vansp_bearbeiten']->read_post();
			
				// nur wenn checked === true, in datenbank eintragen
				if($_SESSION['form_vansp_bearbeiten']->get_checked() === true) { 
						
					// eintragen
					$_SESSION['form_vansp_bearbeiten']->update_werte_in_db($tabelle,$konfig,$this->get_get('aid'));
				}
			
			} else {
				
				// formular anzeigen	
				$_SESSION['form_vansp_bearbeiten'] = new NBform_v_ansp($this->get_from_gc('tpl_'.$id,'admin'),'verein.php?id='.$id.'&aktion=bearbeiten&aid='.$this->get_get('aid'),$konfig);
				
				// werte laden
				$_SESSION['form_vansp_bearbeiten']->read_werte($this->get_get('aid'));
				
				// ausgabe
				$html .= $_SESSION['form_vansp_bearbeiten']->to_html();
				
				// abbrechen-link anhaengen
				$html .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),array('a','verein.php?id=vansp','Bearbeiten abbrechen','abbrechen'));
			}
		} elseif(!is_null($this->get_get('aktion')) && $this->get_get('aktion') === 'loeschen' && $this->aid_ok($this->get_get('aid'))) {
			
			// loeschen
			$ja_name = 'button_ja';
			$ja_wert = 'Ja';
			$nein_name = 'button_nein';
			$nein_wert =  'Nein';
			
			// pruefen, ob formular abgeschickt
			if(isset($_POST[$ja_name]) && $_POST[$ja_name] == $ja_wert) {
			
				// eintrag loeschen
				// datenbank-objekt holen
				$db = new NBdb();
				
				// abfrage vorbereiten
				$sql = 'DELETE FROM v_ansp
						WHERE id='.$this->get_get('aid');
				
				// abfrage ausführen
				$resultat = $db->query($sql);
				
				// datenbank-objekt schliessen
				$db->close();
				
				$this->inhalt('Eintrag gel&ouml;scht.');
			} elseif(isset($_POST[$nein_name]) && $_POST[$nein_name] == $nein_wert) {
				
				// abbrechen
				// header senden
				header('Location: verein.php?id=vansp');
				exit;
			} else {
				
				// formular anzeigen
				// argumente vorbereiten
				$args = array(	'POST',
								'verein.php?id=vansp&aktion=loeschen&aid='.$this->get_get('aid'),
								'Sind Sie sicher, dass der Eintrag gel&ouml;scht werden soll?',
								$ja_name,
								$ja_wert,
								$nein_name,
								$nein_wert);
				
				// in wrapper einbetten
				$html .= $this->parse_wrapper($this->get_from_gc('best_form','wrapper'),$args);
			}
		} else {
		
			// neu erstellen (alles andere)
			// pruefen, ob formular abgeschickt
			if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $_POST[$this->get_from_gc('submit_name','intern')] == $this->get_from_gc('submit_wert','intern')) {
			
				// post einlesen
				$html .= $_SESSION['form_vansp_neu']->read_post();
			
				// nur wenn checked === true, in datenbank eintragen
				if($_SESSION['form_vansp_neu']->get_checked() === true) { 
						
					// eintragen
					$_SESSION['form_vansp_neu']->werte_in_db($tabelle,$konfig);
				}
			
			} else {
				
				// formular anzeigen	
				$_SESSION['form_vansp_neu'] = new NBform_v_ansp($this->get_from_gc('tpl_'.$id,'admin'),'verein.php?id='.$id,$konfig);
				
				// ausgabe
				$html .= $_SESSION['form_vansp_neu']->to_html();
				
				// ansprechpartnerliste anhaengen
				$html .= $this->parse_wrapper($this->get_from_gc('anspliste','wrapper'),array($this->read_ansp()));
			}
		}
		
		// rueckgabe
		return $html;		
	}
	
	
	
	
	/**
	 * read_vereine gibt die liste der vereine zurueck
	 * 
	 * @return liste der vereine
	 */
	public function read_vereine() {
		
		// rueckgabe vorbereiten
		$return = '';
		
		// datenbank-objekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = '	SELECT nummer,name
					FROM verein';
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		// datenbank-objekt schliessen
		$db->close();
		
		// resultat durchlaufen und in wrapper einbetten
		while(list($nummer,$name) = $resultat->fetch_array(MYSQL_NUM)) {
			
			// wrapper vorbereiten
//			$args = array($nummer,utf8_encode($name));
			$args = array($nummer,$name);
			
			$return .= $this->parse_wrapper($this->get_from_gc('vereinsliste','wrapper'),$args);
		}
		
		// rueckgabe
		return $return;
	}
	
	
	
	
	
	/**
	 * read_vereine gibt die liste der ansprechpartner zurueck
	 * 
	 * @return liste der ansprechpartner
	 */
	private function read_ansp() {
		
		// rueckgabe vorbereiten
		$return = '';
		
		// datenbank-objekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = '	SELECT a.id,a.name,v.name
					FROM v_ansp AS a, verein AS v
					WHERE a.verein=v.id
					ORDER BY a.name';
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		// datenbank-objekt schliessen
		$db->close();
		
		// resultat durchlaufen und in wrapper einbetten
		while(list($a_id,$a_name,$v_name) = $resultat->fetch_array(MYSQL_NUM)) {
			
			// wrapper vorbereiten
//			$args = array(utf8_encode($a_name).' ('.utf8_encode($v_name).')','verein.php?id=vansp&aktion=bearbeiten&aid='.$a_id,'verein.php?id=vansp&aktion=loeschen&aid='.$a_id);
			$args = array($a_name.' ('.$v_name.')','verein.php?id=vansp&aktion=bearbeiten&aid='.$a_id,'verein.php?id=vansp&aktion=loeschen&aid='.$a_id);
			
			$return .= $this->parse_wrapper($this->get_from_gc('ansp_anspliste','wrapper'),$args);
		}
		
		// rueckgabe
		return $return;
	}
	
	
	
	
	
	
	/**
	 * aid_ok prueft, ob aid angegeben und ob aid existiert
	 * 
	 * @param int $aid die zu pruefende aid
	 * @return bool true, wenn angegeben und existiert, false sonst
	 */
	private function aid_ok($aid) {
		
		// pruefen, ob aid gesetzt
		if(is_null($aid)) {
			return false;
		}
		
		// aids abfragen
		// datenbank-objekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = '	SELECT id
					FROM v_ansp';
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		// datenbank-objekt schliessen
		$db->close();
		
		// aids zwischenspeichern
		$aids = array();
		while(list($id) = $resultat->fetch_array(MYSQL_NUM)) {
			
			$aids[] = $id;
		}
		
		// pruefen, ob aid in ausgelesenen aids
		if(!in_array($aid,$aids)) {
			return false;
		}
		
		// sonst ok
		return true;
	}
	
	
}



?>
