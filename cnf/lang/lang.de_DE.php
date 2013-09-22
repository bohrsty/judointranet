<?php
/* ********************************************************************************************
 * Copyright (c) 2011 Nils Bohrs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 * 
 * Thirdparty licenses see LICENSE
 * 
 * ********************************************************************************************/
 

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
				'name' => 'Filter ein- oder ausblenden',
				'closeText' => 'Filterdialog schlie&szlig;en',
				'resetFilter' => 'Filter zur&uuml;cksetzen',
				'dateFilter' => 'Datumsfilter',
				'groupFilter' => 'Gruppenfilter',
				'dialogTitle' => 'Filter ausw&auml;hlen'
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
				'rights' => 'Filterbare Gruppen (mehrfache Auswahl: &lt;STRG&gt; gedr&uuml;ckt halten und Gruppen ausw&auml;hlen)',
				'entry_content' => 'Inhalt/Beschreibung',
				'announcement' => 'Ausschreibung',
				'public' => '&Ouml;ffentlicher Zugriff'
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
				'cancel' => 'Abbrechen'
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
		),
		'page' => array(
			'init' => array(
				'name' => 'Kalender'
			),
			'caption' => array(
				'edit' => 'Editiere Eintrag',
				'listall' => 'Listenansicht',
				'new_entry' => 'Neuen Eintrag erstellen',
				'details' => 'Details',
				'delete' => 'L&ouml;sche Eintrag'
			)
		)
	),
	'class.PageView' => array(
		'title' => array(
			'prefix' => array(
				'title' => 'JudoIntranet'
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
		),
		'defaultContent' => array(
			'text' => array(
				'caption' => '&Uuml;berschrift',
				'content' => 'Inhalt'
			)
		),
		'showPage' => array(
			'helpMessages' => array(
				'closeText' => 'Schlie&szlig;en',
			),
		),
	),
	'class.MainView' => array(
		'page' => array(
			'init' => array(
				'name' => 'Judointranet'
			)
		),
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
			),
			'edit' => array(
				'title' => 'Ausschreibung: Bearbeiten'
			),
			'details' => array(
				'title' => 'Ausschreibung: Detailansicht',
			),
			'Error' => array(
				'NotAuthorized' => 'FEHLER - Nicht berechtigt'
			),
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
				'cancel' => 'Abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie diesen Eintrag wirklich l&ouml;schen?',
				'done' => 'Der Eintrag wurde erfolgreich gel&ouml;scht.'
			),
			'title' => array(
				'cancel' => 'Bricht den L&ouml;schvorgang ab'
			)
		),
		'page' => array(
			'init' => array(
				'name' => 'Ausschreibung'
			),
			'caption' => array(
				'edit' => 'Editiere Eintrag',
				'details' => 'Details',
				'delete' => 'L&ouml;sche Eintrag'
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
				'cancel' => 'Abbrechen'
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
		'page' => array(
			'init' => array(
				'name' => 'Inventar'
			),
			'caption' => array(
				'listall' => 'Listenansicht',
				'my' => 'Eigene Objekte verwalten',
				'give' => 'Objekt abgeben',
				'take' => 'Objekt &uuml;bernehmen',
				'details' => 'Details',
				'cancel' => 'Aktion abbrechen'
			)
		)
	),
	'class.AdministrationView' => array(
		'page' => array(
			'init' => array(
				'name' => 'Administration'
			)
		),
		'tableRows' => array(
			'name' => array(
				'name' => 'Name/Anzeigename',
				'category' => 'Kategorie',
				'value' => 'Wert',
				'valid' => 'Status',
				'number' => 'Nummer',
				'id' => 'Schl&uuml;ssel',
				'club_id' => 'Vereins-Schl&uuml;ssel',
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
			),
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
				'rowNotDisabled' => 'Diese Tabellenzeile ist aktiviert und kann nicht aktiviert werden, m&ouml;chten Sie sie stattdessen deaktivieren?',
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
				'delete' => 'L&ouml;schen',
				'disabled' => 'Deaktiviert',
				'enabled' => 'Aktiviert',
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
		),
		'page' => array(
			'init' => array(
				'name' => 'Administration'
			),
			'caption' => array(
				'field' => 'Benutzerdefinierte Tabellen',
				'defaults' => 'Vorgegebene Felder'
			)
		),
		'tables' => array(
			'name' => array(
				'club' => '<b>Vereine</b>',
				'contact' => '<b>Ansprechpartner</b>',
				'judo' => '<b>Judo</b>',
				'judo_belt' => '<b>G&uuml;rtelfarben</b>',
				'location' => '<b>Hallen</b>',
				'protocol_types' => '<b>Protokolltypen</b>',
				'staff' => '<b>Referenten</b>'
			),
		),
	),
	'class.ProtocolView' => array(
		'page' => array(
			'caption' => array(
				'listall' => 'Listenansicht',
				'details' => 'Details',
				'show' => 'Protokoll anzeigen',
				'topdf' => 'PDF anzeigen',
				'correct' => 'Protokoll korrigieren',
				'new_entry' => 'Neues Protokoll',
				'edit' => 'Protokoll bearbeiten',
				'decisions' => 'Beschl&uuml;sse anzeigen'
			),
			'init' => array(
				'name' => 'Protokolle'
			)
		),
		'init' => array(
			'title' => array(
				'listall' => 'Protokolle: Listenansicht',
				'details' => 'Protokolle: Details',
				'default' => 'Protokolle',
				'new' => 'Protokolle: Neues Protokoll',
				'edit' => 'Protokolle: Protokoll bearbeiten',
				'delete' => 'Protokolle: Protokoll l&ouml;schen',
				'show' => 'Protokolle: Protokoll anzeigen',
				'topdf' => 'Protokolle: Protokoll als PDF',
				'correct' => 'Protokolle: Protokoll korrigieren',
				'decisions' => 'Protokolle: Beschl&uuml;sse anzeigen'
			),
			'Error' => array(
				'NotAuthorized' => 'FEHLER - Nicht berechtigt'
			),
		),
		'connectnavi' => array(
			'firstlevel' => array(
				'name' => 'Protokolle'
			),
			'secondlevel' => array(
				'listall' => 'Listenansicht',
				'new' => 'Neues Protokoll erstellen',
				'showdecisions' => 'Alle Beschl&uuml;sse anzeigen'
			)
		),
		'defaultContent' => array(
			'headline' => array(
				'text' => 'Protokolle'
			)
		),
		'listall' => array(
			'TH' => array(
				'date' => 'Datum',
				'type' => 'Art',
				'location' => 'Ort',
				'show' => 'Ansehen',
				'admin' => 'Aufgaben'
			),
			'title' => array(
				'edit' => 'Protokoll bearbeiten',
				'delete' => 'Protokoll l&ouml;schen',
				'correct' => 'Protokoll korrigieren',
				'ProtShow' => 'Protokoll anzeigen',
				'ProtPDF' => 'Protokoll als PDF',
				'date' => 'Details anzeigen',
				'corrected' => 'Korrekturen vorhanden, &uuml;berpr&uuml;fen'
			),
			'alt' => array(
				'edit' => 'Protokoll bearbeiten',
				'delete' => 'Protokoll l&ouml;schen',
				'correct' => 'Protokoll korrigieren',
				'ProtShow' => 'Protokoll anzeigen',
				'ProtPDF' => 'Protokoll als PDF',
				'corrected' => 'Korrekturen vorhanden, &uuml;berpr&uuml;fen'
			)
		),
		'details' => array(
			'show' => array(
				'title' => 'Protokoll anzeigen',
				'name' => 'Protokoll'
			),
			'decisions' => array(
				'title' => 'Alle Beschl&uuml;sse dieses Protokolls anzeigen',
				'name' => 'Beschl&uuml;sse'
			),
			'topdf' => array(
				'title' => 'Protokoll als PDF anzeigen',
				'name' => 'PDF'
			)
		),
		'entry' => array(
			'form' => array(
				'requiredNote' => '<span class="required">*</span> erforderliches Feld',
				'preset' => 'Vorlage',
				'date' => 'Datum',
				'submitButton' => 'Speichern',
				'location' => 'Ort',
				'member0' => 'Teilnehmer (anwesend)',
				'member1' => 'Teilnehmer (entschuldigt)',
				'member2' => 'Teilnehmer (unentschuldigt)',
				'type' => 'Art der Sitzung',
				'rights' => 'Rechte',
				'protocol' => 'Inhalt/Protokolltext',
				'recorder' => 'Protokollant',
				'public' => '&Ouml;ffentlicher Zugriff',
				'correction' => 'Status',
				'correctionInWork' => 'in Bearbeitung',
				'correctionCorrect' => 'Korrekturfreigabe',
				'correctionFinished' => 'ver&ouml;ffentlicht',
				'correctors' => 'Korrektoren',
				'finished' => 'Korrektur abgeschlossen'
			),
			'rule' => array(
				'required.date' => 'Datum muss ausgew&auml;hlt werden!',
				'check.date' => 'Korrektes Datum muss ausgew&auml;hlt werden!',
				'required.location' => 'Ort muss eingetragen werden!',
				'required.type' => 'Art der Sitzung muss ausgew&auml;hlt werden!',
				'required.preset' => 'Vorlage muss ausgew&auml;hlt werden!',
				'check.select' => 'Feld muss ausgew&auml;ht werden!',
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
				'required.member' => 'Mindestens ein Teilnehmer muss angegeben werden!',
				'required.recorder' => 'Protokollant muss eingetragen werden!'
			)
		),
		'new_entry' => array(
			'tmce' => array(
				'item' => 'TOP',
				'decision' => 'Beschluss'
			)
		),
		'delete' => array(
			'form' => array(
				'yes' => 'Ja'
			),
			'cancel' => array(
				'title' => 'Bricht den L&ouml;schvorgang ab',
				'form' => 'Abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie diesen Eintrag wirklich l&ouml;schen?',
				'done' => 'Der Eintrag wurde erfolgreich gel&ouml;scht.'
			)
		),
		'show' => array(
			'decisionLink' => array(
				'title' => 'Zusammenfassung der Beschl&uuml;sse dieses Protokolls anzeigen',
				'text' => 'Zeige Beschl&uuml;sse dieses Protokolls'
			)
		),
		'correct' => array(
			'message' => array(
				'done' => '<p>Korrektur erfolgreich gespeichert.</p>',
				'corrected' => 'Protokoll erfolgreich aktualisiert',
				'back' => 'zur&uuml;ck zur Korrektur'
			),
			'difflist' => array(
				'correctedBy' => 'Korrektur von',
				'caption' => 'Liste der erstellten Korrekturen',
				'imgDone' => 'Korrektur bearbeitet'
			),
			'diff' => array(
				'baseCaption' => 'Originaltext',
				'newCaption' => 'Korrektur'
			)
		)
	),
	'class.Protocol' => array(
		'details' => array(
			'data' => array(
				'status' => '<span>Status:</span><br />',
				'status0' => 'in Bearbeitung',
				'status1' => 'in Bearbeitung',
				'status2' => 'Ver&ouml;ffentlicht',
				'date' => '<span>Datum:</span><br />',
				'type' => '<span>Art:</span><br />',
				'location' => '<span>Ort:</span><br />',
				'member0' => '<span>Teilnehmer (anwesend):</span><br />',
				'member1' => '<span>Teilnehmer (entschuldigt):</span><br />',
				'member2' => '<span>Teilnehmer (unentschuldigt):</span><br />',
				'decisions' => '<span>Beschreibung:</span><br />',
				'owner' => '<span>Besitzer:</span><br />',
				'recorder' => '<span>Protokollant:</span><br />'
			)
		)
	),
	'class.Help' => array(
		'global' => array(
			'title' => array(
				'errorIdNotExists' => 'Fehler',
				'about' => 'Info',
				'fieldDate' => 'Datumsfeld',
				'fieldName' => 'Namen-/Bezeichnungsfeld',
				'fieldShortname' => 'Kurznamensfeld',
				'fieldType' => 'Typauswahlfeld',
				'fieldContent' => 'Inhaltsfeld',
				'fieldSort' => 'Gruppierungsauswahlfeld',
				'fieldIsPublic' => 'Ver&ouml;ffentlichungsauswahlfeld',
				'calendarNew' => 'Neuer Termin',
				'calendarListall' => 'Terminliste',
				'calendarListAdmin' => 'Aufgaben in der Terminliste',
				'calendarListSortlinks' => 'Filterliste',
				'delete' => 'L&ouml;schen',
				'FieldText' => 'Textfeld',
				'FieldCheckbox' => 'Auswahlbox',
				'FieldDbselect' => 'Auswahlfeld',
				'FieldDbhierselect' => 'Abh&auml;ngiges Auswahlfeld',
				'Login' => 'Login',
				'adminUsertableSelect' => 'Tabelle ausw&auml;hlen',
				'adminUsertableTasks' => 'Aufgaben',
			),
			'message' => array(
				'errorIdNotExists' => '<p>Dieses Hilfe-Thema konnte nicht gefunden werden.</p>',
				'about' => '<p><b>judointranet</b></p>
					<p>Author: Nils Bohrs<br />
					Version: {$replace.version}<br />Lizenz: MIT</p>
					<p>&nbsp;</p>
					<p>Copyright (c) 2011 Nils Bohrs</p>
					<p>Permission is hereby granted, free of charge, to any person obtaining a copy of this
					software and associated documentation files (the "Software"), to deal in the Software
					without restriction, including without limitation the rights to use, copy, modify, merge,
					publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
					to whom the Software is furnished to do so, subject to the following conditions:</p>
					<p>The above copyright notice and this permission notice shall be included in all copies or
					substantial portions of the Software.</p>
					<p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
					INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
					PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
					FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
					OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
					DEALINGS IN THE SOFTWARE.</p>',
				'fieldDate' => '<p><b>Format</b>: <i>yyyy-mm-dd</i><br />
					<b>Standardwert</b>: <i>das heutige Datum</i></p>
					<p>Dieses Feld legt ein Datum f&uuml;r diesen Datensatz fest, das Feld
					hat eine Datumsauswahl zur Unterst&uuml;tzung, dieses sollte zur Sicherstellung
					des korrekten Formats verwendet werden.</p>',
				'fieldName' => '<p><b>Format</b>: <i>einzeiliger Text</i><br />
					<b>Standardwert</b>: <i>keiner</i><br />
					<b>Erlaubte Zeichen</b>: <i>"'.self::getGc()->get_config('name.desc').'"</i></p>
					<p>Dieses Feld legt den Namen oder die Bezeichnung f&uuml;r diesen Datensatz
					fest, der Wert erscheint zumeist in Listen oder &Uuml;berschriften.</p>',
				'fieldShortname' => '<p><b>Format</b>: <i>einzeiliger Text</i><br />
					<b>Standardwert</b>: <i>keiner</i><br />
					<b>Erlaubte Zeichen</b>: <i>"'.self::getGc()->get_config('name.desc').'"</i></p>
					<p>Dieses Feld legt einen Kurznamen f&uuml;r diesen Datensatz fest, der Wert wird in
					den Dateinamen der Ausschreibungen zur Abk&uuml;rzung verwendet. Wenn das Feld nicht
					ausgef&uuml;llt wird, wird der Wert beim Speichern auf die ersten drei Buchstaben
					des Namens-/Bezeichnungsfeldes gesetzt, der Wert wird immer in Gro&szlig;buchstaben
					umgewandelt.</p>',
				'fieldType' => '<p><b>Format</b>: <i>einzeiliges Auswahlfeld</i></p>
					<p>Dieses Feld legt den Typ f&uuml;r diesen Datensatz fest, z.B. bei Veranstaltunge
					oder Terminen die Art der Veranstaltung (Turnier, Lehrgang, etc.).</p>',
				'fieldContent' => '<p><b>Format</b>: <i>mehrzeiliger Text</i><br />
					<b>Standardwert</b>: <i>keiner</i><br />
					<b>Erlaubte Zeichen</b>: <i>"'.
					htmlspecialchars(self::getGc()->get_config('textarea.desc')).'"</i></p>
					<p>Dieses Feld legt den Inhalt f&uuml;r diesen Datensatz fest, hier kann
					z.B. die genaue Beschreibung eines Termin eingetragen werden.</p>',
				'fieldSort' => '<p><b>Format</b>: <i>Mehrfachauswahlfeld</i></p>
					<p>Dieses Feld legt die Gruppen fest, nach denen der Datensatz mittels
					Sortierung in den Listen angezeigt wird. Die Auswahl mehrerer Gruppen ist
					durch Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich, das
					Entfernen der Auswahl oder einzelner Gruppen ist ebenfalls mittels
					Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich.</p>',
				'fieldIsPublic' => '<p><b>Format</b>: <i>Auswahlbox</i></p>
					<p>Das Anhaken der Auswahlbox markiert diesen Datensatz als &ouml;ffentlich,
					wenn der Haken gesetzt ist, wird der Datensatz in den &ouml;ffentlichen Listen,
					also ohne Anmeldung sichtbar.</p>',
				'calendarNew' => '<p>Formular zur Erstellung eines neuen Termins. Alle Felder, die
					mit einem roten <span class="required">*</span> gekennzeichnet sind, m&uuml;ssen ausgef&uuml;llt werden, das
					Formular l&auml;sst sich sonst nicht speichern.<br />Die erlaubten Zeichen
					werden in der Hilfe des jeweiligen Feldes erl&auml;tert, bei Fehleingaben
					wird eine entsprechende Meldung ausgegeben.</p>',
				'calendarListall' => '<p>Diese Seite listet alle Termine, die noch nicht abgelaufen
					sind (inkl. des gesamten heutigen Tages). Ein Klick auf den unterstrichenen
					Namen des Termins &ouml;ffnet dessen Details, wenn eine Ausschreibung zu
					diesem Termin vorhanden ist, stehen folgende weitere Ansichten zur
					Verf&uuml;gung:</p>
					<ul>
					<li><img src="img/ann_details.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet
					die Ausschreibung als Seitenansicht eingebettet in diese Seite, Drucken ist
					in dieser Ansicht nicht m&ouml;glich.</li>
					<li><img src="img/ann_pdf.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet die
					Ausschreibung direkt als PDF (ein entsprechendes Programm zur Anzeige
					wie der AdobeReader vorausgesetzt), in dieser Ansicht kann die Ausschreibung
					gedruckt oder gespeichert werden.</li>
					</ul>',
				'calendarListAdmin' => '<p>Die Administration eines Termins oder einer
					Ausschreibung erfolgt &uuml;ber folgende Buttons:</p>
					<ul>
					<li><img src="img/edit.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet den Termin im
					Bearbeitungsmodus, hier k&ouml;nnen die einzelnen Felder des Datensatzes
					ge&auml;ndert werden.</li>
					<li><img src="img/delete.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; l&ouml;scht den Termin nach
					R&uuml;ckfrage endg&uuml;ltig.</li>
					<li><img src="img/ann_edit.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet die Ausschreibung
					im Bearbeitungsmodus, hier k&ouml;nnen die einzelnen Felder des Datensatzes
					ge&auml;ndert werden, die erste Bearbeitung erstellt die zugeh&ouml;rigen
					Felder.</li>
					<li><img src="img/ann_delete.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; l&ouml;scht die Ausschreibung
					nach R&uuml;ckfrage endg&uuml;ltig.</li>
					<li><select><option>Ausschreibungsvorlage ausw&auml;hlen</option></select>
					<input type="submit" value="+" />&nbsp;:&nbsp; Um eine Ausschreibung mit Daten
					zu f&uuml;llen muss ihr eine Vorlage zugewiesen werden, die die zu verwendenden
					Felder und das Aussehen festlegt.<br />
					Das Zuweisen der Vorlage erfolgt durch das Ausw&auml;hlen der Vorlage aus dem
					einzeiligen Auswahlfeld und anschlie&szlig;endem Zuf&uuml;gen durch den Button.</li>
					</ul>',
				'calendarListSortlinks' => '<p>Die eingeblendete Filterauswahl besteht aus drei
					Bereichen:</p>
					<ul>
					<li>Der erste Bereich enth&auml;lt die Buttons zum Zur&uuml;cksetzen der
					gew&auml;hlten Filter:<br />"<span class="underline">Alle Filter zur&uuml;cksetzen</span>" zeigt wieder die
					komplette Liste an, ohne Filter.<br />"<span class="underline">Datumsfilter zur&uuml;cksetzen</span>"
					beh&auml;lt die ausgew&auml;hlte Gruppe bei und entfernt nur den Zeitraum.<br />
					"<span class="underline">Gruppenfilter zur&uuml;cksetzen</span>"beh&auml;lt den gew&auml;hlten Zeitraum und
					entfernt nur die Gruppe</li>
					<li>Der zweite Bereich enth&auml;lt die Buttons zur Auswahl eines festgelegten
					Zeitraums, der die Liste auf die Termine einschr&auml;nkt, die in diesem Zeitraum
					stattfinden.</li>
					<li>Der dritte Bereich enth&auml;lt die Buttons zur Auswahl nach festgelegten
					Gruppierungen (z.B.: Altersklassen), die beim anlegen des Termins festgelegt
					wurden.</li>
					</ul>',
				'delete' => '<p>Der Klick auf "Ja" l&ouml;scht den Datensatz endg&uuml;ltig,
					"Abbrechen" f&uuml;hrt zur&uuml;ck zur Liste.</p>',
				'FieldText' => '<p><b>Format</b>: <i>mehrzeiliger Text</i><br />
					<b>Standardwert</b>: <i>keiner</i><br />
					<b>Erlaubte Zeichen</b>: <i>"'.
					htmlspecialchars(self::getGc()->get_config('textarea.desc')).'"</i><br />
					<b>Auswahl</b>: <i>Systemvorgaben oder Auswahl aus den letzten Eingaben</i></p>
					<p>Das Textfeld nimmt alle Zeichen laut <b>Erlaubte Zeichen</b> an, &uuml;ber
					das einzeilige Auswahlfeld k&ouml;nnen vorgefertigte Texte des Systems oder
					aus der Historie der zuletzt eingegebenen Texte ausgew&auml;hlt werden.</p>',
				'FieldCheckbox' => '<p><b>Format</b>: <i>Auswahlbox</i></p>
					<p>Das Anhaken der Auswahlbox aktiviert die Einstellung oder den Wert, das
					Entfernen des Hakens deaktiviert sie.</p>',
				'FieldDbselect' => '<p><b>Format</b>: <i>Auswahlfeld/Mehrfachauswahlfeld</i></p>
					<p>Dieses Feld erlaubt die Auswahl eines (bei einfachem Auswahlfeld) oder
					mehrfache Auswahl der vorgegebenen Werte. Die Auswahl mehrerer Werte ist
					durch Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich, das
					Entfernen der Auswahl oder einzelner Werte ist ebenfalls mittels
					Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich.</p>',
				'FieldDbhierselect' => '<p><b>Format</b>: <i>abh&auml;ngigesAuswahlfeld</i></p>
					<p>Dieses Feld bietet von einander abh&auml;ngige Optionen zur Auswahl an. Die
					get&auml;tige Auswahl des ersten Felds beeinflusst die zur Auswahl stehenden
					Optionen des zweiten Auswahlfeldes.</p>',
				'Login' => '
					{if array_key_exists(\'class.MainView#callback_check_login#message#UserNotActive\', $replace)}
						<p><b>Benutzer nicht aktiv</b></p>
						<p>Dieser Benutzer ist deaktiviert worden oder noch nicht aktiviert, falls
						diese Meldung zu unrecht erscheint, bitte beim '.htmlspecialchars(self::getGc()->get_config('global.systemcontactName')).'
						nachfragen.</p>
					{elseif array_key_exists(\'class.MainView#callback_check_login#message#WrongPassword\', $replace)}
						<p><b>Falsches Passwort</b></p>
						<p>Es wurde versucht sich mit einem falschen Passwort an zu melden, falls
						diese Meldung zu unrecht erscheint, bitte die Schreibweise des Passworts
						pr&uuml;fen, oder beim '.htmlspecialchars(self::getGc()->get_config('global.systemcontactName')).'
						nachfragen.</p>
					{elseif array_key_exists(\'class.MainView#callback_check_login#message#UserNotExist\', $replace)}
						<p><b>Benutzer existiert nicht</b></p>
						<p>Es wurde versucht sich mit einem nicht existierenden Benutzer an zu melden, falls
						diese Meldung zu unrecht erscheint, bitte die Schreibweise des Benutzernamens
						pr&uuml;fen, oder beim '.htmlspecialchars(self::getGc()->get_config('global.systemcontactName')).'
						nachfragen.</p>
					{else}
						<p><b>Login</b></p>
						<p>Um die nicht &ouml;ffentlichen Funktionen zu nutzen, muss in einem der
						beteiligten Gremien mitgearbeitet werden. Der Zugang wird durch die
						entsprechenden Vorst&auml;nde genehmigt und die Zugangsdaten (Benutzername
						und Passwort) werden durch den '.htmlspecialchars(self::getGc()->get_config('global.systemcontactName')).'
						zur Verf&uuml;gung gestellt.</p>
					{/if}',
				'adminUsertableSelect' => '<p>Der nebenstehende Link blendet die Auswahl der
					administrierbaren Tabellen ein (z.B.: Vereine und deren Ansprechpartner).
					Ein Klick auf die gew&uuml;nschte Tabelle zeigt deren Inhalt an und bietet
					die Funktionen diese zu ver&auml;ndern, zu deaktivieren oder zu l&ouml;schen
					und neue Datens&auml;tze an zu legen.<br />
					Die Art und Zahl der Spalten unterscheidet sich je nach Tabelle, da
					unterschiedliche Daten gespeichert werden.</p>',
				'adminUsertableTasks' => '<p></p>
					<ul>
					<li><img src="img/admin_edit.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet den Datensatz
					zur Bearbeitung, hier k&ouml;nnen alle Felder bis auf den Schl&uuml;ssel
					ge&auml;ndert werden.</li>
					<li><img src="img/admin_disable.png" alt="Bild im Hilfetext" />/<img src="img/admin_enable.png" />&nbsp;:&nbsp;
					Je nach Status des Datensatzes aktiviert oder deaktiviert dieser Button den Datensatz.
					Deaktivierte Datens&auml;tze werden in keinem Auswahlfeld angezeigt, existieren aber noch.</li>
					<li><img src="img/admin_delete.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; L&ouml;scht den Datensatz nach
					R&uuml;ckfrage. Der Datensatz ist danach endg&uuml;ltig aus der Datenbank entfernt und
					nur durch Neueintragen wiederherstellbar.</li>
					</ul>',
			),
		),
		'getMessage' => array(
			'templateValues' => array(
				'imgTitle' => 'Hilfe',
			),
		),
	)
);


?>
