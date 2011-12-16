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

class NBmain extends NBobject {
	
	/*
	 * klassenvariablen
	 */
	private $navi;
	private $inhalt;
	private $template;
	private $inhalt_fest;
	private $titel;
	private $getids;
	private $get;
	
	
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct() {
		
		// klassenvariablen initialisieren
		$this->set_titel('');
		$this->read_template();
		$this->set_inhalt_fest(false);
		$this->set_getids(array());
		$this->read_get();
		$this->set_navi(new NBnavi());
		
		// login abfragen
		if(!isset($_SESSION['benutzer'])) {
			$_SESSION['benutzer'] = new NBbenutzer();
		}
		
		// pruefen, ob eingeloggt
		if($_SESSION['benutzer']->check_login() === true) {
						
			if($this->get_get('id') === false) {
				$this->get_navi()->set_seite(null);
				$this->set_inhalt('');
				$this->inhalt($this->read_error('url_param_not_valid'));
				$this->set_inhalt_fest(true);
			} elseif( $this->get_get('id') == 'logout') {
				$_SESSION['benutzer']->reset_benutzer();
				$_SESSION['benutzer']->set_html($_SESSION['benutzer']->get_formular()->to_html());
				$_SESSION['benutzer']->put_login_meldung('Erfolgreich ausgeloggt.');
				$this->get_navi()->set_seite(null);
				$this->set_inhalt($_SESSION['benutzer']->to_html());
				$this->set_inhalt_fest(true);
			} else {
				$this->get_navi()->set_seite($this->get_get('id'));
				$this->set_inhalt('');
			}
		} else {
			
			$this->set_inhalt($_SESSION['benutzer']->to_html());
			$this->set_inhalt_fest(true);
		}
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	protected function get_navi() {
		return $this->navi;
	}
	protected function set_navi($navi){
		$this->navi = $navi;
	}
	
	protected function get_inhalt() {
		return $this->inhalt;
	}
	protected function set_inhalt($inhalt){
		$this->inhalt = $inhalt;
	}
	
	protected function get_template() {
		return $this->template;
	}
	protected function set_template($template){
		$this->template = $template;
	}
	
	protected function get_inhalt_fest() {
		return $this->inhalt_fest;
	}
	protected function set_inhalt_fest($inhalt_fest){
		$this->inhalt_fest = $inhalt_fest;
	}
	
	protected function get_titel() {
		return $this->titel;
	}
	public function set_titel($titel){
		$this->titel = $this->get_from_gc('title','global').$titel;
	}
	
	protected function get_getids() {
		return $this->getids;
	}
	protected function set_getids($getids){
		$this->getids = $getids;
	}
	
	protected function get_get($param = '') {
		if($param == '') {
			return $this->get;
		} else {
			if(isset($this->get[$param])) {
				return $this->get[$param];
			} else {
				return null;
			}
		}
	}
	protected function set_get($get){
		$this->get = $get;
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
		return 'NBmain';
	}
	
	
	
	
	
	

	/**
	 * read_template liest das template aus der datei ein und speichert
	 * es als string in der klassenvariable
	 */
	protected function read_template() {
		
		// aus datei einlesen
		$pfad = $this->get_from_gc('template_path','global');
		$fh = fopen($pfad,'r');
		
		$tpl = fread($fh,filesize($pfad));
		
		fclose($fh);
		
		// template setzen
		$this->set_template($tpl);
	
	}
	
	
	
	
	
	

	/**
	 * inhalt haengt den uebergebenen inhalt an
	 * 
	 * @param string $inhalt der anzuhaengende inhalt
	 */
	public function inhalt($inhalt) {
		
		// inhalt zwischenspeichern
		$temp = $this->get_inhalt();
		
		// neuen inhalt anhaengen, wenn kein error
		if($this->get_inhalt_fest() === false) {
			$this->set_inhalt($temp.$inhalt);
		}
	
	}
	
	
	
	
	
	

	/**
	 * to_html gibt das template mit den eingebetteten inhalten
	 */
	public function to_html() {
		
		// zugriff pruefen
		if($_SESSION['benutzer']->check_zugriff(basename($_SERVER['PHP_SELF']),$this->get_get('id'))) {

			// getids trennen
			// auf festgelegten inhalt pruefen
			if($this->get_inhalt_fest() === false && $this->get_get('id') != '') {
				$this->set_inhalt('');
				$this->read_inhalt($this->get_get('id'));
			}
		} else {
			
			// fehlermeldung
			$this->set_inhalt($this->read_error('no_accessrights'));
		}
		
		// template zwischenspeichern
		$tpl = $this->get_template();
		
		// inhalte einbetten
		$tpl = str_replace('###title###',$this->get_titel(),$tpl);
		$tpl = str_replace('###navi###',$this->get_navi()->to_html(),$tpl);
		$tpl = str_replace('###content###',$this->get_inhalt(),$tpl);
		
		// ausgabe
		print $tpl;
		
		// bei logout session beenden
		if(!is_null($this->get_get('id')) && $this->get_get('id') == 'logout') {
			session_destroy();
		}
	}
	
	
	
	
	
	

	/**
	 * getids haengt die inhalte oder funktionen fuer die getids an
	 * 
	 * @param string $getid id aus $_GET
	 * @param string $funktion name der aus zu fuehrenden funktion
	 * @param array $parameter array aus den notwendigen parametern der funktion
	 */
	public function getids($getid,$funktion,$parameter) {
		
		// $getids[$getid][$funktion][$parameter][0]
		// funktionsname und parameter auslesen und an getids anhaengen
		$temp = $this->get_getids();
		$temp[$getid] = array('funktion' => $funktion,'parameter' => $parameter);
		$this->set_getids($temp);
	}
	
	
	
	
	
	

	/**
	 * read_inhalt fuehrt die in $getids registrierte funktion aus und haengt das ergebnis
	 * an $inhalt an
	 * 
	 * @param string $getid id aus $_GET
	 */
	protected function read_inhalt($getid) {
		
		// pruefen, ob $getid leer und $getid angemeldet
		$temp = $this->get_getids();
		if($getid != '' && isset($temp[$getid])) {
			// aufruf vorbereiten
			$param1 = array($this,$temp[$getid]['funktion']);
			$param2 = $temp[$getid]['parameter'];
			
			// funktion aufrufen
			$ergebnis = call_user_func_array($param1,$param2);
			
			// inhalt anhaengen
			$this->inhalt($ergebnis);
		}
	}
	
	
	
	
	
	
	/**
	 * read_get prueft alle werte von $_GET und speichert sie in get
	 */
	private function read_get() {
		
		// $_GET durchlaufen, wenn gesetzt
		$get = array();
		if(isset($_GET)) {
			
			foreach($_GET as $schluessel => $wert) {
				
				$get[$schluessel] = $this->clean_wert($wert,'urlparam');
			}
		}
		
		// get setzen
		$this->set_get($get);
	}
	
}



?>
