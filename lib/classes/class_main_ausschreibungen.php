<?php

// sicherung abfragen
if(!defined('SICHERUNG')) {
	$meldung = 'Diese Datei darf nicht direkt aufgerufen werden! ['.basename($_SERVER['PHP_SELF']).']';
	$meldung .= '<br /><a href="../../index.php">Startseite</a>';
	die($meldung);
}


/**
 * klasse repraesentiert eine seite (ausschreibung neu/show) 
 */

class NBmain_ausschreibungen extends NBmain {
	
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
		return 'NBmain_ausschreibungen';
	}
	
	
	
	
	
	
	/**
	 * a_neu ruft den inhalt entsprechend der uebergebenen werte auf
	 * 
	 * @param string $text auf der seite an zu zeigender text
	 */
	private function a_neu() {
		
		// ueberschrift setzen
		$this->inhalt('<h3>Ausschreibungen</h3>');
		
		// rueckgabe vorbereiten
		$html = '';
		
		
		// pruefen, ob formular abgeschickt
		if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $_POST[$this->get_from_gc('submit_name','intern')] == $this->get_from_gc('submit_wert','intern')) {
		
			// post einlesen
			$html .= $_SESSION['form_a_neu']->read_post();
			
			// nur wenn checked === true, in datenbank eintragen
			if($_SESSION['form_a_neu']->get_checked() === true) {
				
				// eintragen
				$_SESSION['form_a_neu']->werte_in_db($this->get_get('tid'));
			}
		
		} else {
			
			// objekt loeschen, wenn existiert
			if(isset($_SESSION['form_a_neu'])) {
				unset($_SESSION['form_a_neu']);
			}
			
			// formular anzeigen	
			$_SESSION['form_a_neu'] = new NBform_a_neu($_SESSION['GC']['termin']['a_neu_tpl_path'],'ausschreibungen.php?id=ausschreibung&aktion=neu&tid='.$this->get_get('tid'));
		
			// ausgabe
			$html .= $_SESSION['form_a_neu']->to_html();
		}
		
		// rueckgabe
		return $html;
	}
	
	
	
	
	
	
	/**
	 * t_neu ruft den inhalt entsprechend der uebergebenen werte auf
	 * 
	 * @param string $text auf der seite an zu zeigender text
	 */
	private function t_neu() {
		
		// ueberschrift setzen
		$this->inhalt('<h3>Termine</h3>');
		
		// rueckgabe vorbereiten
		$html = '';
		
		
		// pruefen, ob formular abgeschickt
		if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $_POST[$this->get_from_gc('submit_name','intern')] == $this->get_from_gc('submit_wert','intern')) {
		
			// post einlesen
			$html .= $_SESSION['form_t_neu']->read_post();
			
			// nur wenn checked === true, in datenbank eintragen
			if($_SESSION['form_t_neu']->get_checked() === true) {
				
				// eintragen
				$tid = $_SESSION['form_t_neu']->werte_in_db();
				
				// weiter-link
				$a_args = array('t_a_neu','ausschreibungen.php?id=ausschreibung&aktion=neu&tid='.$tid,'Ausschreibungsdaten eingeben','Ausschreibung erstellen');
				$this->inhalt($this->parse_wrapper($this->get_from_gc('a','wrapper'),$a_args));
			}
		
		} else {
			
			// objekt loeschen, wenn existiert
			if(isset($_SESSION['form_t_neu'])) {
				unset($_SESSION['form_t_neu']);
			}
			
			// formular anzeigen	
			$_SESSION['form_t_neu'] = new NBform_t_neu($_SESSION['GC']['termin']['t_neu_tpl_path'],'ausschreibungen.php?id=termin&aktion=neu');
		
			// ausgabe
			$html .= $_SESSION['form_t_neu']->to_html();
		}
		
		// rueckgabe
		return $html;
	}
	
	
	
	
	
	
	/**
	 * ausschreibung ruft den inhalt entsprechend der uebergebenen werte auf
	 */
	protected function ausschreibung() {
		
		// rueckgabe vorbereiten
		$html = '';
		
		// pruefen, ob $_GET['action'] gesetzt, sonst terminliste anzeigen
		if(is_null($this->get_get('aktion'))) {
			
			// fehler ausgeben
			$html .= $this->read_error('link_not_valid');
		} else {
		
			// pruefen, ob $_GET['action'] nicht false, sonst fehlermeldung
			if($this->get_get('aktion') === false) {
				
				// fehlermeldung erzeugen
				$html .= $this->read_error('url_param_not_valid');
			} elseif($this->get_get('aktion') == 'bearbeiten') {
				
				// bearbeiten
				// pruefen, ob formular abgeschickt
				if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $_POST[$this->get_from_gc('submit_name','intern')] == $this->get_from_gc('submit_wert','intern')) {
				
					// post einlesen
					$html .= $_SESSION['form_a_bearbeiten']->read_post();
					
					// nur wenn checked === true, in datenbank eintragen
					if($_SESSION['form_a_bearbeiten']->get_checked() === true) {
						
						// eintragen
						$_SESSION['form_a_bearbeiten']->update_werte_in_db('ausschreibung','a_neu_daten',$this->get_get('aid'));
					}
				
				} else {
					
					// objekt loeschen, wenn existiert
					if(isset($_SESSION['form_a_bearbeiten'])) {
						unset($_SESSION['form_a_bearbeiten']);
					}
					
					// formular anzeigen	
					$_SESSION['form_a_bearbeiten'] = new NBform_a_neu($_SESSION['GC']['termin']['a_neu_tpl_path'],'ausschreibungen.php?id=ausschreibung&aktion=bearbeiten&aid='.$this->get_get('aid'));
					
					// werte laden
					$_SESSION['form_a_bearbeiten']->read_werte($this->get_get('aid'));
					
					// ausgabe
					$html .= $_SESSION['form_a_bearbeiten']->to_html();
					
					// links anhaengen
					$abr_args = array(	'a',
										'ausschreibungen.php?id=termin',
										'Bearbeiten abbrechen',
										'abbrechen');
					$html .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),$abr_args);
				}
			} elseif($this->get_get('aktion') == 'loeschen') {
					
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
					$sql = 'DELETE FROM ausschreibung
							WHERE id="'.$this->get_get('aid').'"';
					$resultat = $db->query($sql);

					// termin anpassen
					$sql = 'UPDATE termin
							SET ausschreibungs_id=0
							WHERE ausschreibungs_id="'.$this->get_get('aid').'"';
					
					// abfrage ausführen
					$resultat = $db->query($sql);

					
					// datenbank-objekt schliessen
					$db->close();
					
					$this->inhalt('Ausschreibung gel&ouml;scht.');
				} elseif(isset($_POST[$nein_name]) && $_POST[$nein_name] == $nein_wert) {
					
					// abbrechen
					// header senden
					header('Location: ausschreibungen.php?id=termin');
					exit;
				} else {
					
					// formular anzeigen
					// argumente vorbereiten
					$args = array(	'POST',
									'ausschreibungen.php?id='.$this->get_get('id').'&aktion=loeschen&aid='.$this->get_get('aid'),
									'Sind Sie sicher, dass die Ausschreibung gel&ouml;scht werden soll?',
									$ja_name,
									$ja_wert,
									$nein_name,
									$nein_wert);
					
					// in wrapper einbetten
					$html .= $this->parse_wrapper($this->get_from_gc('best_form','wrapper'),$args);
				}
				
			} elseif($this->get_get('aktion') == 'neu') {
				
				// auf $_GET['tid'] pruefen, sonst fehlermeldung

				if(is_null($this->get_get('tid'))) {
					
					// fehlermeldung erzeugen
					$html .= $this->read_error('tid_not_found');
				} else {
					
					// aktion ausfuehren
					$html .= $this->a_neu();
				}
			} else {
				
				// fehlermeldung erzeugen
				$html .= $this->read_error('link_not_valid');
			}
		}
		
		// rueckgabe
		return $html;
	}
	
	
	
	
	
	
	/**
	 * termin ruft den inhalt entsprechend der uebergebenen werte auf
	 */
	protected function termin() {
		
		// rueckgabe vorbereiten
		$html = '';
		
		// pruefen, ob $_GET['action'] gesetzt, sonst terminliste anzeigen
		if(is_null($this->get_get('aktion'))) {
			
			// terminliste ausgeben
			$html .= $this->read_terminliste();
		} else {
		
			// pruefen, ob $_GET['action'] nicht false, sonst fehlermeldung
			if($this->get_get('aktion') === false) {
				
				// fehlermeldung erzeugen
				$html .= $this->read_error('url_param_not_valid');
			}elseif($this->get_get('aktion') == 'neu') {
				
				// neuen termin erstellen
				$html .= $this->t_neu();
			} else {
				
				// auf $_GET['tid'] pruefen, sonst fehlermeldung

				if(is_null($this->get_get('tid'))) {
					
					// fehlermeldung erzeugen
					$html .= $this->read_error('tid_not_found');
				} else {
					
					// aktion ausfuehren
					$html .= $this->t_aktion();
				}
			}
		}
		
		// rueckgabe
		return $html;
	}
	
	
	
	
	
	
	/**
	 * a_neu ruft den inhalt entsprechend der uebergebenen werte auf
	 */
	/**
	 * read_terminliste liest die termine aus der datenbank und fuegt die notwendigen links hinzu
	 * 
	 * @return string terminliste als html
	 */
	private function read_terminliste() {
		
		// ueberschrift setzen
		$this->inhalt('<h3>Terminliste</h3>');
		
		// datenbankobjekt holen
		$db = new NBdb();
		
		// termin query vorbereiten
		$sql_termin = '	SELECT t.id,t.datum,t.name,t.ausschreibungs_id,k.altersklasse
						FROM termin AS t,t_kategorie AS k
						WHERE t.kategorie=k.id
						ORDER BY t.datum';
					
		// termin query ausfuehren
		$resultat_termin = $db->query($sql_termin);
		
		// termin zwischenspeichern
		$termin = array();
		while(list($tid,$tdatum,$tname,$taid,$taltersklasse) = $resultat_termin->fetch_array(MYSQL_NUM)) {
			
			// pruefen, ob ausschreibung erstellt, dann ort auslesen
			$tort = '';
			if($taid != 0) {
				
				// ort abfragen
				// abfrage vorbereiten
				$sql_ort = '	SELECT h.ort
								FROM termin AS t,t_kategorie AS k,a_hallen AS h,ausschreibung AS a
								WHERE t.ausschreibungs_id=a.id
								AND t.kategorie=k.id
								AND a.hallen_id=h.id
								AND t.id="'.$tid.'"';
				
				// abfrage ausfuehren
				$resultat_ort = $db->query($sql_ort);
				
				list($tort) = $resultat_ort->fetch_array(MYSQL_NUM);
			}
			
			$termin[$tid] = array('datum' => $tdatum, 'name' => $tname, 'aid' => $taid, 'altersklasse' => $taltersklasse, 'ort' => $tort);
		}
		
		// datenbankobjekt schliessen
		$db->close();
		
		// rueckgabe vorbereiten
		$reihen = '';
		
		// gerade/ungerade vorbereiten
		$zaehler = 0;
		
		// abfrage durchlaufen
		foreach($termin as $tid => $werte) {
			
			// pruefen, ob gerade oder ungerade und class entsprechend setzen
			$class = '';
			if($zaehler % 2 == 1) {
				// ungerade
				$class = 'odd';
			} else {
				// gerade
				$class = 'even';
			}
			
			// links erzeugen
			// abfragen, ob ausschreibung vorhanden
			$links = '';
			if($werte['aid'] == 0) {
				
				$anlegen_arg = array(	'a_tl_details',
										$_SERVER['PHP_SELF'].'?id=ausschreibung&aktion=neu&tid='.$tid,
										'Ausschreibung anlegen',
										'Ausschreibung anlegen');
				$bearb_args = array(	'a_tl_bearbeiten',
										$_SERVER['PHP_SELF'].'?id='.$this->get_get('id').'&aktion=bearbeiten&tid='.$tid,
										'Termin bearbeiten',
										'bearbeiten');
				$loeschen_args = array(	'a_tl_loeschen',
										$_SERVER['PHP_SELF'].'?id='.$this->get_get('id').'&aktion=loeschen&tid='.$tid,
										'Termin l&ouml;schen',
										'l&ouml;schen');
				$links = $this->parse_wrapper($this->get_from_gc('a','wrapper'),$anlegen_arg).' ';
				$links .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),$bearb_args).' ';
				$links .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),$loeschen_args);
			} else {
				$detail_arg = array(	'a_tl_details',
										$_SERVER['PHP_SELF'].'?id='.$this->get_get('id').'&aktion=details&tid='.$tid,
										'Details zur Ausschreibung',
										'Details');
				$topdf_args = array(	'a_tl_topdf',
										$_SERVER['PHP_SELF'].'?id='.$this->get_get('id').'&aktion=topdf&tid='.$tid,
										'Ausschreibung als .pdf ausgeben',
										'PDF');
				$bearb_args = array(	'a_tl_bearbeiten',
										$_SERVER['PHP_SELF'].'?id='.$this->get_get('id').'&aktion=bearbeiten&tid='.$tid,
										'Termin bearbeiten',
										'bearbeiten');
				$loeschen_args = array(	'a_tl_loeschen',
										$_SERVER['PHP_SELF'].'?id='.$this->get_get('id').'&aktion=loeschen&tid='.$tid,
										'Termin l&ouml;schen',
										'l&ouml;schen');
				$links = $this->parse_wrapper($this->get_from_gc('a','wrapper'),$detail_arg).' ';
				$links .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),$topdf_args).' ';
				$links .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),$bearb_args).' ';
				$links .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),$loeschen_args);
			}
			
			// reihe erzeugen
			$reihe_args = array(	$class,
									date('d.m.Y',strtotime($werte['datum'])),
									htmlentities($werte['name']),
									htmlentities($werte['altersklasse']),
									htmlentities($werte['ort']),
									$links);
			$reihen .= $this->parse_wrapper($this->get_from_gc('terminliste_reihe','wrapper'),$reihe_args);
			
			// zaehler erhoehen
			$zaehler++;
		}
		
		// tabelle zusammenfuegen
		$tabelle_args = array($reihen);
		
		// neu-link erzeugen
		$neu_args = array('a_t_neu','ausschreibungen.php?id=termin&aktion=neu','Neuen Termin erstellen','Neuen Termin erstellen');
		
		// rueckgabe
		$return = '';
		$return .= $this->parse_wrapper($this->get_from_gc('pa','wrapper'),$neu_args);
		$return .= '<p></p>';
		$return .= $this->parse_wrapper($this->get_from_gc('terminliste_tabelle','wrapper'),$tabelle_args);
		return $return;
	}
	
	
	
	
	
	
	
	/**
	 * read_ausschreibungen_alle liest die daten der auschreibung aus der db und gibt sie als 
	 * array zurueck
	 * 
	 * @param int $get_tid id des termins in der datenbank
	 * @return mixed array mit allen werten der ausschreibung oder false im fehlerfall
	 */
	private function read_auschreibung_alle($get_tid) {
		
		// datenbankobjekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = '	SELECT t.datum AS "t.datum",t.name AS "t.name", t.kurzname AS "t.kurzname",
					k.altersklasse AS "t.altersklasse",k.guertelfarben AS "t.guertelfarbe",k.jahrgaenge AS "t.jahrgaenge",
					h.halle AS "a.hallen.halle",h.ort AS "a.hallen.ort",
					va.veranstalter AS "a.veranstalter",
					v.name AS "a.verein",
					w.zeiten AS "a.wiegen",
					s.berechtigte AS "a.startberechtigt",
					g.klassen AS "a.gewichtsklassen",
					mo.modus AS "a.modus",
					mg.meldegeld AS "a.meldegeld",
					md.meldung AS "a.meldung",
					a.meldeschluss AS "a.meldeschluss",a.meldung_an AS "split.meldung_an",
					hw.hinweis AS "a.hinweis",
					van.name AS "a.ansp",
					vl.pfad AS "pdml.path"
					FROM termin AS t,
					t_kategorie AS k,
					a_hallen AS h,
					ausschreibung AS a,
					a_veranstalter AS va,
					verein AS v,
					a_wiegen AS w,
					a_startberechtigt AS s,
					a_gewichtsklassen AS g,
					a_modus AS mo,
					a_meldegeld AS mg,
					a_meldung AS md,
					a_hinweis AS hw,
					v_ansp AS van,
					a_vorlagen AS vl
					WHERE t.id="' .$get_tid. '"
					AND t.ausschreibungs_id=a.id
					AND t.kategorie=k.id
					AND a.hallen_id=h.id
					AND a.veranstalter_id=va.id
					AND van.verein=v.id
					AND a.wiegen_id=w.id
					AND a.startberechtigt_id=s.id
					AND a.gewichtsklassen_id=g.id
					AND a.modus_id=mo.id
					AND a.meldegeld_id=mg.id
					AND a.meldung_id=md.id
					AND a.hinweis_id=hw.id
					AND a.ansp_id=van.id
					AND a.vorlagen_id=vl.id
					LIMIT 1';
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		$sql = '';
		
		// ausschreibung vorbereiten
		$ausschreibung = array();
		
		// pruefen, ob genau ein ergebnis
		if($resultat->num_rows != 1) {
			
			// fehler zurueckgeben
			return false;
		} else {
			
			// resultat in ausschreibung speichern
			$ausschreibung = $resultat->fetch_array(MYSQL_ASSOC);
			$resultat->close();
			
			// benutzerdaten fuer meldung_an abfragen
			// wenn meldung_an "," enthaelt, trennen
			if(strpos($ausschreibung['split.meldung_an'],',') !== false) {
				
				list($ref1,$ref2) = explode(',',$ausschreibung['split.meldung_an']);
				
				// abfrage vorbereiten
				$sql = '	SELECT name,adresse,email,posten
							FROM benutzer
							WHERE id="'.$ref1.'"
							OR id="'.$ref2.'"';
			} else {
				
				// abfrage vorbereiten
				$sql = '	SELECT name,adresse,email,posten
							FROM benutzer
							WHERE id="'.$ausschreibung['split.meldung_an'].'"';
			}
			
			// abfrage ausfuehren
			$resultat = $db->query($sql);
			
			// datenbankobjekt schliessen
			$db->close();
			
			// meldung_an-wert loeschen
			unset($ausschreibung['split.meldung_an']);
			
			// daten zwischenspeichern
			$ref_daten = array();
			while($reihe = $resultat->fetch_array(MYSQL_ASSOC)) {
				
				$ref_daten[] = $reihe;
			}
			
			// referentendaten an $ausschreibung anhaengen
			$ausschreibung['a.meldung_an0.name'] = $ref_daten[0]['name'];
			$ausschreibung['a.meldung_an0.adresse'] = $ref_daten[0]['adresse'];
			$ausschreibung['a.meldung_an0.email'] = $ref_daten[0]['email'];
			$ausschreibung['a.meldung_an0.posten'] = $ref_daten[0]['posten'];
			
			// wenn zweiter referent
			if(isset($ref_daten[1])) {
				
				// referentendaten an $ausschreibung anhaengen
				$ausschreibung['a.meldung_an1.name'] = $ref_daten[1]['name'];
				$ausschreibung['a.meldung_an1.adresse'] = $ref_daten[1]['adresse'];
				$ausschreibung['a.meldung_an1.email'] = $ref_daten[1]['email'];
				$ausschreibung['a.meldung_an1.posten'] = $ref_daten[1]['posten'];
			}
			
			// datum umformatieren
			// 20100101
			$ausschreibung['t.datum.Ymd'] = date('Ymd',strtotime($ausschreibung['t.datum']));
			// 01. Januar 2010
			$ausschreibung['t.datum.j.F.Y'] = strftime('%e. %B %Y',strtotime($ausschreibung['t.datum']));
			// 01012010
			$ausschreibung['t.datum.dmY'] = date('dmY',strtotime($ausschreibung['t.datum']));
			
			// meldeschluss datum formatieren
			$ausschreibung['a.meldeschluss'] = strftime('%e. %B %Y',strtotime($ausschreibung['a.meldeschluss']));
			
			// version erzeugen
			$ausschreibung['a.version'] = date('dmy');
			
			// referenten_komplett zusammensetzen
			$ausschreibung['a.referenten.komplett'] =	$ausschreibung['a.meldung_an0.name']."\n"
														.$ausschreibung['a.meldung_an0.adresse']."\n" 
														.'Email: '.$ausschreibung['a.meldung_an0.email']."\n";
			// wenn zweiter referent
			if(isset($ausschreibung['a.meldung_an1.name']) && isset($ausschreibung['a.meldung_an1.adresse']) && isset($ausschreibung['a.meldung_an1.email'])) {
				
				$ausschreibung['a.referenten.komplett'] .=	"\n".$ausschreibung['a.meldung_an1.name']."\n"
															.$ausschreibung['a.meldung_an1.adresse']."\n" 
															.'Email: '.$ausschreibung['a.meldung_an1.email']."\n\n";
			}
			
			// referenten_namen und referenten_posten zusammensetzen
			$ausschreibung['a.referenten.namen'] = $ausschreibung['a.meldung_an0.name'];
			if(isset($ausschreibung['a.meldung_an1.name'])) {
				
				$ausschreibung['a.referenten.namen'] .= ', ' .$ausschreibung['a.meldung_an1.name'];
			}
			$ausschreibung['a.referenten.posten'] = $ausschreibung['a.meldung_an0.posten'];
			if(isset($ausschreibung['a.meldung_an1.posten'])) {
				
				$ausschreibung['a.referenten.posten'] .= ', ' .$ausschreibung['a.meldung_an1.posten'];
			}
			
			// pdf-dateinamen erzeugen
			$ausschreibung['pdml.filename'] = $this->replace_marker($this->get_from_gc('ausschr_filename_tpl','termin'),$ausschreibung);
			
			// rueckgabe
			return $ausschreibung;
		}
	}
	
	
	
	
	
	
	/**
	 * aktion fuehrt die entsprechende aktion auf den uebergebenen termin aus
	 */
	private function t_aktion() {
		
		// rueckgabe vorbereiten
		$html ='';
		
		/*
         * im pdf verwendbare felder (als marker eingeschlossen in ###)
         * 
         * a.veranstalter
         * a.verein
         * a.hallen.halle
         * a.hallen.ort
         * a.wiegen
         * a.startberechtigt
         * a.gewichtsklassen
         * a.modus
         * a.meldegeld
         * a.meldung
         * a.meldung_an0.name
         * a.meldung_an0.adresse
         * a.meldung_an0.email
         * a.meldung_an0.posten
         * a.meldung_an1.name
         * a.meldung_an1.adresse
         * a.meldung_an1.email
         * a.meldung_an1.posten
         * a.meldeschluss
         * a.hinweis
         * a.ansp
         * a.version
         * t.datum
         * t.datum.Ymd
         * t.datum.dmY
         * t.datum.d.F.Y
         * t.name
         * t.altersklasse
         * t.guertelfarbe
         * t.jahrgaenge
         * a.referenten.komplett
         * a.referenten.namen
         * a.referenten.posten
         */
        /*
         * pdml-einstellungen
         * 
         * pdml.path
         * pdml.filename
         */
        
        // alle daten der ausschreibung aus der datenbank lesen und in array speichern
		$ausschreibung = array();
		$ausschreibung = $this->read_auschreibung_alle($this->get_get('tid'));
					
		switch($this->get_get('aktion')) {
  
			case 'details':
			  	
				// pruefen, ob ausschreibung erfolgreich ausgelesen
				if($ausschreibung === false) {
					
					// fehlermeldung zurueckgeben
					$html .= $this->read_error('tid_not_found');
				} else {	
				  	// template-datei einlesen
					$fh = fopen($this->get_from_gc('detail_tpl_path','termin'),'r');
					$tpl = fread($fh,filesize($this->get_from_gc('detail_tpl_path','termin')));
					fclose($fh);
					
					// marker ersetzen
					$tmp = $this->replace_marker($tpl,$ausschreibung,'html');
					$html .= $this->replace_marker($tmp,$ausschreibung,'html');
					
					// zurueck- und to_pdf-links erstellen
					$b_t_pdf_args = array(	'ausschreibungen.php?id=termin',
											'ausschreibungen.php?id=termin&aktion=topdf&tid='.$this->get_get('tid'));
					
					// wrapper parsen
					$html .= $this->parse_wrapper($this->get_from_gc('details_back_topdf','wrapper'),$b_t_pdf_args);
				}					
			break;

			case 'topdf':
			
				// pruefen, ob ausschreibung erfolgreich ausgelesen
				if($ausschreibung === false) {
					
					// fehlermeldung zurueckgeben
					$html .= $this->read_error('tid_not_found');
				} else {
				
					// pdml-datei einlesen
					$fh = fopen($ausschreibung['pdml.path'],'r');
					$pdml = fread($fh,filesize($ausschreibung['pdml.path']));
					fclose($fh);
			
					// marker ersetzen
					$tmp = $this->replace_marker($pdml,$ausschreibung,'pdml');
					$pdml = $this->replace_marker($tmp,$ausschreibung,'pdml');
					
					// pdml vorbereiten
					$_SESSION['pdml']['dateiname'] = $ausschreibung['pdml.filename'];
					$_SESSION['pdml']['pdml'] = $pdml;
					
					// pdml extern aufrufen
					header('Location: topdf.php');
					exit();
				}
				
			break;
			
			case 'bearbeiten':
				
				// bearbeiten
				// pruefen, ob formular abgeschickt
				if(isset($_POST[$this->get_from_gc('submit_name','intern')]) && $_POST[$this->get_from_gc('submit_name','intern')] == $this->get_from_gc('submit_wert','intern')) {
				
					// post einlesen
					$html .= $_SESSION['form_t_bearbeiten']->read_post();
				
					// nur wenn checked === true, in datenbank eintragen
					if($_SESSION['form_t_bearbeiten']->get_checked() === true) { 
							
						// eintragen
						$_SESSION['form_t_bearbeiten']->update_werte_in_db('termin','t_neu_daten',$this->get_get('tid'));
					}
				
				} else {
					
					// formular anzeigen	
					$_SESSION['form_t_bearbeiten'] = new NBform_t_neu($_SESSION['GC']['termin']['t_neu_tpl_path'],'ausschreibungen.php?id=termin&aktion=bearbeiten&tid='.$this->get_get('tid'));
					
					// werte laden
					$aid = $_SESSION['form_t_bearbeiten']->read_werte($this->get_get('tid'));
					
					// ausgabe
					$html .= $_SESSION['form_t_bearbeiten']->to_html();
					
					// links anhaengen
					$abr_args = array(	'a',
										'ausschreibungen.php?id='.$this->get_get('id'),
										'Bearbeiten abbrechen',
										'abbrechen');
					$html .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),$abr_args);
					
					// links und details nur wenn ausschreibung
					if($aid != 0) {
						
						// bearbeiten-link
						$bearb_args = array('a',
											'ausschreibungen.php?id=ausschreibung&aktion=bearbeiten&aid='.$aid,
											'Ausschreibung bearbeiten',
											'Ausschreibung bearbeiten');
						
						$html .= ' ';
						$html .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),$bearb_args);
						
						// loeschen-link
						$loeschen_args = array('a',
											'ausschreibungen.php?id=ausschreibung&aktion=loeschen&aid='.$aid,
											'Ausschreibung l&ouml;schen',
											'Ausschreibung l&ouml;schen');
						
						$html .= ' ';
						$html .= $this->parse_wrapper($this->get_from_gc('a','wrapper'),$loeschen_args);
						
						// ausschreibungsdetails anzeigen
						// template-datei einlesen
						$fh = fopen($this->get_from_gc('detail_tpl_path','termin'),'r');
						$tpl = fread($fh,filesize($this->get_from_gc('detail_tpl_path','termin')));
						fclose($fh);
						
						// marker ersetzen
						$html .= $this->replace_marker($tpl,$ausschreibung,'html');
					}
				}
			break;
			
			case 'loeschen':
				
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
					// ausschreibung auslesen
					$sql = 'SELECT ausschreibungs_id
							FROM termin
							WHERE id="'.$this->get_get('tid').'"';
					// ausschreibungs_id zwischenspeichern
					$resultat = $db->query($sql);
					list($aid) = $resultat->fetch_array(MYSQL_NUM);
					$aid = (int) $aid;
					
					// ausschreibung loeschen
					if($aid != 0) {
						$sql = 'DELETE FROM ausschreibung
								WHERE id="'.$aid.'"';
						$resultat = $db->query($sql);

					}
					
					// termin loeschen
					$sql = 'DELETE FROM termin
							WHERE id="'.$this->get_get('tid').'"';
					
					// abfrage ausführen
					$resultat = $db->query($sql);

					
					// datenbank-objekt schliessen
					$db->close();
					
					$this->inhalt('Termin und Ausschreibung gel&ouml;scht.');
				} elseif(isset($_POST[$nein_name]) && $_POST[$nein_name] == $nein_wert) {
					
					// abbrechen
					// header senden
					header('Location: ausschreibungen.php?id='.$this->get_get('id'));
					exit;
				} else {
					
					// formular anzeigen
					// argumente vorbereiten
					$args = array(	'POST',
									'ausschreibungen.php?id='.$this->get_get('id').'&aktion=loeschen&tid='.$this->get_get('tid'),
									'Sind Sie sicher, dass Termin und Ausschreibung gel&ouml;scht werden soll?',
									$ja_name,
									$ja_wert,
									$nein_name,
									$nein_wert);
					
					// in wrapper einbetten
					$html .= $this->parse_wrapper($this->get_from_gc('best_form','wrapper'),$args);
				}
			break;

			default:
				$html .= $this->read_terminliste();
			break;
		}
		
		// rueckgabe
		return $html;
	}
	
	
	
	
	
	
	/**
	 * aid_ok prueft, ob aid angegeben und ob aid existiert
	 * 
	 * @param int $aid die zu pruefende aid
	 * @return bool true, wenn angegeben und existiert, false sonst
	 */
	private function tid_ok($tabelle,$tid) {
		
		// pruefen, ob aid gesetzt
		if(is_null($tid)) {
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
		$tids = array();
		while(list($id) = $resultat->fetch_array(MYSQL_NUM)) {
			
			$tids[] = $id;
		}
		
		// pruefen, ob aid in ausgelesenen aids
		if(!in_array($tid,$tids)) {
			return false;
		}
		
		// sonst ok
		return true;
	}
	
}



?>
