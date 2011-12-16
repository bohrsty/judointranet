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

class NBbenutzer extends NBobject {
	
	/*
	 * klassenvariablen
	 */
	private $name;
	private $login;
	private $passwort;
	private $gruppen;
	private $bezeichnung;
	private $adresse;
	private $posten;
	private $eingeloggt;
	private $formular;
	
	
	
	
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct() {
		
		// elter instanzieren
		parent::__construct();
		// klassenvariablen initialisieren
		$this->set_name('');
		$this->set_login('');
		$this->set_passwort('');
		$this->set_gruppen(array());
		$this->set_bezeichnung('');
		$this->set_adresse('');
		$this->set_posten('');
		$this->set_eingeloggt(false);
		$this->read_formular();
		$this->set_html('');
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	protected function get_name() {
		return $this->name;
	}
	protected function set_name($name){
		$this->name = $name;
	}
	
	public function get_login() {
		return $this->login;
	}
	protected function set_login($login){
		$this->login = $login;
	}
	
	protected function get_passwort() {
		return $this->passwort;
	}
	protected function set_passwort($passwort){
		$this->passwort = $passwort;
	}
	
	protected function get_gruppen() {
		return $this->gruppen;
	}
	protected function set_gruppen($gruppen){
		$this->gruppen = $gruppen;
	}
	
	public function get_bezeichnung() {
		return $this->bezeichnung;
	}
	protected function set_bezeichnung($bezeichnung){
		$this->bezeichnung = $bezeichnung;
	}
	
	protected function get_adresse() {
		return $this->adresse;
	}
	protected function set_adresse($adresse){
		$this->adresse = $adresse;
	}
	
	protected function get_posten() {
		return $this->posten;
	}
	protected function set_posten($posten){
		$this->posten = $posten;
	}
	
	protected function get_eingeloggt() {
		return $this->eingeloggt;
	}
	protected function set_eingeloggt($eingeloggt){
		$this->eingeloggt = $eingeloggt;
	}
	
	public function get_formular() {
		return $this->formular;
	}
	protected function set_formular($formular){
		$this->formular = $formular;
	}
	
	protected function get_html() {
		return $this->html;
	}
	public function set_html($html){
		$this->html = $html;
	}
	
	
	
	/*
	 * methoden
	 */
	/**
	 * instance_of den namen der klasse zurueck
	 * 
	 * @return string name der klasse
	 */
	protected function instance_of() {
		return 'NBbenutzer';
	}
	
	
	
	
	
	

	/**
	 * to_html gibt das objekt als html zurueck
	 * 
	 * @return string objekt als html
	 */
	public function to_html() {
		
		return $this->get_html();
	}
	
	
	
	
	
	

	/**
	 * check_login prueft, ob eingeloggt und gibt entsprechende meldungen aus
	 * 
	 * @return bool true oder false je nach ergebnis
	 */
	public function check_login() {
		
		// pruefen, ob eingeloggt
		if($this->get_eingeloggt() === true) {
			
			return true;
		} else {
			
			// formular anzeigen
			$this->set_html($this->get_formular()->to_html());
			
			// auf abgesendetes formular pruefen
			if(isset($_POST[$this->get_from_gc('submit_login_name','intern')]) && $_POST[$this->get_from_gc('submit_login_name','intern')] == $this->get_from_gc('submit_login_wert','intern')) {
				
				// formular pruefen
				// pruefen der eingaben
				$benutzername = $this->clean_wert($_POST['benutzername'],'userpass');
				$passwort = $this->clean_wert($_POST['passwort'],'userpass');
				if($benutzername === false || $passwort === false) {
					
					// ungueltige-zeichen-meldung
					$this->set_html($this->get_formular()->to_html());
					$this->put_login_meldung('<p class="message_error">Benutzername oder Passwort enthalten unerlaubte Zeichen oder sind leer!</p>');
					return false;
				} elseif($this->authentifizieren($benutzername,$passwort) !== true) {
					
					// benutzer nicht authentifiziert
					return false;
				} else {
					
					// benutzer erfolgreich authentifiziert
					$this->set_eingeloggt(true);
					return true;
				}
			} else {
				
				// std. meldung setzen
				$this->put_login_meldung($this->get_from_gc('std_login_meldung','global'));
				return false;
			}
		}
	}
	
	
	
	
	
	

	/**
	 * read_formular liest das template ein und erstellt das formular
	 */
	public function read_formular() {
		
		$this->set_formular(new NBform_login($this->get_from_gc('template_login','global'),$_SERVER['PHP_SELF']));
	}
	
	
	
	
	
	

	/**
	 * put_login_meldung setzt die an zu zeigende meldung im login formular
	 * 
	 * @param string $meldung zu setzende meldung
	 */
	public function put_login_meldung($meldung) {
		
		// html zwischenspeichern
		$temp = $this->get_html();
		
		// meldung einsetzen
		$temp = str_replace('###login_meldung###',$meldung,$temp);
		
		// html setzen
		$this->set_html($temp);;
	}
	
	
	
	
	
	

	/**
	 * authentifizieren liest den benutzer aus der datenbank und schreib ihn ins objekt
	 * wenn er existiert, aktiv ist und das passwort korrekt ist
	 * 
	 * @return bool true, wenn authentifiziert, sonst false
	 */
	private function authentifizieren($benutzername,$passwort) {
		
		// datenbankobjekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = 'SELECT *
				FROM benutzer
				WHERE login="'.$benutzername.'"
				LIMIT 1';
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		// resultat vorbereiten
		$id = 0;
		$bezeichnung = '';
		$name = '';
		$adresse = '';
		$posten = '';
		$email = '';
		$login = '';
		$db_passwort = '';
		$gruppen = '';
		$aktiv = '';
		
		// pruefen, ob betroffene reihen == 0
		if($resultat->num_rows == 0) {
			
			// fehlermeldung benutzer existiert nicht oder inaktiv
			$db->close();
			$this->put_login_meldung('<p class="message_error">Benutzer existiert nicht oder ist inaktiv!</p>');
			return false;
		} else {
			
			// benutzerdaten auslesen
			list($id,$bezeichnung,$name,$adresse,$posten,$email,$login,$db_passwort,$gruppen,$aktiv) = $resultat->fetch_array(MYSQL_NUM);
			
			// pruefen, ob aktiv
			if($aktiv == 0) {
				
				// fehlermeldung benutzer existiert nicht oder inaktiv
				$db->close();
				$this->put_login_meldung('<p class="message_error">Benutzer existiert nicht oder ist inaktiv!</p>');
				return false;
			} elseif($passwort !== $db_passwort) {
				
				// fehlermeldung benutzer und passwort pruefen
				$db->close();
				$this->put_login_meldung('<p class="message_error">Login fehlgeschlagen, bitte Benutzernamen und Passwort pr&uuml;fen!</p>');
				return false;
			}
		}
		
		// benutzer authentifiziert, daten in objekt schreiben
		$db->close();
		$this->set_adresse($adresse);
		$this->set_bezeichnung($bezeichnung);
		$this->set_login($login);
		$this->set_name($name);
		$this->set_passwort($db_passwort);
		$this->set_posten($posten);
		if(strpos($gruppen,',') === false) {
			$this->set_gruppen(array($gruppen));
		} else {
			$this->set_gruppen(explode(',',$gruppen));
		}
		return true;
	}
	
	
	
	
	
	

	/**
	 * in_gruppe prueft, ob der eingeloggte benutzer in einer der angegebenen gruppen ist
	 * 
	 * @param array $gruppen array mit den zu ueberpruefenden gruppen
	 * @return bool true, wenn in einer der gruppen, false sonst
	 */
	public function in_gruppe($gruppen) {
		
		// fuer alle (1 in $gruppen)
		if(in_array('1',$gruppen)) {
			return true;
		}
		// schnittmenge aus den arrays bilden
		if(count(array_intersect($this->get_gruppen(),$gruppen)) == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	
	
	
	
	

	/**
	 * reset_benutzer setzt das objekt auf anfang zurueck (z.b. logout)
	 */
	public function reset_benutzer() {
		
		// klassenvariablen zuruecksetzen
		$this->set_name('');
		$this->set_login('');
		$this->set_passwort('');
		$this->set_gruppen(array());
		$this->set_bezeichnung('');
		$this->set_adresse('');
		$this->set_posten('');
		$this->set_eingeloggt(false);
		$this->read_formular();
		$this->set_html('');
	}
	
	
	
	
	
	

	/**
	 * check_zugriff prueft anhand der gruppenzugehoerigkeit, ob der eingeloggte benutzer zugriff
	 * auf die angeforderte seite hat
	 * 
	 * @param string $seite aktuelle seite, die geprueft werden soll
	 * @param string $id aktuelle id, die geprueft werden soll
	 * @return bool true, wenn zugriff, false sonst
	 */
	public function check_zugriff($seite,$id) {
		
		// datenbankobjekt holen
		$db = new NBdb();
		
		// abfrage vorbereiten
		$sql = '	SELECT gruppen
					FROM navi
					WHERE datei="'.$seite.'" AND link_id="'.$id.'"';
		
		// abfrage ausfuehren
		$resultat = $db->query($sql);
		
		// datenbankobjekt schliessen
		$db->close();
		
		// ergebnis speichern
		list($db_gruppen) = $resultat->fetch_array(MYSQL_NUM);
		
		// gruppen trennen falls mehrere
		$gruppen = array();
		if(strpos($db_gruppen,',') === false) {
			
			// nur eine
			$gruppen = array($db_gruppen);
		} else {
			
			// gruppen trennen
			$gruppen = explode(',',$db_gruppen);
		}
		
		// gruppenzugehoerigkeit pruefen und ergebnis zurueckgeben
		return $this->in_gruppe($gruppen);
	}
	
}



?>
