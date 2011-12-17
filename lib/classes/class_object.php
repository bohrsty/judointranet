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

class NBobject {
	
	/*
	 * klassenvariablen
	 */
	
	
	/*
	 * konstruktor der klasse
	 */
	public function __construct() {
		
		// klassenvariablen initialisieren
		
		
	}
	
	
	
	
	/*
	 * get- und set-methoden
	 */
	
	
	
	
	/*
	 * methoden
	 */
	/**
	 * get_from_gc gibt den wert der uebergebenen konfigurationseinstellung zurueck
	 * 
	 * @param string $konfig zurueck zu gebende konfigurationseinstellung
	 * @param string $bereich abschnitt der ini-datei
	 * @param bool $aus_db true = aus der datenbank lesen, false = aus der session
	 * @return string wert der konfigurationseinstellung
	 */
	protected function get_from_gc($konfig,$bereich,$aus_db=false) {
		
		// pruefen, ob aus datenbank oder session
		if($aus_db === false) {
			
			// aus session zurueckgeben
			return $_SESSION['GC'][$bereich][$konfig];
		} else {
			
			// aus datenbank zurueckgeben
			// datenbank-objekt holen
			$db = new NBdb();
			
			// abfrage vorbereiten
			$sql = 'SELECT wert
					FROM konfig
					WHERE name="'.$konfig.'"
					AND bereich="'.$bereich.'"';
			
			// abfrage ausfuehren
			$resultat = $db->query($sql);
			
			// datenbank-objekt schliessen
			$db->close();
			
			// resultat verarbeiten
			list($wert) = $resultat->fetch_array(MYSQL_NUM);
			
			// rueckgabe
			return $wert;
		}
		
	}
	
	
	
	
	
	
	
	/**
	 * parse_wrapper bettet die im array uebergebenen werte in den wrapper ein
	 * und gibt ihn als string zurueck
	 * 
	 * @param string $wrapper der zu parsende wrapper
	 * @param array $werte der array mit den zu setzenden werten in der korrekten reihenfolge
	 * @return string der wrapper mit den eingebetten werten
	 */
	protected function parse_wrapper($wrapper,$werte) {
		
		// wrapper trennen
		$felder = explode('|',$wrapper,count($werte)+1);
		
		// array durchlaufen und string zusammenfuegen
		$string = '';
		for($i=0;$i<count($werte);$i++) {
			
			$string .= $felder[$i] . $werte[$i];
		}
		$string .= $felder[count($werte)];
		
		// rueckgabe
		return $string;
	}
	
	
	
	
	
	
	/**
	 * instance_of den namen der klasse zurueck
	 * 
	 * @return string name der klasse
	 */
	protected function instance_of() {
		return 'NBobject';
	}
	
	
	
	
	
	
	/**
	 * clean_wert prueft den uebergebenen wert entsprechend dem ueberegebenen typ durch
	 * regexp
	 * 
	 * @param mixed $wert zu pruefender wert
	 * @param string $typ typ des wertes, anhand dessen der regexp bestimmt wird
	 * @return mixed false, wenn wert nicht korrekt, sonst den wert
	 */
	protected function clean_wert($wert,$typ) {
		
		// regexps
		$regexp = array(
							'userpass' => '/^[a-zA-Z0-9\.\-_]+$/',
	  						'urlparam' => '/^[a-zA-Z0-9\.\-_\+]*$/',
	  						'postwert' => '{^[a-zA-Z0-9äöüÄÖÜß\.\-_\+!§\$%&/()=`´;:\*#~\?ß<>| ]*$}',
	  						'text' => '{^[a-zA-Z0-9äöüÄÖÜß\.,\-_\+!§\$%&/()=`´;:\*#~\?ß<>| ]*$}',
	  						'text_nt' => '{^[a-zA-Z0-9äöüÄÖÜß\.,\-_\+!§\$%&/()=`´;:\*#~\?ß<>| \n\r\t]*$}s',
	  						'datum' => '/^[0123]?\d\.[012]?\d\.\d{4}$/',
	  						'zahl' => '/^[0-9]*$/'
		);
	  
		// pruefen, ob nicht erlaubte zeichen
		if(!preg_match($regexp[$typ],$wert)) {
	
			// wert nicht konform
			return false;
		} else {
	
			// parameter zurueckgeben
			return $wert;
		}
	}
	
	
	
	
	
	
	/**
	 * read_errormsg bettet die fehlermeldung in ein img-tag ein und gibt es als
	 * string zurueck
	 * 
	 * @param string $msg die an zu zeigende fehlermeldung
	 * @return string die fehlermeldung als html-string
	 */
	protected function read_errormsg($msg) {
		
		// argumente vorbereiten
		$args = array($msg,$msg);
		
		// in wrapper einbetten
		return $this->parse_wrapper($this->get_from_gc('img_errormsg','wrapper'),$args);
	}
	
	
	
	
	
	
	/**
	 * read_error gibt die fehlermeldung nach uebergebenem typ aus
	 * 
	 * @param string $type typ der auszugebenden fehlermeldung
	 * @return string fehlermeldung als html fuer $content
	 */
	protected function read_error($type) {
    
		// rueckgabe vorbereiten
		$meldung = '';
		// nach $type trennen
		switch($type) {
  
			case 'url_param_not_valid':
				$meldung .= 'Link enth&auml;lt ung&uuml;ltige Zeichen, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />';
				$meldung .= 'Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />';
				$meldung .= 'Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />';
			break;
			
			case 'link_not_valid':
				$meldung .= 'Der Link ist ung&uuml;ltig, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />';
				$meldung .= 'Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />';
				$meldung .= 'Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />';
			break;

			case 'not_logged_in':
				$meldung .= 'Diese Seite kann nicht aufgerufen werden, wenn Sie nicht eingelogged sind, bitte einloggen.<br />';
			break;
    
			case 'no_accessrights':
				$meldung .= 'Sie sind nicht berechtig diese Seite an zu zeigen, falls diese Meldung &uuml;beraschend angezeigt wird, wenden Sie sich bitte an Ihren Systembetreuer.<br />';
			break;
    
			case 'tid_not_found':
				$meldung .= 'Der angeforderte Termin existiert nicht.<br />';
			break;
    
			default:
				$meldung .= 'Es ist ein allgemeiner Fehler aufgetreten, wenden Sie sich bitte an den Systembetreuer.<br />';
			break;
		}

		// fehlertyp anhaengen
		$meldung .= '[Error: "' . $type . '"]';

		$meldung_args = array($meldung);
		$return = $this->parse_wrapper($this->get_from_gc('error_msg','wrapper'),$meldung_args);

		// rueckgabe
		return $return;
	}







	/**
	 * replace_marker ersetzt die marker der ausschreibung in einem string durch die
	 * entsprechungen aus der datenbank
	 * 
	 * @param $string string in dem die marker ersetzt werden sollen
	 * @param $array array mit den ein zu setzenden werten
	 * @param $typ art der ersetzung
	 * @return (string) string in dem die marker ersetzt wurden
	 */
	protected function replace_marker($string,$array,$typ='text') {
		
		// keys auslesen
		$keys = array_keys($array);
		
		// schleife zum ersetzen
		for($i=0;$i<count($keys);$i++) {
			
			// nur ersetzen wenn wert vorhanden
			if(isset($array[$keys[$i]])) {
				
				// trennen nach typ
				switch($typ) {
					
					case 'html':
//						$string = str_replace('###'.$keys[$i].'###',nl2br($this->replace_umlaute(htmlspecialchars(utf8_encode($array[$keys[$i]])))),$string);
						$string = str_replace('###'.$keys[$i].'###',nl2br($this->replace_umlaute(htmlspecialchars($array[$keys[$i]]))),$string);
					break;
					case 'pdml':
						$string = str_replace('###'.$keys[$i].'###',nl2br($array[$keys[$i]]),$string);
					break;
					case 'html2pdf':
						$string = str_replace('###'.$keys[$i].'###',nl2br($array[$keys[$i]]),$string);
					break;
					case 'text':
					default:
						$string = str_replace('###'.$keys[$i].'###',$array[$keys[$i]],$string);
					break;
				}
			}
		}
			
		// rueckgabe
		return $string;
	}







	/**
	 * replace_umlaute ersetzt die umlaute in einem string durch die html-entsprechungen
	 * 
	 * @param $string string in dem die umlaute ersetzt werden sollen
	 * @return (string) string in dem die umlaute ersetzt wurden
	 */
	protected function replace_umlaute($string) {
		
		// umlaute ersetzen
		$string = str_replace('ä','&auml;',$string);
		$string = str_replace('Ä','&Auml;',$string);
		$string = str_replace('ö','&ouml;',$string);
		$string = str_replace('Ö','&Ouml;',$string);
		$string = str_replace('ü','&uuml;',$string);
		$string = str_replace('Ü','&Uuml;',$string);
		$string = str_replace('ß','&szlig;',$string);
		
		// rueckgabe
		return $string;
	}
	
	
	
}



?>
