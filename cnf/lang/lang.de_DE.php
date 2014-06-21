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
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>'.
									(self::getUser()->get_loggedin() ? '' : ' oder <a href="index.php?id=login'.Error::afterLogin('&amp;').'" title="anmelden">melden Sie sich an</a>').'.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'NotAuthorizedDemo' => array(
				'ERROR.caption' => 'DEMO-MODUS',
				'ERROR.message' => 'Diese Seite l&auml;uft im Demo-Modus, einige Funktionen sind deaktiviert:<br />
									Alle Funktionen, die die Anmeldung der Benutzer ver&auml;ndern oder Eingriffe in die Systemkonfiguration erm&ouml;glichen sind nicht verf&uuml;gbar<br />
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
			),
			'MysqlError' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Es ist ein Fehler bei einer Datenbank-Abfrage aufgetreten, bitte geben Sie die nachfolgenden Informationen an den Systembetreuer weiter:<br />'
			),
			'HeaderSent' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Beim Zusammenstellen der Daten zum Download ist ein Fehler aufgetreten.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet oder wiederholt auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'ObjectNotExists' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Das angegebene Objekt existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'GETUnknownAction' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Die angegebene Aktion ist nicht bekannt, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'NidNotExists' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Der Navigationseintrag existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'GidNotExists' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Die Gruppe existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'UidNotExists' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Der Benutzer existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'ObjectInUse' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Das Objekt ist in Verwendung und kann nicht gel&ouml;scht/bearbeitet werden, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
			'FileNotExists' => array(
				'ERROR.caption' => 'FEHLER',
				'ERROR.message' => 'Die angegebene Datei existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
									Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
									Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />'
			),
		
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
				'AnnDetails.draft' => 'Ausschreibung anzeigen (ENTWURF)',
				'AnnPDF' => 'Ausschreibung als PDF anzeigen',
				'AnnPDF.draft' => 'Ausschreibung als PDF anzeigen (ENTWURF)',
				'attach' => 'Datei(en) anh&auml;ngen',
				'filesAttached' => 'Anh&auml;nge vorhanden',
				'public' => '&Ouml;ffentlich',
			),
			'title' => array(
				'edit' => 'bearbeitet diesen Eintrag',
				'delete' => 'l&ouml;scht diesen Eintrag',
				'AnnEdit' => 'bearbeitet die Ausschreibung',
				'AnnDelete' => 'l&ouml;scht die Ausschreibung',
				'AnnDetails' => 'Ausschreibung anzeigen',
				'AnnPDF' => 'Ausschreibung als PDF anzeigen',
				'attach' => 'Datei(en) anh&auml;ngen',
				'filesAttached' => 'Anh&auml;nge vorhanden',
				'public' => '&Ouml;ffentlich',
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
			),
			'choose' => array(
				'date' => 'Zeitraum ausw&auml;hlen',
				'group' => 'Gruppe ausw&auml;hlen',
			),
		),
		'entry' => array(
			'form' => array(
				'requiredNote' => '<span class="required">*</span> erforderliches Feld',
				'date' => 'Datum',
				'submitButton' => 'Speichern',
				'name' => 'Name',
				'shortname' => 'Kurzbezeichnung',
				'type' => 'Veranstaltungstyp',
				'filter' => 'Filterbare Gruppen (mehrfache Auswahl: &lt;STRG&gt; gedr&uuml;ckt halten und Gruppen ausw&auml;hlen)',
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
				'yes' => '  Ja  ',
				'cancel' => 'Abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie diesen Eintrag wirklich l&ouml;schen?',
				'done' => 'Der Eintrag wurde erfolgreich gel&ouml;scht.'
			),
			'title' => array(
				'cancel' => 'Bricht den L&ouml;schvorgang ab',
				'yes' => 'L&ouml;scht den Eintrag',
			)
		),
		'readPresetForm' => array(
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
				'newEntry' => 'Neuen Eintrag erstellen',
				'details' => 'Details',
				'delete' => 'L&ouml;sche Eintrag'
			)
		),
		'global' => array(
			'info' => array(
				'help' => 'Hilfe'
			),
		),
		'details' => array(
			'text' => array(
				'attached' => '<b>Angeh&auml;ngte Dateien:</b>',
				'none' => '- keine -',
			),
		),
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
				'logout.title' => 'Logout',
				'data' => 'Benutzerdaten &auml;ndern',
				'data.title' => 'Benutzerdaten &auml;ndern',
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
		'zebraAddPermissions' => array(
			'dialog' => array(
				'closeText' => 'Schlie&szlig;en',
				'title' => 'Berechtigungen auswählen',
			),
			'form' => array(
				'permissions' => 'Berechtigungen',
			),
			'clearRadio' => array(
				'title' => 'Berechtigung entfernen',
				'name' => 'Berechtigung entfernen',
				'note' => 'Berechtigung f&uuml;r diese Gruppe vollst&auml;ndig entfernen',
			),
			'permissions' => array(
				'read' => 'Berechtigung: Lesen/Auflisten',
				'edit' => 'Berechtigung: Bearbeiten',
				'headLine' => '<b>Name der zu berechtigenden Gruppe</b>',
			),
		),
	),
	'class.MainView' => array(
		'page' => array(
			'init' => array(
				'name' => 'JudoIntranet'
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
				'label' => 'Neues Kennwort',
				'labelConfirm' => 'Kennwort wiederholen',
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
			),
		),
		'userData' => array(
			'rule' => array(
				'name.required' => 'Name muss ausgef&uuml;llt werden!',
				'email.required' => 'Emailadresse muss ausgef&uuml;llt werden!',
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
				'email' => 'Es muss eine g&uuml;ltige Emailadresse eingegeben werden!',
			),
			'label' => array(
				'name' => 'Name',
				'email' => 'Emailadresse',
			),
			'submit' => array(
				'value' => 'Speichern',
			),
			'validate' => array(
				'dataChanged' => 'Die Benutzerdaten wurden erfolgreich ge&auml;ndert.'
			),
			'caption' => array(
				'data' => 'Benutzerdaten &auml;ndern',
			),
		),
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
				'filter' => '<span>Filter:</span><br />',
				'public' => '<span>&Ouml;ffentlicher Zugriff:</span><br />',
				'publicYes' => 'Ja',
				'publicNo' => 'Nein',
			)
		)
	),
	'class.Field' => array(
		'global' => array(
			'info' => array(
				'help' => 'Hilfe',
			),
		),
		'element' => array(
			'rule' => array(
				'required.date' => 'Datum muss ausgew&auml;hlt werden!',
				'check.date' => 'Korrektes Datum muss ausgew&auml;hlt werden!',
				'required.text' => 'Feld muss ausgef&uuml;llt werden!',
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
				'required.checkbox' => 'Auswahlfeld muss angehakt werden!',
				'check.select' => 'Feld muss ausgew&auml;ht werden!',
				'check.hierselect' => 'Felder m&uuml;ssen ausgew&auml;ht werden!',
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
		'readDefaults' => array(
			'separator' => array(
				'defaults' => 'Vorgaben',
				'lastUsed' => 'zuletzt verwendet',
			),
		),
	),
	'class.AnnouncementView' => array(
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
			'delete' => array(
				'title' => 'Ausschreibung: Ausschreibung l&ouml;schen'
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
				'yes' => '  Ja  ',
				'cancel' => 'Abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie diesen Eintrag wirklich l&ouml;schen?',
				'done' => 'Der Eintrag wurde erfolgreich gel&ouml;scht.'
			),
			'title' => array(
				'cancel' => 'Bricht den L&ouml;schvorgang ab',
				'yes' => 'L&ouml;scht den Datensatz',
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
		'init' => array(
			'my' => array(
				'title' => 'Inventar: Eigene Objekte'
			),
			'default' => array(
				'title' => 'Inventar'
			),
			'listall' => array(
				'title' => 'Inventar: Listenansicht',
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
				'yes' => '  Ja  ',
				'cancel' => 'Abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie dieses Objekt wirklich zur&uuml;ckziehen?',
				'done' => 'Das Objekt wurde erfolgreich zur&uuml;ckgezogen.'
			),
			'title' => array(
				'cancel' => 'Bricht den Vorgang ab',
				'yes' => 'Zieht die &Uuml;bergabe zur&uuml;ck',
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
				'email' => 'Emailadresse',
				'year' => 'G&uuml;ltigkeitsjahr',
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
				'defaults' => 'Administration: Vorgaben verwalten',
				'user' => 'Administration: Benutzer und Rechte verwalten',
			)
		),
		'createTableLinks' => array(
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
		'listTableContent' => array(
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
		'deleteRow' => array(
			'form' => array(
				'yes' => '  Ja  '
			),
			'title' => array(
				'yes' => 'L&ouml;scht den Eintrag'
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
		'editRow' => array(
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
		'newRow' => array(
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
		'useradmin' => array(
			'caption' => array(
				'name' => 'Benutzer, Gruppen und Berechtigungen verwalten'
			),
			'tabs' => array(
				'tab.user' => 'Benutzerverwaltung',
				'tab.group' => 'Gruppenverwaltung',
				'tab.permission' => 'Berechtigungsverwaltung',
				'caption.user' => 'Benutzerverwaltung',
				'caption.group' => 'Gruppenverwaltung',
				'caption.permission' => 'Berechtigungsverwaltung',
			),
			'backLink' => array(
				'name' => '&lArr; Zur&uuml;ck...',
			),
		),
		'permissionContent' => array(
			'form' => array(
				'submitButton' => 'Speichern',
			),
			'naviList' => array(
				'captionName' => 'Navigationseintrag',
				'visible' => 'sichtbar',
				'notVisible' => 'nicht sichtbar',
			),
			'backLink' => array(
				'name' => '&lArr; Zur&uuml;ck...',
			),
			'message' => array(
				'success' => 'Berechtigungen erfolgreich gespeichert!'
			),
		),
		'addPermissionEntry' => array(
			'clearRadio' => array(
				'title' => 'Berechtigung entfernen',
				'name' => 'Berechtigung entfernen',
				'note' => 'Berechtigung f&uuml;r diesen Navigationseintrag vollst&auml;ndig entfernen',
			),
			'permissions' => array(
				'read' => 'Berechtigung: Lesen/Auflisten',
				'edit' => 'Berechtigung: Bearbeiten',
				'headLine' => '<b>Name der zu berechtigenden Gruppe</b>',
			),
		),
		'groupContent' => array(
			'form' => array(
				'submitButton.new' => 'Gruppe anlegen',
				'submitButton.edit' => 'Gruppe speichern',
				'groupName' => 'Gruppenname',
				'rule.required.name' => 'Der Gruppenname ist erforderlich!',
				'rule.regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
				'subgroupOf' => 'Obergruppe',
				'rule.required.subgroupOf' => 'Die Obergruppe ist erforderlich!',
				'delete.yes' => 'L&ouml;schen',
				'delete.cancel' => 'Abbrechen',
			),
			'groupList' => array(
				'captionName' => 'Gruppe bearbeiten',
				'captionDelete' => 'l&ouml;schen',
				'editGroup' => 'bearbeiten',
				'deleteGroup' => 'l&ouml;schen',
			),
			'message' => array(
				'new.success' => 'Neue Gruppe erfolgreich angelegt:',
				'edit.success' => 'Gruppe erfolgreich gespeichert:',
				'delete' => 'Wollen Sie diese Gruppe endg&uuml;ltig l&ouml;schen?',
				'delete.success' => 'Die Gruppe wurde erfolgreich gel&ouml;scht!',
			),
			'caption' => array(
				'edit' => 'Gruppe bearbeiten:',
			),
		),
		'userContent' => array(
			'form' => array(
				'submitButton.new' => 'Benutzer anlegen',
				'submitButton.edit' => 'Benutzer speichern',
				'userUsername' => 'Benutzername',
				'userPassword' => 'Passwort',
				'userPasswordConfirm' => 'Passwort wiederholen',
				'userName' => 'Name',
				'userEmail' => 'Emailadresse',
				'rule.required.username' => 'Der Benutzername ist erforderlich!',
				'rule.required.password' => 'Das Passwort ist erforderlich!',
				'rule.checkPasswd' => 'Die Eingaben m&uuml;ssen identisch sein!',
				'rule.required.name' => 'Der Name ist erforderlich!',
				'rule.required.email' => 'Die Emailadresse ist erforderlich!',
				'rule.email' => 'Es muss eine g&uuml;ltige Emailadresse eingegeben werden!',
				'rule.regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
				'groups' => 'Gruppen',
				'delete.yes' => 'L&ouml;schen',
				'delete.cancel' => 'Abbrechen',
			),
			'userList' => array(
				'captionName' => 'Benutzer bearbeiten',
				'captionDelete' => 'l&ouml;schen',
				'editUser' => 'bearbeiten',
				'deleteUser' => 'l&ouml;schen',
			),
			'message' => array(
				'new.success' => 'Neuen Benutzer erfolgreich angelegt:',
				'edit.success' => 'Benutzer erfolgreich gespeichert:',
				'delete' => 'Wollen Sie diesen Benutzer endg&uuml;ltig l&ouml;schen?',
				'delete.success' => 'Der Benutzer wurde erfolgreich gel&ouml;scht!',
			),
			'caption' => array(
				'edit' => 'Benutzer bearbeiten:',
			),
		),
		'global' => array(
			'info' => array(
				'help' => 'Hilfe'
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
				'newEntry' => 'Neues Protokoll',
				'edit' => 'Protokoll bearbeiten',
				'decisions' => 'Beschl&uuml;sse anzeigen',
				'delete' => 'Protokoll l&ouml;schen',
			),
			'init' => array(
				'name' => 'Protokolle'
			),
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
				'correctionFinished' => 'Korrektur abgeschlossen',
				'ProtShow' => 'Protokoll anzeigen',
				'ProtPDF' => 'Protokoll als PDF',
				'date' => 'Details anzeigen',
				'corrected' => 'Korrekturen vorhanden, &uuml;berpr&uuml;fen',
				'attach' => 'Datei(en) anh&auml;ngen',
				'filesAttached' => 'Anh&auml;nge vorhanden',
			),
			'alt' => array(
				'edit' => 'Protokoll bearbeiten',
				'delete' => 'Protokoll l&ouml;schen',
				'correct' => 'Protokoll korrigieren',
				'correctionFinished' => 'Korrektur abgeschlossen',
				'ProtShow' => 'Protokoll anzeigen',
				'ProtPDF' => 'Protokoll als PDF',
				'corrected' => 'Korrekturen vorhanden, &uuml;berpr&uuml;fen',
				'attach' => 'Datei(en) anh&auml;ngen',
				'filesAttached' => 'Anh&auml;nge vorhanden',
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
			),
			'text' => array(
				'attached' => '<b>Angeh&auml;ngte Dateien:</b>',
				'none' => '- keine -',
			),
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
		'newEntry' => array(
			'tmce' => array(
				'item' => 'TOP',
				'decision' => 'Beschluss',
				'messageElement' => 'Der Editor erscheint erst nach Auswahl der Vorlage!',
				'messageSelect' => 'Die Vorlage kann erst nach dem Speichern wieder angepasst werden!',
			)
		),
		'delete' => array(
			'form' => array(
				'yes' => '  Ja  '
			),
			'title' => array(
				'yes' => 'Protokoll l&ouml;schen',
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
				'newCaption' => 'Korrektur',
				'pageCaption' => 'Korrektur vergleichen',
			),
			'tmceClass' => array(
				'tmceItem' => 'TOP',
				'tmceDecision' => 'Beschluss',
			),
		),
		'global' => array(
			'info' => array(
				'help' => 'Hilfe',
			),
		),
		'decisions' => array(
			'listAllTitle' => array(
				'goTo' => 'Gehe zum Protokoll...',
			),
		),
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
				'fileListall' => 'Dateiliste',
				'fileListAdmin' => 'Aufgaben in der Dateiliste',
				'fileUpload' => 'Datei hochladen',
				'fieldFile' => 'Dateiauswahlfeld',
				'protocolListall' => 'Protokollliste',
				'protocolListAdmin' => 'Aufgaben in der Protokollliste',
				'fieldAllText' => 'Textfeld',
				'fieldPreset' => 'Vorlagenauswahl',
				'protocolNew' => 'Neues Protokoll',
				'protocolCorrectable' => 'Protokoll Status',
				'protocolCorrectors' => 'Protokoll Korrektoren',
				'protocolDecisions' => 'Beschl&uuml;sse',
				'protocolCorrect' => 'Protokoll korrigieren',
				'protocolDiff' => 'Korrekturen vergleichen',
				'protocolDifflist' => 'Korrekturen auflisten',
			),
			'message' => array(
				'errorIdNotExists' => '<p>Dieses Hilfe-Thema konnte nicht gefunden werden.</p>',
				'about' => '<p><b>JudoIntranet</b></p>
					<p>Author: Nils Bohrs<br />
					Version: r{$replace.version}<br />Lizenz: MIT</p>
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
				'fieldDate' => '<p><b>Format</b>: <i>dd.mm.yyyy</i><br />
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
					<li><img src="img/attachment_info.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; zeigt an, ob
					Dateien am Termin angeh&auml;ngt sind, ein Klick darauf &ouml;ffnet die Detailansicht des Termins.</li>
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
					<li><img src="img/attachment.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &Ouml;ffnet den Dialog um
					Dateien mit diesem Termin zu verkn&uuml;pfen.</li>
					<li><span class="Zebra_Form"><select class="control"><option>Ausschreibungsvorlage ausw&auml;hlen</option></select>
					<input class="submit" type="submit" value="+" /></span>&nbsp;:&nbsp; Um eine Ausschreibung mit Daten
					zu f&uuml;llen muss ihr eine Vorlage zugewiesen werden, die die zu verwendenden
					Felder und das Aussehen festlegt.<br />
					Das Zuweisen der Vorlage erfolgt durch das Ausw&auml;hlen der Vorlage aus dem
					einzeiligen Auswahlfeld und anschlie&szlig;endem Zuf&uuml;gen durch den Button.</li>
					</ul>
					<p>Das Symbol <img src="img/public.png" alt="Bild im Hilfetext" /> hinter dem Namen eines Termins zeigt den
					eingeloggten Benutzern an, ob der Termin &ouml;ffentlich ist.</p>',
				'calendarListSortlinks' => '<p>Die eingeblendete Filterauswahl besteht aus drei
					Bereichen:</p>
					<ul>
					<li>Der obere Bereich enth&auml;lt die Buttons zum Zur&uuml;cksetzen der
					gew&auml;hlten Filter:<br />"<span class="underline">Alle Filter zur&uuml;cksetzen</span>" zeigt wieder die
					komplette Liste an, ohne Filter.<br />"<span class="underline">Datumsfilter zur&uuml;cksetzen</span>"
					beh&auml;lt die ausgew&auml;hlte Gruppe bei und entfernt nur den Zeitraum.<br />
					"<span class="underline">Gruppenfilter zur&uuml;cksetzen</span>"beh&auml;lt den gew&auml;hlten Zeitraum und
					entfernt nur die Gruppe</li>
					<li>Der erste Reiter enth&auml;lt die Buttons zur Auswahl eines festgelegten
					Zeitraums, der die Liste auf die Termine einschr&auml;nkt, die in diesem Zeitraum
					stattfinden.</li>
					<li>Der zweite Reiter enth&auml;lt die Buttons zur Auswahl nach festgelegten
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
				'fileListall' => '<p>Diese Seite listet alle Dateien auf getrennt nach hochgeladenen und zwischengespeicherten.
					Ein Klick auf den unterstrichenen Namen einer Datei &ouml;ffnet deren Details.</p>
					<ul>
					<li><img src="img/file_download.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; l&auml;dt die Datei herunter, der Browser
					&ouml;ffnet den Auswahldialog, der zur Entscheidung auffordert, was mit der Datei geschehen soll.</li>
					</ul>',
				'fileListAdmin' => '<p>Die Administration einer Datei erfolgt &uuml;ber folgende Buttons:</p>
					<ul>
					<li><img src="img/file_edit.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet die Datei im
					Bearbeitungsmodus, hier k&ouml;nnen die einzelnen Felder des Datensatzes ge&auml;ndert werden.</li>
					<li><img src="img/file_delete.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; l&ouml;scht die Datei nach
					R&uuml;ckfrage endg&uuml;ltig.</li>
					</ul>',
				'fileUpload' => '<p>Formular zum Hochladen einer neuen Datei. Alle Felder, die
					mit einem roten <span class="required">*</span> gekennzeichnet sind, m&uuml;ssen ausgef&uuml;llt werden, das
					Formular l&auml;sst sich sonst nicht speichern.<br />Die erlaubten Zeichen
					werden in der Hilfe des jeweiligen Feldes erl&auml;tert, bei Fehleingaben
					wird eine entsprechende Meldung ausgegeben.</p>',
				'fieldFile' => '<p><b>Format</b>: <i>Datei-Upload</i></p>
					<p>Der Button "Durchsuchen..." &ouml;ffnet den Dialog zur Auswahl einer Datei vom lokalen Rechner. Durch
					einen Fehler in einer verwendeten Komponente wird die Datei nach dem Ausw&auml;hlen nicht mehr angezeigt,
					um eine falsch ausgew&auml;hlte Datei zu korrigieren, ist das Neuladen der Seite erforderlich.</p>',
				'protocolListall' => '<p>Diese Seite listet alle Protokolle. Ein Klick auf den unterstrichenen
					Namen des Protokolls &ouml;ffnet dessen Details. Zur Ansicht des Protokolls stehen folgende Buttons zur
					Verf&uuml;gung:</p>
					<ul>
					<li><img src="img/prot_details.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet
					das Protokoll als Seitenansicht eingebettet in diese Seite, Drucken ist
					in dieser Ansicht nicht m&ouml;glich.</li>
					<li><img src="img/prot_pdf.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet das
					Protokoll direkt als PDF (ein entsprechendes Programm zur Anzeige
					wie der AdobeReader vorausgesetzt), in dieser Ansicht kann die Ausschreibung
					gedruckt oder gespeichert werden.</li>
					<li><img src="img/attachment_info.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; zeigt an, ob
					Dateien am Protokoll angeh&auml;ngt sind, ein Klick darauf &ouml;ffnet die Detailansicht des Protokolls.</li>
					</ul>',
				'protocolListAdmin' => '<p>Die Administration eines Protokolls erfolgt &uuml;ber folgende Buttons
					(die administrativen Aufgaben k&ouml;nnen derzeit nur vom Ersteller des Protokolls vorgenommen werden):</p>
					<ul>
					<li><img src="img/prot_edit.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet das Protokoll im
					Bearbeitungsmodus, hier k&ouml;nnen die einzelnen Felder des Datensatzes
					ge&auml;ndert werden.</li>
					<li><img src="img/prot_delete.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; l&ouml;scht das Protokoll nach
					R&uuml;ckfrage endg&uuml;ltig.</li>
					<li><img src="img/prot_correct.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet das Protokoll zur
					Korrektur, nur sichtbar, wenn man als Korrektor eingetragen wurde und sich das Protokoll im Status "korrigierbar"
					befindet.</li>
					<li><img src="img/prot_corrected.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet die Liste der Korrekturen
					um diese zu pr&uuml;fen und ein zu arbeiten. Der Button ist nur sichtbar, wenn man Ersteller des Protokolls ist
					und Korrekturen existieren.</li>
					<li><img src="img/attachment.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet eine Seite auf der Dateien
					mit diesem Protokoll verkn&uuml;pft werden k&ouml;nnen.</li>
					</ul>',
				'fieldAllText' => '<p><b>Format</b>: <i>ein-/mehrzeiliger Text</i><br />
					<b>Standardwert</b>: <i>keiner</i><br />
					<b>Erlaubte Zeichen</b>: <i>"'.
					htmlspecialchars(self::getGc()->get_config('textarea.desc')).'"</i></p>
					<p>In dieses Feld kann ein beliebiger Text eingetragen werden, entsprechend den o.a. erlaubten Zeichen.</p>',
				'fieldPreset' => '<p><b>Format</b>: <i>einzeiliges Auswahlfeld</i></p>
					Hier ist die Vorlage aus zu w&auml;hlen, in der der Datensatz als PDF angezeitg wird.<p>',
				'protocolNew' => '<p>Formular zur Erstellung eines neuen Protokolls. Alle Felder, die
					mit einem roten <span class="required">*</span> gekennzeichnet sind, m&uuml;ssen ausgef&uuml;llt werden, das
					Formular l&auml;sst sich sonst nicht speichern.<br />Die erlaubten Zeichen
					werden in der Hilfe des jeweiligen Feldes erl&auml;tert, bei Fehleingaben
					wird eine entsprechende Meldung ausgegeben.</p>',
				'protocolCorrectable' => '<p>Der Status eines Protokolls besteht aus drei Auswahlm&ouml;glichkeiten:</p>
					<ul>
					<li><b>in Bearbeitung</b>: in diesem Status kann nur der Ersteller des Protokolls es bearbeiten, f&uuml;r
					alle anderen ist es nicht sichtbar.</li>
					<li><b>Korrekturfreigabe</b>: in diesem Status kann das Protokoll von den "Korrektoren" korrigiert werden,
					es ist dann nur f&uuml;r den Ersteller und die Korrektoren sichtbar mit jeweils unterschiedlichen Aufgaben.</li>
					<li><b>ver&ouml;ffentlicht</b>: in diesem Status ist das Protokoll fertig und sichtbar f&uuml;r alle die
					berechtigt sind es zu sehen (angemeldete Benutzer, oder alle).</li>
					</ul>',
				'protocolCorrectors' => '<p><b>Format</b>: <i>Mehrfachauswahlfeld</i></p>
					<p>Das Feld listet alle Benutzer auf, die sich anmelden k&ouml;nnen, hier kann ein oder mehrere Benutzer ausgew&auml;hlt
					werden, die das Protokoll korrigieren d&uuml;rfen. Die Auswahl mehrerer Werte ist
					durch Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich, das
					Entfernen der Auswahl oder einzelner Werte ist ebenfalls mittels
					Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich.</p>',
				'protocolDecisions' => '<p>Beschl&uuml;sse werden hier in Tabellen angezeigt:</p>
					<table class="content protocol_showdecisions">
						<tr class="head">
							<td class="date">
								<b>---Datum---</b>
							</td>
							<td class="type">
								<b>---Sitzungstyp---</b>
							</td>
							<td>
								<b>---Ort---</b>
							</td>
						</tr>
						<tr class="decision even">
							<td colspan="3">
								---Beschlusstext 1. Beschluss---	
							</td>
						</tr>
						<tr class="decision odd">
							<td colspan="3">
								---Beschlusstext 2. Beschluss---	
							</td>
						</tr>
					</table>
					<p>Wenn diese Seite aus einem einzelnen Protokoll heraus aufgerufen wird, werden nur die
					Beschl&uuml;sse dieses Protokolls angezeigt. Wenn man den Men&uuml;punkt "Alle Beschl&uuml;sse anzeigen"
					unter "Protokolle" w&auml;hlt, wird pro Protokoll eine Tabelle mit den jeweiligen Beschl&uuml;ssen angezeigt.</p>',
				'protocolCorrect' => '<p>Zur Korrektur wird der Original-Protokolltext im Editor angezeigt und die Korrektoren
					d&uuml;rfen ihre Korrekturen direkt im Text vornehmen, so dass das Protokoll dem Stand entspricht, den sie
					f&uuml;r den korrekten halten.</p>',
				'protocolDiff' => '<p>Der obere Teil der Ansicht zeigt auf der rechten Seite den Text des urspr&uuml;nglichen Protokolls
					und auf der linken Seite den Text der Korrektur zeilenweise gegen&uuml;ber gestellt. Die Formatierungen (TOP oder Beschluss)
					werden im direkten Vergleich durch die Texte "TOP:" und "Beschluss:" ersetzt. Wenn eine Zeile in der Vergleichsansicht
					<span style="background-color: #fd8;">rot</span>
					eingef&auml;rbt ist, wurde in diese Zeile etwas ver&auml;ndert (hinzugef&uuml;gt oder entfernt), wenn die Zeile
					<span style="background-color: #9e9;">gr&uuml;n</span>
					eingef&auml;rbt ist, wurde diese Zeile komplett hinzugef&uuml;gt.</p>
					<p>Der untere Teil der Ansicht enth&auml;lt den Editor zum Bearbeiten des urspr&uuml;nglichen Protokolls.</p>
					<p>Der Haken "Korrektur abgeschlossen" in der letzten Zeile markiert die Korrektur als bearbeitet, damit wird
					sie in der Liste der Korrekturen markiert (&nbsp;<img src="img/done.png" alt="Bild im Hilfetext" />&nbsp;)
					und der Korrektor kann seine Korrektur nicht mehr bearbeiten.</p>',
				'protocolDifflist' => '<p>Hier werden alle Korrekturen zu diesem Protokoll aufgelistet, es werden der Name des Korrektors
					und das Datum der Erstellung angezeigt. Der Klick auf eine Korrektur, &ouml;ffnet die Vergleichsansicht.</p>
					<p>Wenn hinter der Korrektur das Symbol <img src="img/done.png" alt="Bild im Hilfetext" /> angezeigt wird,
					wurde diese Korrektur als "schon bearbeitet" markiert.</p>',
			),
		),
		'getMessage' => array(
			'templateValues' => array(
				'imgTitle' => 'Hilfe',
			),
		),
	),
	'class.Group' => array(
		'fakePublic' => array(
			'public' => array(
				'name' => '&Ouml;ffentlicher Zugriff',
			),
		),
	),
	'class.Navi' => array(
		'item' => array(
			'name' => array(
				'mainPage' => 'Startseite',
				'mainPage.login' => 'Login',
				'mainPage.logout' => 'Logout',
				'calendarPage' => 'Kalender',
				'calendarPage.new' => 'Neuer Eintrag',
				'calendarPage.listall' => 'Listenansicht',
				'calendarPage.details' => 'Detailansicht',
				'calendarPage.edit' => 'Eintrag editieren',
				'calendarPage.delete' => 'Eintrag l&ouml;schen',
				'inventoryPage' => 'Inventar',
				'inventoryPage.my' => 'Eigene verwalten',
				'inventoryPage.listall' => 'Listenansicht',
				'inventoryPage.give' => 'Objekt abgeben',
				'inventoryPage.take' => 'Objekt annehmen',
				'inventoryPage.cancel' => 'Objekt zur&uuml;ckziehen',
				'inventoryPage.details' => 'Details',
				'inventoryPage.movement' => '&Uuml;bergaben',
				'announcementPage' => 'Ausschreibungen',
				'announcementPage.listall' => 'Listenansicht',
				'announcementPage.new' => 'Neuen Eintrag erstellen',
				'announcementPage.edit' => 'Eintrag bearbeiten',
				'announcementPage.delete' => 'Eintrag l&ouml;schen',
				'announcementPage.details' => 'Details',
				'announcementPage.topdf' => 'Eintrag als PDF',
				'protocolPage' => 'Protokolle',
				'protocolPage.listall' => 'Listenansicht',
				'protocolPage.new' => 'Neues Protokoll erstellen',
				'protocolPage.details' => 'Details',
				'protocolPage.edit' => 'Protokoll bearbeiten',
				'protocolPage.show' => 'Protokoll anzeigen',
				'protocolPage.topdf' => 'Protokoll als PDF',
				'protocolPage.delete' => 'Protokoll l&ouml;schen',
				'protocolPage.correct' => 'Protokoll korrigieren',
				'protocolPage.showdecisions' => 'Alle Beschl&uuml;sse anzeigen',
				'administrationPage' => 'Administration',
				'administrationPage.field' => 'Ben. Tabellen verwalten',
				'administrationPage.defaults' => 'Vorgaben verwalten',
				'administrationPage.useradmin' => 'Benutzer/Gruppen/Rechte',
				'filePage' => 'Dateien',
				'filePage.listall' => 'Dateien auflisten',
				'filePage.details' => 'Datei-Details',
				'filePage.edit' => 'Datei bearbeiten',
				'filePage.delete' => 'Datei l&ouml;schen',
				'filePage.upload' => 'Datei hochladen',
				'filePage.cached' => 'Datei herunterladen',
				'filePage.attach' => 'Datei anh&auml;ngen',
			),
		),
	),
	'zebraTemplate' => array(
		'tabs' => array(
			'name' => array(
				'elements' => 'Daten',
				'permissions' => 'Berechtigungen',
			),
		),
	),
	'class.FileView' => array(
		'defaultContent' => array(
			'headline' => array(
				'text' => 'Dateien'
			)
		),
		'init' => array(
			'title' => array(
				'listall' => 'Dateien: Listenansicht',
				'details' => 'Dateien: Details',
				'default' => 'Dateien',
				'upload' => 'Dateien: Datei hochladen',
				'edit' => 'Dateien: Datei bearbeiten',
				'delete' => 'Dateien: Datei l&ouml;schen',
				'download' => 'Dateien: Datei herunterladen',
				'cached' => 'Dateien: Datei herunterladen',
				'attach' => 'Dateien: Datei anh&auml;ngen',
			),
			'Error' => array(
				'NotAuthorized' => 'FEHLER - Nicht berechtigt'
			),
		),
		'page' => array(
			'caption' => array(
				'listall' => 'Listenansicht',
				'details' => 'Details',
				'download' => 'Datei herunterladen',
				'upload' => 'Datei hochladen',
				'edit' => 'Datei bearbeiten',
				'delete' => 'Datei l&ouml;schen',
				'attach' => 'Datei anh&auml;ngen',
			),
			'init' => array(
				'name' => 'Dateien'
			),
		),
		'listall' => array(
			'TH' => array(
				'name' => 'Name',
				'filetype' => 'Dateityp',
				'filename' => 'Dateiname',
				'show' => 'Ansehen',
				'admin' => 'Aufgaben'
			),
			'title' => array(
				'edit' => 'Datei bearbeiten',
				'delete' => 'Datei l&ouml;schen',
				'name' => 'Details',
				'filename' => ' herunterladen',
			),
			'alt' => array(
				'edit' => 'Datei bearbeiten',
				'delete' => 'Datei l&ouml;schen',
			),
			'tabTitle' => array(
				'download' => 'Hochgeladen',
				'cached' => 'Zwischengespeichert',
			),
			'tableName' => array(
				'calendar' => 'Ausschreibungen',
				'protocol' => 'Protokolle',
			),
		),
		'details' => array(
			'back' => array(
				'title' => 'Zur&uuml;ck',
				'name' => 'Zur&uuml;ck',
			),
			'download' => array(
				'title' => 'Herunterladen',
				'name' => 'Herunterladen',
			),
		),
		'delete' => array(
			'form' => array(
				'yes' => '  Ja  '
			),
			'title' => array(
				'yes' => 'Datei l&ouml;schen',
			),
			'cancel' => array(
				'title' => 'Bricht den L&ouml;schvorgang ab',
				'form' => 'Abbrechen'
			),
			'message' => array(
				'confirm' => 'Wollen Sie diese Datei wirklich l&ouml;schen?',
				'done' => 'Die Datei wurde erfolgreich gel&ouml;scht.'
			),
		),
		'attach' => array(
			'file' => array(
				'title' => 'Datei anh&auml;ngen an:',
			),
			'section' => array(
				'download' => 'Hochgeladen',
				'cached' => 'Zwischengespeichert',
			),
			'tableName' => array(
				'calendar' => 'Ausschreibungen',
				'protocol' => 'Protokolle',
			),
			'text' => array(
				'attached' => 'Angeh&auml;ngte Dateien:',
				'none' => '- keine -',
			),
		),
		'entry' => array(
			'form' => array(
				'requiredNote' => '<span class="required">*</span> erforderliches Feld',
				'name' => 'Name',
				'content' => 'Datei ausw&auml;hlen',
				'submitButton' => 'Hochladen',
				'public' => '&Ouml;ffentlicher Zugriff',
				'submitButton.edit' => 'Speichern',
			),
			'rule' => array(
				'required.name' => 'Name muss eingetragen werden!',
				'file.allowedFileTypes' => 'Es k&ouml;nnen nur Dateien mit folgenden Erweiterungen hochgeladen werden!',
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
				'file.upload' => 'Die Datei konnte nicht hochgeladen werden!',
			)
		),
		'global' => array(
			'info' => array(
				'help' => 'Hilfe',
			),
		),
	),
	'class.File' => array(
		'getFileTypeAs' => array(
			'name' => array(
				'text_plain' => 'Textdokument',
				'application_pdf' => 'PDF-Dokument',
			),
		),
		'details' => array(
			'data' => array(
				'name' => 'Name',
				'filetype' => 'Dateityp',
				'filename' => 'Dateiname',
			),
		),
	),
);


?>
