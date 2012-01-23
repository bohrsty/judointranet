<?php

/*
 * language-definition for de_DE
 * 
 * format is an array in format:
 * $lang = array('<filename>' => 
 * 				array('<method>' => 
 * 					array('<"bereich">' => 
 * 						array(
 * 							'<name>' => '<value>'
 * ))))
 */

$lang = array(
	'class.Error' => array(
		'handle_error' => array(
			'GETInvalidChars' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Der Link enth&auml;lt ung&uuml;ltige Zeichen, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'POSTInvalidChars' => array(
				'ERROR.message' => 'Dieses Feld enh&auml;lt ung&uuml;ltige Zeichen'
			),
			'ReadTemplateFile' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Ein ben&ouml;tigtes Template kann nicht geladen werden, bitte wenden Sie sich an den Systembetreuer.'
			),
			'GETUnknownId' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Der Link enth&auml;lt einen ung&uuml;ltigen Parameter, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'CannotGetNavi' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Ein ben&ouml;tigtes Navigationselement kann nicht geladen werden, bitte wenden Sie sich an den Systembetreuer.'
			),
			'NotAuthorized' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Sie sind nicht berechtigt diese Seite an zu zeigen, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'DbActionUnknown' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Der Eintrag konnte nicht gespeichert werden, bitte probieren Sie es erneut oder wenden sich an Ihre Systembetreuer.'
			)
		
		)
	
	),
	'class.CalendarView' => array(
		'listall' => array(
			'TH' => array(
				'date' => 'Datum',
				'name' => 'Veranstaltung'
			)
		),
		'init' => array(
			'listall' => array(
				'title' => 'Kalender: Listenansicht'
			),
			'default' => array(
				'title' => 'Kalender'
			),
			'Error' => array(
				'NotAuthorized' => 'FEHLER - Nicht berechtigt'
			),
			'new' => array(
				'title' => 'Kalender: Neuer Eintrag'
			),
			'details' => array(
				'title' => 'Kalender: Termindetails'
			)
		),
		'connectnavi' => array(
			'firstlevel' => array(
				'name' => 'Kalender'
			),
			'secondlevel' => array(
				'listall' => 'Listenansicht',
				'new' => 'Neuen Eintrag erstellen'
			)
		),
		'get_date_links' => array(
			'dates' => array(
				'next_day' => 'Morgen',
				'next_week' => 'n&auml;chste Woche',
				'two_weeks' => 'n&auml;chste zwei Wochen',
				'next_month' => 'n&auml;chster Monat',
				'half_year' => 'n&auml;chstes halbes Jahr',
				'next_year' => 'n&auml;chstes Jahr'
			),
			'alt' => array(
				'next_day' => 'Morgen',
				'next_week' => 'n&auml;chste Woche',
				'two_weeks' => 'n&auml;chste zwei Wochen',
				'next_month' => 'n&auml;chster Monat',
				'half_year' => 'n&auml;chstes halbes Jahr',
				'next_year' => 'n&auml;chstes Jahr'
			)
		),
		'new_entry' => array(
			'form' => array(
				'requiredNote' => '<span class="required">*</span> erforderliches Feld',
				'date' => 'Datum',
				'submitButton' => 'Speichern',
				'name' => 'Name',
				'shortname' => 'Kurzbezeichnung',
				'type' => 'Veranstaltungstyp',
				'rights' => 'Berechtigte Gruppen (mehrfache Auswahl: &lt;STRG&gt; gedr&uuml;ckt halten und Gruppen ausw&auml;hlen)',
				'entry_content' => 'Inhalt/Beschreibung'
			),
			'rule' => array(
				'required.date' => 'Datum muss ausgew&auml;hlt werden!',
				'check.date' => 'Korrektes Datum muss ausgew&auml;hlt werden!',
				'required.name' => 'Name muss eingetragen werden!',
				'required.type' => 'Typ muss ausgew&auml;hlt werden!',
				'check.select' => 'Feld muss ausgew&auml;ht werden!',
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!'
			),
			'date' => array(
				'month.1' => 'Jan',
				'month.2' => 'Feb',
				'month.3' => 'M&auml;r',
				'month.4' => 'Apr',
				'month.5' => 'Mai',
				'month.6' => 'Jun',
				'month.7' => 'Jul',
				'month.8' => 'Aug',
				'month.9' => 'Sep',
				'month.10' => 'Okt',
				'month.11' => 'Nov',
				'month.12' => 'Dez'
			)
		)
	),
	'class.PageView' => array(
		'title' => array(
			'prefix' => array(
				'title' => 'BfV-Intranet'
			)
		),
		'navi' => array(
			'secondlevel' => array(
				'login' => 'Login',
				'logout' => 'Logout'
			)
		),
		'PageView' => array(
			'logininfo' => array(
				'NotLoggedin' => 'Nicht angemeldet.',
				'LoggedinAs' => 'Angemeldet als:'
			)
		)
	),
	'class.MainView' => array(
		'connectnavi' => array(
			'firstlevel' => array(
				'name' => 'Startseite'
			)
		),
		'init' => array(
			'login' => array(
				'title' => 'Login'
			),
			'logout' => array(
				'title' => 'Logout'
			),
			'Error' => array(
				'NotAuthorized' => 'FEHLER - Nicht berechtigt'
			),
			'default' => array(
				'title' => 'Startseite'
			)
		),
		'login' => array(
			'form' => array(
				'requiredNote' => '<span class="required">*</span> erforderliches Feld',
				'username' => 'Benutzername',
				'password' => 'Passwort',
				'loginButton' => 'Anmelden'
			),
			'rule' => array(
				'required.username' => 'Der Benutzername darf nicht leer sein!',
				'required.password' => 'Das Passwort darf nicht leer sein!'
			),
			'message' => array(
				'caption' => 'Login',
				'UserNotExist' => 'Der angegebene Benutzername existiert nicht.',
				'UserNotActive' => 'Dieser Benutzer ist nicht aktiv.',
				'WrongPassword' => 'Falsches Passwort.'
			)
		),
		'callback_check_login' => array(
			'message' => array(
				'UserNotExist' => 'Der angegebene Benutzername existiert nicht.',
				'UserNotActive' => 'Dieser Benutzer ist nicht aktiv.',
				'WrongPassword' => 'Falsches Passwort.'
			)
		)
	),
	'class.User' => array(
		'logout' => array(
			'logout' => array(
				'caption' => 'Logout',
				'message' => 'Sie haben sich erfolgreich abgemeldet.'
			)
		),
		'login' => array(
			'message' => array(
				'default' => 'Bitte einloggen',
			)
		),
		'return_all_groups' => array(
			'rights' => array(
				'public.access' => 'Alle (&ouml;ffentlicher Zugriff)'
			)
		)
	),
	'class.Calendar' => array(
		'return_types' => array(
			'type' => array(
				'name.event' => 'Turnier/Meisterschaft'
			)
		),
		'details_to_html' => array(
			'data' => array(
				'name' => '<span>Veranstaltung:</span><br />',
				'shortname' => '<span>Kurzname:</span><br />',
				'date' => '<span>Datum:</span><br />',
				'type' => '<span>Art:</span><br />',
				'content' => '<span>Beschreibung:</span><br />',
				'rights' => '<span>Berechtigte Gruppen:</span><br />'
			)
		)
	)
);


?>
