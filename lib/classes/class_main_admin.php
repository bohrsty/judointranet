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

class NBmain_admin extends NBmain {
	
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
		return 'NBmain_admin';
	}
	
	
	
	
	
	
	/**
	 * admin_aktionen ruft den inhalt entsprechend der uebergebenen werte auf
	 * 
	 * @param string $id wert des id-parameters
	 * @param string $tabelle name der tabelle, aus der/in die die informationen gelesen/geschrieben werden
	 * @param string $text auf der seite an zu zeigender text
	 */
	protected function admin_aktionen($id,$tabelle,$text) {
		
		// ueberschrift setzen
		$this->inhalt('<h3>Administration</h3>');
		$this->inhalt($text);
		
		// rueckgabe vorbereiten
		$html = '';
		// konfig festlegen
		$konfig = 'neu_'.$id;
			
		// pruefen, ob bearbeiten oder loeschen gesetzt
		if(!is_null($this->get_get('aktion')) && $this->get_get('aktion') === 'bearbeiten' && $this->aid_ok($tabelle,$this->get_get('aid'))) {
			
			// bearbeiten
			// pruefen, ob formular abgeschickt
			if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $_POST[$this->get_from_gc('submit_name','intern')] == $this->get_from_gc('submit_wert','intern')) {
			
				// post einlesen
				$html .= $_SESSION['form_admin_bearbeiten']->read_post();
			
				// nur wenn checked === true, in datenbank eintragen
				if($_SESSION['form_admin_bearbeiten']->get_checked() === true) { 
						
					// eintragen
					$_SESSION['form_admin_bearbeiten']->update_werte_in_db($tabelle,$konfig,$this->get_get('aid'));
				}
			
			} else {
				
				// formular anzeigen	
				$_SESSION['form_admin_bearbeiten'] = new NBform_admin_neu($this->get_from_gc('tpl_'.$id,'admin'),'admin.php?id='.$id.'&aktion=bearbeiten&aid='.$this->get_get('aid'),$konfig);
				
				// werte laden
				$_SESSION['form_admin_bearbeiten']->read_werte($tabelle,$konfig,$this->get_get('aid'));
				
				// ausgabe
				$html .= $_SESSION['form_admin_bearbeiten']->to_html();
				
				// liste der verfuegbaren felder anhaengen
				$html .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),array('a','admin.php?id='.$id,'Bearbeiten abbrechen','abbrechen'));
				
				// liste der verfuegbaren felder anhaengen
				$html .= $this->read_admin_liste($tabelle,$id);
			}
		} elseif(!is_null($this->get_get('aktion')) && $this->get_get('aktion') === 'loeschen' && $this->aid_ok($tabelle,$this->get_get('aid'))) {
			
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
				$sql = 'DELETE FROM '.$tabelle.'
						WHERE id='.$this->get_get('aid');
				
				// abfrage ausführen
				$resultat = $db->query($sql);
				
				// datenbank-objekt schliessen
				$db->close();
				
				$this->inhalt('Eintrag gel&ouml;scht.');
			} elseif(isset($_POST[$nein_name]) && $_POST[$nein_name] == $nein_wert) {
				
				// abbrechen
				// header senden
				header('Location: admin.php?id='.$id);
				exit;
			} else {
				
				// formular anzeigen
				// argumente vorbereiten
				$args = array(	'POST',
								'admin.php?id='.$id.'&aktion=loeschen&aid='.$this->get_get('aid'),
								'Sind Sie sicher, dass der Eintrag gel&ouml;scht werden soll?',
								$ja_name,
								$ja_wert,
								$nein_name,
								$nein_wert);
				
				// in wrapper einbetten
				$html .= $this->parse_wrapper($this->get_from_gc('best_form','wrapper'),$args);
			}
		} else {
		
			// pruefen, ob formular abgeschickt
			if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $_POST[$this->get_from_gc('submit_name','intern')] == $this->get_from_gc('submit_wert','intern')) {
			
				// post einlesen
				$html .= $_SESSION['form_admin_neu']->read_post();
			
				// nur wenn checked === true, in datenbank eintragen
				if($_SESSION['form_admin_neu']->get_checked() === true) { 
						
					// eintragen
					$_SESSION['form_admin_neu']->werte_in_db($tabelle,$konfig);
				}
			
			} else {
				
				// formular anzeigen	
				$_SESSION['form_admin_neu'] = new NBform_admin_neu($this->get_from_gc('tpl_'.$id,'admin'),'admin.php?id='.$id,$konfig);
				
				// ausgabe
				$html .= $_SESSION['form_admin_neu']->to_html();
				
				// liste der verfuegbaren felder anhaengen
				$html .= $this->read_admin_liste($tabelle,$id);
			}
		}
		
		// rueckgabe
		return $html;		
	}
	
	
	
	
	
	
	/**
	 * read_admin_liste
	 * 
	 * @param string $tabelle name der ab zu fragenden tabelle
	 * @return liste der verfuegbaren felder
	 */
	private function read_admin_liste($tabelle,$id) {
		
		// rueckgabe vorbereiten
		$return = '';
		
		// datenbank-objekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = '	SELECT *
					FROM '.$tabelle;
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		// datenbank-objekt schliessen
		$db->close();
		
		// resultat verarbeiten
		$tr = '';
		$th = '';
		while($reihe = $resultat->fetch_array(MYSQL_ASSOC)) {
			
			// argumente vorbereiten
			$reihe['Aktionen'] = $this->parse_wrapper($this->get_from_gc('adminliste_akt','wrapper'),array('admin.php?id='.$id.'&aktion=bearbeiten&aid='.$reihe['id'],'admin.php?id='.$id.'&aktion=loeschen&aid='.$reihe['id']));
			$td = '';
			
			// ueberschriften vorbereiten
			$schluessel = array_keys($reihe);
			if($th == '') {
				for($i=1;$i<count($schluessel);$i++) {
					
//					$args = array($this->replace_umlaute(htmlspecialchars(utf8_encode($schluessel[$i]))));
					$args = array($this->replace_umlaute(htmlspecialchars($schluessel[$i])));
					$th .= $this->parse_wrapper($this->get_from_gc('adminliste_th','wrapper'),$args);
				}
				// tr zufuegen
				$tr .= $this->parse_wrapper($this->get_from_gc('adminliste_tr','wrapper'),array($th));
			}
			
			// reihe durchlaufen
			foreach($reihe as $name => $wert) {
				
				// wrapper einbinden
				if($name != 'id') {
					if($name == 'Aktionen') {
						
						$args = array($wert);
						$td .= $this->parse_wrapper($this->get_from_gc('adminliste_td','wrapper'),$args);
					} else {
						
//						$args = array(nl2br($this->replace_umlaute(htmlspecialchars(utf8_encode($wert)))));
						$args = array(nl2br($this->replace_umlaute(htmlspecialchars($wert))));
						$td .= $this->parse_wrapper($this->get_from_gc('adminliste_td','wrapper'),$args);
					}
				}
			}
			
			// tr zufuegen
			$tr .= $this->parse_wrapper($this->get_from_gc('adminliste_tr','wrapper'),array($td));
			
		}
		
		// tr in table einbetten
		$return .= $this->parse_wrapper($this->get_from_gc('adminliste','wrapper'),array($tr));
		
		// rueckgabe
		return $return;
	}
	
	
	
	
	
	
	/**
	 * aid_ok prueft, ob aid angegeben und ob aid existiert
	 * 
	 * @param int $aid die zu pruefende aid
	 * @return bool true, wenn angegeben und existiert, false sonst
	 */
	private function aid_ok($tabelle,$aid) {
		
		// pruefen, ob aid gesetzt
		if(is_null($aid)) {
			return false;
		}
		
		// aids abfragen
		// datenbank-objekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = '	SELECT id
					FROM '.$tabelle;
		
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
