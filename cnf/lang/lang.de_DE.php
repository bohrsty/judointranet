<?php

/*
 * language-definition for de_DE
 * 
 * format is an array in format:
 * $lang = array(
 * 				'<filename>' => array(
 * 					'<method>' => array(
 * 						'<scope>' => array(
 * 							'<name>' => '<value>'
 * 						)
 * 					)
 * 				)
 * 			)
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
			),
			'CidNotExists' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Der Termin existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'NotOwned' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Sie sind nicht Eigent&uuml;mer dieses Objekts, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'NotGiven' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Sie haben dieses Objekts nicht abgegeben, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'NotGivenTo' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Dieses Objekts wurde nicht abgegeben, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'MissingParams' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Es fehlen Parameter, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'WrongParams' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Es wurden falsche Parameter &uuml;bergeben, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'AnnNotExists' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'F&uuml;r diesen Termin wurde keine Ausschreibung angelegt, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'UsertableNotExists' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Diese Tabelle existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'RowNotExists' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Diese Tabellenzeile existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			)
		
		)
	
	),
	'class.CalendarView' => array(
		'listall' => array(
			'TH' => array(
				'date' => 'Datum',
				'name' => 'Veranstaltung',
				'show' => 'Ansehen',
				'admin' => 'Aufgaben'
			),
			'alt' => array(
				'edit' => 'bearbeiten',
				'delete' => 'l&ouml;schen',
				'AnnEdit' => 'Ausschreibung bearbeiten',
				'AnnDelete' => 'Ausschreibung l&ouml;schen',
				'AnnDetails' => 'Ausschreibung anzeigen',
				'AnnPDF' => 'Ausschreibung als PDF anzeigen'
			),
			'title' => array(
				'edit' => 'bearbeitet diesen Eintrag',
				'delete' => 'l&ouml;scht diesen Eintrag',
				'AnnEdit' => 'bearbeitet die Ausschreibung',
				'AnnDelete' => 'l&ouml;scht die Ausschreibung',
				'AnnDetails' => 'Ausschreibung anzeigen',
				'AnnPDF' => 'Ausschreibung als PDF anzeigen'
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
			),
			'edit' => array(
				'title' => 'Kalender: Termin bearbeiten'
			),
			'delete' => array(
				'title' => 'Kalender: Termin l&ouml;schen'
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
		'get_sort_links' => array(
			'dates' => array(
				'next_day' => 'Morgen',
				'next_week' => 'n&auml;chste Woche',
				'two_weeks' => 'n&auml;chste zwei Wochen',
				'next_month' => 'n&auml;chster Monat',
				'half_year' => 'n&auml;chstes halbes Jahr',
				'next_year' => 'n&auml;chstes Jahr'
			),
			'reset' => array(
				'all' => 'Alle Filter zur&uuml;cksetzen',
				'date' => 'Datumsfilter zur&uuml;cksetzen',
				'groups' => 'Gruppenfilter zur&uuml;cksetzen'
			),
			'title' => array(
				'next_day' => 'Morgen',
				'next_week' => 'n&auml;chste Woche',
				'two_weeks' => 'n&auml;chste zwei Wochen',
				'next_month' => 'n&auml;chster Monat',
				'half_year' => 'n&auml;chstes halbes Jahr',
				'next_year' => 'n&auml;chstes Jahr',
				'resetAll' => 'Alle Filter zur&uuml;cksetzen',
				'resetDate' => 'Datumsfilter zur&uuml;cksetzen',
				'resetGroups' => 'Gruppenfilter zur&uuml;cksetzen'
			),
			'toggleFilter' => array(
				'title' => 'Filter ein- oder ausblenden',
				'name' => 'Filter ein- oder ausblenden'
			)
		),
		'entry' => array(
			'form' => array(
				'requiredNote' => '<span class="required">*</span> erforderliches Feld',
				'date' => 'Datum',
				'submitButton' => 'Speichern',
				'name' => 'Name',
				'shortname' => 'Kurzbezeichnung',
				'type' => 'Veranstaltungstyp',
				'rights' => 'Berechtigte Gruppen (mehrfache Auswahl: &lt;STRG&gt; gedr&uuml;ckt halten und Gruppen ausw&auml;hlen)',
				'entry_content' => 'Inhalt/Beschreibung',
				'announcement' => 'Ausschreibung'
			),
			'rule' => array(
				'required.date' => 'Datum muss ausgew&auml;hlt werden!',
				'check.date' => 'Korrektes Datum muss ausgew&auml;hlt werden!',
				'required.name' => 'Name muss eingetragen werden!',
				'required.type' => 'Typ muss ausgew&auml;hlt werden!',
				'check.select' => 'Feld muss ausgew&auml;ht werden!',
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!'
			)
		),
		'delete' => array(
			'form' => array(
				'yes' => 'Ja',
				'cancel' => 'abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie diesen Eintrag wirklich l&ouml;schen?',
				'done' => 'Der Eintrag wurde erfolgreich gel&ouml;scht.'
			),
			'title' => array(
				'cancel' => 'Bricht den L&ouml;schvorgang ab'
			)
		),
		'read_preset_form' => array(
			'select' => array(
				'choosePreset' => 'Ausschreibungsvorlage ausw&auml;hlen',
				'submit' => '+'
			),
			'rule' => array(
				'select' => 'Bitte eine Vorlage ausw&auml;hlen'
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
		'put_userinfo' => array(
			'logininfo' => array(
				'NotLoggedin' => 'Nicht angemeldet.',
				'LoggedinAs' => 'Angemeldet als:',
				'toggleUsersettings' => 'Benutzereinstellungen ein-/ausblenden'
			),
			'usersettings' => array(
				'passwd' => 'Kennwort &auml;ndern',
				'passwd.title' => 'Kennwort &auml;ndern',
				'logout' => 'Logout',
				'logout.title' => 'Logout'
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
			),
			'user' => array(
				'title' => 'Benutzereinstellungen'
			),
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
		),
		'user' => array(
			'caption' => array(
				'general' => 'Benutzereinstellungen f&uuml;r',
				'passwd' => 'Kennwort &auml;ndern'
			),
			'passwd' => array(
				'label' => 'Neues Kennwort und Wiederholung eingeben',
				'submitButton' => 'Kennwort ändern'
			),
			'form' => array(
				'requiredNote' => '<span class="required">*</span> erforderliches Feld'
			),
			'rule' => array(
				'required' => 'Kennwort muss ausgef&uuml;llt werden!',
				'checkPasswd' => 'Die Eingaben m&uuml;ssen identisch sein!'
			),
			'validate' => array(
				'passwdChanged' => 'Das Kennwort wurde erfolgreich ge&auml;ndert.'
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
				'name.event' => 'Turnier/Meisterschaft',
				'name.training' => 'Lehrgang'
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
	),
	'class.Field' => array(
		'element' => array(
			'rule' => array(
				'required.date' => 'Datum muss ausgew&auml;hlt werden!',
				'check.date' => 'Korrektes Datum muss ausgew&auml;hlt werden!',
				'required.text' => 'Feld muss ausgef&uuml;llt werden!',
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
				'required.checkbox' => 'Auswahlfeld muss angehakt werden!'
			),
			'label' => array(
				'textarea.manual' => 'eintragen...',
				'textarea.defaults' => '...oder ausw&auml;hlen'
			)
		),
		'value_to_html' => array(
			'checkbox.value' => array(
				'checked' => 'Ja',
				'unchecked' => 'Nein'
			)
		),
		'read_defaults' => array(
			'defaults' => array(
				'separator' => 'Vorgaben'
			),
			'lastUsed' => array(
				'separator' => 'zuletzt verwendet'
			)
		),
		'entry' => array(
			'rule' => array(
				'check.select' => 'Feld muss ausgew&auml;ht werden!',
				'check.hierselect' => 'Felder m&uuml;ssen ausgew&auml;ht werden!'
			)
		)
	),
	'class.AnnouncementView' => array(
		'connectnavi' => array(
			'firstlevel' => array(
				'name' => 'Ausschreibungen'
			),
			'secondlevel' => array(
				'listall' => 'Listenansicht',
				'new' => 'Neuen Eintrag erstellen',
				'delete' => 'Eintrag l&ouml;schen',
				'details' => 'Details',
				'topdf' => 'Eintrag als PDF'
			)
		),
		'init' => array(
			'new' => array(
				'title' => 'Ausschreibung: Neuer Eintrag'
			),
			'listall' => array(
				'title' => 'Ausschreibung: Listenansicht'
			)
		),
		'entry' => array(
			'form' => array(
				'requiredNote' => '<span class="required">*</span> erforderliches Feld'
			)
		),
		'new_entry' => array(
			'form' => array(
				'submitButton' => 'Speichern'
			)
		),
		'edit' => array(
			'form' => array(
				'submitButton' => 'Speichern'
			)
		),
		'delete' => array(
			'form' => array(
				'yes' => 'Ja',
				'cancel' => 'abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie diesen Eintrag wirklich l&ouml;schen?',
				'done' => 'Der Eintrag wurde erfolgreich gel&ouml;scht.'
			),
			'title' => array(
				'cancel' => 'Bricht den L&ouml;schvorgang ab'
			)
		)
	),
	'class.InventoryView' => array(
		'connectnavi' => array(
			'firstlevel' => array(
				'name' => 'Inventar'
			),
			'secondlevel' => array(
				'listall' => 'Listenansicht',
				'my' => 'Eigene verwalten',
				'give' => 'Objekt abgeben',
				'take' => 'Objekt annehmen',
				'cancel' => 'Objekt zur&uuml;ckziehen',
				'details' => 'Details',
				'movement' => '&Uuml;bergaben'
			)
		),
		'init' => array(
			'my' => array(
				'title' => 'Inventar: Eigene Objekte'
			),

			'default' => array(
				'title' => 'Inventar'
			),
			'Error' => array(
				'NotAuthorized' => 'FEHLER - Nicht berechtigt'
			),
			'give' => array(
				'title' => 'Inventar: Objekt abgeben'
			),
			'take' => array(
				'title' => 'Inventar: Objekt annehmen'
			),
			'cancel' => array(
				'title' => 'Inventar: Objekt zur&uuml;ckziehen'
			),
			'details' => array(
				'title' => 'Inventar: Details'
			),
			'movement' => array(
				'title' => 'Inventar: &Uuml;bergaben'
			)
		),
		'my' => array(
			'TH' => array(
				'name' => 'Objekt',
				'number' => 'Inventarnummer',
				'admin' => 'Aufgaben'
			),
			'title' => array(
				'give' => 'Objekt abgeben',
				'take' => 'Objekt annehmen',
				'cancel' => 'Objekt zur&uuml;ckziehen',
				'details' => 'Details'
			),
			'content' => array(
				'give' => 'abgeben',
				'take' => 'annehmen',
				'cancel' => 'zur&uuml;ckziehen'
			)
		),
		'give' => array(
			'form' => array(
				'submitButton' => 'Speichern'
			),
			'page' => array(
				'headline' => 'Objekt abgeben',
				'objectinfo.head' => '',
				'objectinfo.tail' => ' abgeben an',
				'accessory.required' => 'Bitte unbedingt das zu &uuml;bergebende Zubeh&ouml;r anhaken, sonst muss davon ausgegangen werden, dass es einbehalten wurde und kostenpflichtig ersetzt werden muss!',
				'headline.givento' => ' abgegeben an ',
				'accessory.given' => 'Zu &uuml;bergebendes Zubeh&ouml;r:'
			)
		),
		'take' => array(
			'form' => array(
				'submitButton' => 'Speichern'
			),
			'page' => array(
				'headline' => 'Objekt annehmen',
				'headline.taken' => '&uuml;bernommen',
				'accessory.required' => 'Bitte unbedingt das &uuml;bernommene Zubeh&ouml;r anhaken, sonst muss davon ausgegangen werden, dass es einbehalten wurde und kostenpflichtig ersetzt werden muss!',
				'accessory.taken' => '&Uuml;bernommenes Zubeh&ouml;r:',
				'TakeFrom' => '&Uuml;bernommen von'
			)
		),
		'entry' => array(
			'form' => array(
				'requiredNote' => '<span class="required">*</span> erforderliches Feld'
			),
			'rule' => array(
				'required.give_to' => 'Annehmender Benutzer muss ausgew&auml;hlt werden!',
				'check.give_to' => 'Annehmender Benutzer muss ausgew&auml;hlt werden!'
			)
		),
		'cancel' => array(
			'form' => array(
				'yes' => 'Ja',
				'cancel' => 'abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie dieses Objekt wirklich zur&uuml;ckziehen?',
				'done' => 'Das Objekt wurde erfolgreich zur&uuml;ckgezogen.'
			),
			'title' => array(
				'cancel' => 'Bricht den Vorgang ab'
			)
		),
		'listall' => array(
			'TH' => array(
				'name' => 'Objekt',
				'number' => 'Inventarnummer',
				'owner' => 'Besitzer',
				'status' => 'Status'
			),
			'status' => array(
				'givento' => 'abzugeben an'
			),
			'title' => array(
				'details' => 'Details'
			)
		),
		'details' => array(
			'accessories' => array(
				'list' => 'Zubeh&ouml;r'
			)
		),
		'get_movements' => array(
			'date' => array(
				'title' => '&Uuml;bergaben anzeigen'
			)
		),
		'movement' => array(
			'fields' => array(
				'taken' => 'Angenommen von',
				'given' => 'Abgegeben von'
			),
			'hx' => array(
				'movement' => '&Uuml;bergabe von ',
				'at' => 'am '
			),
			'back' => array(
				'title' => 'zur&uuml;ck',
				'name' => 'zur&uuml;ck zur Liste'
			)
		),
		'default_content' => array(
			'headline' => array(
				'text' => 'Inventarverwaltung'
			),
			'explain' => array(
				'my.hx' => 'Eigene verwalten',
				'my.p' =>	'Unter "Eigene verwalten" werden die Objekte angezeigt, die derzeit in deinem Besitz sind oder die an dich abgegeben wurden.<br />
							Wenn ein Objekt abgegeben werden soll, ist der Empf&auml;nger aus zu w&auml;hlen und das zu &uuml;bergebende Zubeh&ouml;r an zu haken.<br />
							Beim Annehmen eines Objekts ist der Empfang des &uuml;bernommenen Zubeh&ouml;rs zu best&auml;tigen.',
				'listall.hx' => 'Listenansicht',
				'listall.p' =>	'Die Listenansicht bietet einen &Uuml;berblick &uuml;ber das Inventar, die derzeitigen Besitzer und den Status eines Objekts. Die Details eines
								Objekts zeigen die &Uuml;bergaben und Details zum &uuml;bergebenen Zubeh&ouml;r.'
			)
		)
	),
	'class.AdministrationView' => array(
		'tableRows' => array(
			'name' => array(
				'name' => 'Name/Anzeigename',
				'category' => 'Kategorie',
				'value' => 'Wert',
				'valid' => 'Aktiviert',
				'number' => 'Nummer',
				'id' => 'ID',
				'club_id' => 'Vereins-ID',
				'class' => 'Altersgruppe',
				'type' => 'Typ',
				'weightclass' => 'Gewichtsklasse',
				'time' => 'Zeit',
				'agegroups' => 'Jahrg&auml;nge',
				'color' => 'Farbe',
				'hall' => 'Halle',
				'street' => 'Stra&szlig;e',
				'zip' => 'PLZ',
				'city' => 'Stadt',
				'email' => 'Emailadresse'
			)
		),
		'connectnavi' => array(
			'firstlevel' => array(
				'name' => 'Administration'
			),
			'secondlevel' => array(
				'field' => 'Ben. Tabellen verwalten',
				'defaults' => 'Vorgaben verwalten'
			)
		),
		'init' => array(
			'default' => array(
				'title' => 'Administration'
			),
			'Error' => array(
				'NotAuthorized' => 'FEHLER - Nicht berechtigt'
			),
			'title' => array(
				'field' => 'Administration: benutzerdefinierte Tabellen verwalten',
				'defaults' => 'Administration: Vorgaben verwalten'
			)
		),
		'create_table_links' => array(
			'title' => array(
				'manage' => ' verwalten'
			),
			'name' => array(
				'manage' => ' verwalten'
			),
			'toggleTable' => array(
				'title' => 'Tabellenauswahl ein- oder ausblenden',
				'name' => 'Tabellenauswahl ein- oder ausblenden'
			)
		),
		'field' => array(
			'caption' => array(
				'name' => 'Benutzerdefinierte Tabellen verwalten',
				'name.table' => 'Tabelle verwalten: '
			),
			'disable' => array(
				'rowNotEnabled' => 'Diese Tabellenzeile ist nicht aktiviert und kann nicht deaktiviert werden, m&ouml;chten Sie sie stattdessen aktivieren?',
				'rowNotEnabled.enable' => 'Zeile aktivieren'
			),
			'enable' => array(
				'rowNotDisabled' => 'Diese Tabellenzeile ist nicht deaktiviert und kann nicht aktiviert werden, m&ouml;chten Sie sie stattdessen deaktivieren?',
				'rowNotDisabled.disable' => 'Zeile deaktivieren'
			)
		),
		'list_table_content' => array(
			'pages' => array(
				'page' => 'Seite',
				'pages' => 'Seiten',
				'to' => 'bis',
				'of' => 'von'
			),
			'table' => array(
				'tasks' => 'Aufgaben',
				'edit' => 'Bearbeiten',
				'disable' => 'Deaktivieren',
				'enable' => 'Aktivieren',
				'delete' => 'L&ouml;schen'
			),
			'new' => array(
				'title' => 'Neuen Eintrag in diese Tabelle einf&uuml;gen',
				'name' => 'Neuer Eintrag'
			)
		),
		'delete_row' => array(
			'form' => array(
				'yes' => 'Ja'
			),
			'cancel' => array(
				'title' => 'Abbrechen',
				'form' => 'Abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie diese Zeile wirklich l&ouml;schen? Sie ist damit unwiederbringlich entfernt, Deaktivieren blendet sie f&uuml;r Benutzer aus, bestehende Verkn&uuml;pfungen bleiben aber bestehen!',
				'done' => 'Die Zeile wurde endg&uuml;tig gel&ouml;scht!'
			)
		),
		'edit_row' => array(
			'rule' => array(
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
				'requiredSelect' => 'Feld muss ausgew&auml;hlt werden!',
				'checkSelect' => 'Feld muss ausgew&auml;hlt werden!',
				'required' => 'Feld muss ausgef&uuml;llt werden!'
			),
			'form' => array(
				'submitButton' => 'Speichern',
				'requiredNote' => '<span class="required">*</span> erforderliches Feld'
			),
			'caption' => array(
				'edit' => '&Auml;ndere Zeile',
				'done' => 'Zeile erfolgreich ge&auml;ndert'
			)
		),
		'new_row' => array(
			'rule' => array(
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
				'requiredSelect' => 'Feld muss ausgew&auml;hlt werden!',
				'checkSelect' => 'Feld muss ausgew&auml;hlt werden!',
				'required' => 'Feld muss ausgef&uuml;llt werden!'
			),
			'form' => array(
				'submitButton' => 'Speichern',
				'requiredNote' => '<span class="required">*</span> erforderliches Feld'
			),
			'caption' => array(
				'edit' => 'Zeile einf&uuml;gen',
				'done' => 'Zeile erfolgreich eingef&uuml;gt'
			)
		),
		'defaults' => array(
			'caption' => array(
				'name' => 'Vorgaben verwalten'
			),
			'disable' => array(
				'rowNotEnabled' => 'Diese Tabellenzeile ist nicht aktiviert und kann nicht deaktiviert werden, m&ouml;chten Sie sie stattdessen aktivieren?',
				'rowNotEnabled.enable' => 'Zeile aktivieren'
			),
			'enable' => array(
				'rowNotDisabled' => 'Diese Tabellenzeile ist nicht deaktiviert und kann nicht aktiviert werden, m&ouml;chten Sie sie stattdessen deaktivieren?',
				'rowNotDisabled.disable' => 'Zeile deaktivieren'
			)
		)
	),
	'class.ProtocolView' => array(
		'init' => array(
			'listall' => array(
				'title' => 'Protokolle: Listenansicht'
			),
			'default' => array(
				'title' => 'Protokolle'
			),
			'Error' => array(
				'NotAuthorized' => 'FEHLER - Nicht berechtigt'
			),
			'new' => array(
				'title' => 'Protokolle: Neues Protokoll'
			),
			'edit' => array(
				'title' => 'Protokolle: Protokoll bearbeiten'
			),
			'delete' => array(
				'title' => 'Protokolle: Protokoll l&ouml;schen'
			)
		),
		'connectnavi' => array(
			'firstlevel' => array(
				'name' => 'Protokolle'
			),
			'secondlevel' => array(
				'listall' => 'Listenansicht',
				'new' => 'Neues Protokoll erstellen'
			)
		),
		'default_content' => array(
			'headline' => array(
				'text' => 'Protokolle'
			)
		)
	)
);


?>
