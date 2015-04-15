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
 * 				'<text key>' => '<translated text>'
 * 				.
 * 				.
 * 				.
 * 		)
 */

$lang = array(
	/*
	 * error messages
	 */
	'ERROR' => 'FEHLER',
	'Error: get request contains invalid characters' => 'Der Link enth&auml;lt ung&uuml;ltige Zeichen, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: post request contains invalid characters' => 'Die Anfrage enth&auml;lt ung&uuml;ltige Zeichen, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: field invalid chars' => 'Dieses Feld enh&auml;lt ung&uuml;ltige Zeichen',
	'Error: cannot load template' => 'Ein ben&ouml;tigtes Template kann nicht geladen werden, bitte wenden Sie sich an den Systembetreuer.',
	'Error: link unknown param' => 'Der Link enth&auml;lt einen ung&uuml;ltigen Parameter, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: cannot load navi' => 'Ein ben&ouml;tigtes Navigationselement kann nicht geladen werden, bitte wenden Sie sich an den Systembetreuer.',
	'Error: not authorized' => 'Sie sind nicht berechtigt diese Seite an zu zeigen, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>
										{if !$object->getUser()->get_loggedin()} oder <a href="index.php?id=login{Object::afterLogin(\'&amp;\')}" title="anmelden">melden Sie sich an</a>{/if}.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'DEMO-MODE' => 'DEMO-MODUS',
	'Error: demo mode not authorized' => 'Diese Seite l&auml;uft im Demo-Modus, einige Funktionen sind deaktiviert:<br />
										Alle Funktionen, die die Anmeldung der Benutzer ver&auml;ndern oder Eingriffe in die Systemkonfiguration erm&ouml;glichen sind nicht verf&uuml;gbar<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: not saved in database' => 'Der Eintrag konnte nicht gespeichert werden, bitte probieren Sie es erneut oder wenden sich an Ihre Systembetreuer.',
	'Error: calendar entry not exists' => 'Der Termin existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: not owner of object' => 'Sie sind nicht Eigent&uuml;mer dieses Objekts, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: object not given' => 'Sie haben dieses Objekts nicht abgegeben, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: object not given to' => 'Dieses Objekts wurde nicht abgegeben, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: missing params' => 'Es fehlen Parameter, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: wrong params' => 'Es wurden falsche Parameter &uuml;bergeben, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: no announcement for calendar entry' => 'F&uuml;r diesen Termin wurde keine Ausschreibung angelegt, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: table not exists' => 'Diese Tabelle existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: table row not exists' => 'Diese Tabellenzeile existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: database error' => 'Es ist ein Fehler bei einer Datenbank-Abfrage aufgetreten, bitte geben Sie die nachfolgenden Informationen an den Systembetreuer weiter:<br />',
	'Error: header already sent before download' => 'Beim Zusammenstellen der Daten zum Download ist ein Fehler aufgetreten.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet oder wiederholt auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: object not exists' => 'Das angegebene Objekt existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: unknown action' => 'Die angegebene Aktion ist nicht bekannt, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: navi entry not exists' => 'Der Navigationseintrag existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: group not exists' => 'Die Gruppe existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: user not exists' => 'Der Benutzer existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: object in use' => 'Das Objekt ist in Verwendung und kann nicht gel&ouml;scht/bearbeitet werden, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: file not exists' => 'Die angegebene Datei existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: result import failed' => 'Beim importieren der Ergebnisdatei ist ein Fehler aufgetreten, folgende Gr&uuml;nde
										k&ouml;nnen in u.a. Meldung angegeben sein:<br />
										<ul>
										<li>"Second step not possible; Session removed, please try again."&nbsp;-&nbsp;Bitte
										den Import erneut starten.</li>
										<li>"No valid data for import module "&lt;Import-Modul-Name&gt;"&nbsp;-&nbsp;Das Datenformat
										der Ergebnisdatei stimmt nicht mir dem ausgew&auml;hlten Modul &uuml;berein, bitte die
										Ergebnisdatei pr&uuml;fen</li>
										</ul>
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: result not exists' => 'Dieses Ergebnis existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: unknown task' => 'Diese Aufgabe ist unbekannt, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: result not possible for future calendar entries!' => 'Ergebnisse k&ouml;nnen nicht an zuk&uuml;nftige Termine angeh&auml;ngt werden, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: protocol entry not exists' => 'Das Protokoll existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: holiday function is not callable' => 'Eine Funktion um einen Feiertag zu bestimmen ist nicht aufrufbar, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: holiday calculation error' => 'Ein Eintrag in den Einstellungen der Feiertage ist nicht berechnbar, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: holiday year not valid' => 'Das gew&auml;hlte Jahr ist nicht g&uuml;ltig oder zu weit in der Zukunft, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler weiterhin auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	'Error: tribute not exists' => 'Diese Ehrung existiert nicht, wenn Sie die Adresse manuell eingegeben haben, bitte &uuml;berpr&uuml;fen.<br />
										Wenn Sie einem Link gefolgt sind, versuchen Sie es bitte erneut von der <a href="javascript:history.back(1)">vorherigen Seite</a>.<br />
										Wenn der Fehler unerwartet auftritt, wenden Sie sich bitte an den Systembetreuer.<br />',
	/*
	 * API error messages
	 */
	'API call failed [not signed]' => 'API-Aufruf fehlgeschlagen [keine Signatur]',
	'API call failed [timeout] #?reloadLink' => 'API-Aufruf fehlgeschlagen [Timeout, bitte #?reloadLink]',
	'API call failed [unknown apiClass]' => 'API-Aufruf fehlgeschlagen [unbekannte Klasse]',
	'API call failed [unknown provider]' => 'API-Aufruf fehlgeschlagen [unbekannter Provider]',
	'API call failed [missing post data]' => 'API-Aufruf fehlgeschlagen [POST-Daten fehlen]',
	'API call failed [not authorized]' => 'API-Aufruf fehlgeschlagen [keine Berechtigung]',
	'API call failed [no listing data]' => 'API-Aufruf fehlgeschlagen [kein Daten erhalten]',
	
	/*
	 * ************************
	 */
	'date' => 'Datum',
	'event' => 'Veranstaltung',
	'show' => 'Ansehen',
	'tasks' => 'Aufgaben',
	'edit' => 'bearbeiten',
	'delete' => 'l&ouml;schen',
	'edit announcement' => 'Ausschreibung bearbeiten',
	'delete announcement' => 'Ausschreibung l&ouml;schen',
	'show announcement' => 'Ausschreibung anzeigen',
	'show announcement draft' => 'Ausschreibung anzeigen (ENTWURF)',
	'show announcement pdf' => 'Ausschreibung als PDF anzeigen',
	'show announcement pdf draft' => 'Ausschreibung als PDF anzeigen (ENTWURF)',
	'attach file(s)' => 'Datei(en) anh&auml;ngen',
	'existing attachments' => 'Anh&auml;nge vorhanden',
	'public' => '&Ouml;ffentlich',
	'edits entry' => 'bearbeitet diesen Eintrag',
	'deletes entry' => 'l&ouml;scht diesen Eintrag',
	'edits announcement' => 'bearbeitet die Ausschreibung',
	'deletes announcement' => 'l&ouml;scht die Ausschreibung',
	'calendar: listall' => 'Kalender: Listenansicht',
	'calendar' => 'Kalender',
	'calendar: new entry' => 'Kalender: Neuer Eintrag',
	'calendar: details' => 'Kalender: Termindetails',
	'calendar: edit' => 'Kalender: Termin bearbeiten',
	'calendar: delete' => 'Kalender: Termin l&ouml;schen',
	'calendar: calendar' => 'Kalender: Kalenderansicht',
	'calendar: schedule' => 'Kalender: Terminplan',
	'calendarview' => 'Kalenderansicht',
	'tomorrow' => 'Morgen',
	'next week' => 'n&auml;chste Woche',
	'next two weeks' => 'n&auml;chste zwei Wochen',
	'next month' => 'n&auml;chster Monat',
	'next halfyear' => 'n&auml;chstes halbes Jahr',
	'next year' => 'n&auml;chstes Jahr',
	'reset all filter' => 'Alle Filter zur&uuml;cksetzen',
	'reset date filter' => 'Datumsfilter zur&uuml;cksetzen',
	'reset group filter' => 'Gruppenfilter zur&uuml;cksetzen',
	'show filter' => 'Filter einblenden',
	'close filter' => 'Filterdialog schlie&szlig;en',
	'reset filter' => 'Filter zur&uuml;cksetzen',
	'date filter' => 'Datumsfilter',
	'group filter' => 'Gruppenfilter',
	'select filter' => 'Filter ausw&auml;hlen',
	'select date' => 'Zeitraum ausw&auml;hlen',
	'select group' => 'Gruppe ausw&auml;hlen',
	'save' => 'Speichern',
	'name' => 'Name',
	'shortname' => 'Kurzbezeichnung',
	'event type' => 'Veranstaltungstyp',
	'filtering groups (multi select)' => 'Filterbare Gruppen (mehrfache Auswahl: &lt;STRG&gt; gedr&uuml;ckt halten und Gruppen ausw&auml;hlen)',
	'content/description' => 'Inhalt/Beschreibung',
	'announcement' => 'Ausschreibung',
	'public access' => '&Ouml;ffentlicher Zugriff',
	'required date' => 'Datum muss ausgew&auml;hlt werden!',
	'check date' => 'Korrektes Datum muss ausgew&auml;hlt werden!',
	'required name' => 'Name muss eingetragen werden!',
	'required type' => 'Typ muss ausgew&auml;hlt werden!',
	'required field' => 'Feld muss ausgew&auml;ht werden!',
	'allowed chars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
	'yes' => '  Ja  ',
	'cancel' => 'Abbrechen',
	'you really want to delete' => 'Wollen Sie diesen Eintrag wirklich l&ouml;schen?',
	'entry successful deleted' => 'Der Eintrag wurde erfolgreich gel&ouml;scht.',
	'cancels deletion' => 'Bricht den L&ouml;schvorgang ab',
	'deletes entry' => 'L&ouml;scht den Eintrag',
	'+' => '+',
	'select template' => 'Bitte eine Vorlage ausw&auml;hlen',
	'edit entry' => 'Editiere Eintrag',
	'listall' => 'Listenansicht',
	'create new entry' => 'Neuen Eintrag erstellen',
	'details' => 'Details',
	'delete entry' => 'L&ouml;sche Eintrag',
	'help' => 'Hilfe',
	'attached files' => '<b>Angeh&auml;ngte Dateien:</b>',
	'<b>attached files</b>' => '<b>Angeh&auml;ngte Dateien:</b>',
	'- none -' => '- keine -',
	'JudoIntranet' => 'JudoIntranet',
	'not loggedin' => 'Nicht angemeldet.',
	'logged in as' => 'Angemeldet als:',
	'toggle usersettings' => 'Benutzereinstellungen ein-/ausblenden',
	'change password' => 'Kennwort &auml;ndern',
	'change usersettings' => 'Benutzerdaten &auml;ndern',
	'close' => 'Schlie&szlig;en',
	'remove permissions' => 'Berechtigung entfernen',
	'completely remove permissions from group' => 'Berechtigung f&uuml;r diese Gruppe vollst&auml;ndig entfernen',
	'permission: read/list' => 'Berechtigung: Lesen/Auflisten',
	'permission: edit' => 'Berechtigung: Bearbeiten',
	'<b>name of permitted group</b>' => '<b>Name der zu berechtigenden Gruppe</b>',
	'homepage' => 'Startseite',
	'usersettings' => 'Benutzereinstellungen',
	'username' => 'Benutzername',
	'password' => 'Passwort',
	'log on' => 'Anmelden',
	'logout' => 'Logout',
	'logout successful' => 'Sie haben sich erfolgreich abgemeldet.',
	'required username' => 'Der Benutzername darf nicht leer sein!',
	'required password' => 'Das Passwort darf nicht leer sein!',
	'username not exists' => 'Der angegebene Benutzername existiert nicht.',
	'user not active' => 'Dieser Benutzer ist nicht aktiv.',
	'wrong password' => 'Falsches Passwort.',
	'usersettings for' => 'Benutzereinstellungen f&uuml;r',
	'new password' => 'Neues Kennwort',
	'repeat password' => 'Kennwort wiederholen',
	'change password' => 'Kennwort ändern',
	'required password' => 'Kennwort muss ausgef&uuml;llt werden!',
	'has to be the same' => 'Die Eingaben m&uuml;ssen identisch sein!',
	'password changed successful' => 'Das Kennwort wurde erfolgreich ge&auml;ndert.',
	'name required' => 'Name muss ausgef&uuml;llt werden!',
	'email required' => 'Emailadresse muss ausgef&uuml;llt werden!',
	'valid email' => 'Es muss eine g&uuml;ltige Emailadresse eingegeben werden!',
	'email address' => 'Emailadresse',
	'usersettings changed successful' => 'Die Benutzerdaten wurden erfolgreich ge&auml;ndert.',
	'successfully logged off' => 'Sie haben sich erfolgreich abgemeldet.',
	'please log on' => 'Bitte einloggen',
	'any (public access)' => 'Alle (&ouml;ffentlicher Zugriff)',
	'competition/championship' => 'Turnier/Meisterschaft',
	'course' => 'Lehrgang',
	'event<br />' => '<span>Veranstaltung:</span><br />',
	'shortname<br />' => '<span>Kurzname:</span><br />',
	'date<br />' => '<span>Datum:</span><br />',
	'type<br />' => '<span>Art:</span><br />',
	'description<br />' => '<span>Beschreibung:</span><br />',
	'filter<br />' => '<span>Filter:</span><br />',
	'public access<br />' => '<span>&Ouml;ffentlicher Zugriff:</span><br />',
	'yes' => 'Ja',
	'no' => 'Nein',
	'required checkbox' => 'Auswahlfeld muss angehakt werden!',
	'required fields' => 'Felder m&uuml;ssen ausgew&auml;ht werden!',
	'presets' => 'Vorgaben',
	'last used' => 'zuletzt verwendet',
	'announcement: new entry' => 'Ausschreibung: Neuer Eintrag',
	'announcement: listall' => 'Ausschreibung: Listenansicht',
	'announcement: edit' => 'Ausschreibung: Bearbeiten',
	'announcement: details' => 'Ausschreibung: Detailansicht',
	'announcement: pdf' => 'Ausschreibung: als PDF',
	'announcement: delete' => 'Ausschreibung: Ausschreibung l&ouml;schen',
	'announcement: refreshpdf' => 'Ausschreibung: PDF erneuern',
	'deletes the entry' => 'L&ouml;scht den Datensatz',
	'inventory: own objects' => 'Inventar: Eigene Objekte',
	'inventory' => 'Inventar',
	'inventory: listall' => 'Inventar: Listenansicht',
	'inventory: give object' => 'Inventar: Objekt abgeben',
	'inventory: take object' => 'Inventar: Objekt annehmen',
	'inventory: cancel give' => 'Inventar: Objekt zur&uuml;ckziehen',
	'inventory: details' => 'Inventar: Details',
	'inventory: transactions' => 'Inventar: &Uuml;bergaben',
	'object' => 'Objekt',
	'inventory number' => 'Inventarnummer',
	'give object' => 'Objekt abgeben',
	'take object' => 'Objekt annehmen',
	'cancel give object' => 'Objekt zur&uuml;ckziehen',
	'give away' => 'abgeben',
	'take' => 'annehmen',
	'cancel give' => 'zur&uuml;ckziehen',
	'<empty>' => '',
	'give to' => ' abgeben an',
	'require to check given accessories' => 'Bitte unbedingt das zu &uuml;bergebende Zubeh&ouml;r anhaken, sonst muss davon ausgegangen werden, dass es einbehalten wurde und kostenpflichtig ersetzt werden muss!',
	'given to' => ' abgegeben an ',
	'accessories to be given' => 'Zu &uuml;bergebendes Zubeh&ouml;r:',
	'taken' => '&uuml;bernommen',
	'require to check taken accessories' => 'Bitte unbedingt das &uuml;bernommene Zubeh&ouml;r anhaken, sonst muss davon ausgegangen werden, dass es einbehalten wurde und kostenpflichtig ersetzt werden muss!',
	'taken accessories' => '&Uuml;bernommenes Zubeh&ouml;r:',
	'taken from' => '&Uuml;bernommen von',
	'required taking user' => 'Annehmender Benutzer muss ausgew&auml;hlt werden!',
	'you really want to cancel give' => 'Wollen Sie dieses Objekt wirklich zur&uuml;ckziehen?',
	'successful cancel give' => 'Das Objekt wurde erfolgreich zur&uuml;ckgezogen.',
	'cancels the transaction' => 'Bricht den Vorgang ab',
	'cancels give' => 'Zieht die &Uuml;bergabe zur&uuml;ck',
	'owner' => 'Besitzer',
	'state' => 'Status',
	'to be given to' => 'abzugeben an',
	'accessories' => 'Zubeh&ouml;r',
	'show transactions' => '&Uuml;bergaben anzeigen',
	'taken from' => 'Angenommen von',
	'given from' => 'Abgegeben von',
	'transaction for' => '&Uuml;bergabe von ',
	'on' => 'am ',
	'back' => 'zur&uuml;ck',
	'back to list' => 'zur&uuml;ck zur Liste',
	'manage own objects' => 'Eigene Objekte verwalten',
	'take object' => 'Objekt &uuml;bernehmen',
	'cancel action' => 'Aktion abbrechen',
	'administration' => 'Administration',
	'userdefined tables' => 'Benutzerdefinierte Tabellen',
	'default fields' => 'Vorgegebene Felder',
	'usertable row name' => 'Name/Anzeigename',
	'usertable row category' => 'Kategorie',
	'usertable row value' => 'Wert',
	'usertable row number' => 'Nummer',
	'usertable row id' => 'Schl&uuml;ssel',
	'usertable row club_id' => 'Verein',
	'usertable row class' => 'Altersgruppe',
	'usertable row type' => 'Typ',
	'usertable row weightclass' => 'Gewichtsklasse',
	'usertable row time' => 'Zeit',
	'usertable row agegroups' => 'Jahrg&auml;nge',
	'usertable row color' => 'Farbe',
	'usertable row hall' => 'Halle',
	'usertable row street' => 'Stra&szlig;e',
	'usertable row zip' => 'PLZ',
	'usertable row city' => 'Stadt',
	'usertable row email' => 'Emailadresse',
	'emailaddress' => 'Emailadresse',
	'usertable row year' => 'G&uuml;ltigkeitsjahr',
	'administration: manage user tables' => 'Administration: benutzerdefinierte Tabellen verwalten',
	'administration: manage defaults' => 'Administration: Vorgaben verwalten',
	'administration: manage users and permissions' => 'Administration: Benutzer und Rechte verwalten',
	'administration: create new year' => 'Administration: neues Jahr erstellen',
	'administration: manage school holidays' => 'Administration: Schulferien verwalten',
	'manage' => ' verwalten',
	'toggle table selection' => 'Tabellenauswahl ein- oder ausblenden',
	'manage user tables' => 'Benutzerdefinierte Tabellen verwalten',
	'manage table' => 'Tabelle verwalten: ',
	'page' => 'Seite',
	'pages' => 'Seiten',
	'page to' => 'bis',
	'of pages' => 'von',
	'edit' => 'Bearbeiten',
	'disable' => 'Deaktivieren',
	'enable' => 'Aktivieren',
	'delete' => 'L&ouml;schen',
	'disabled' => 'Deaktiviert',
	'enabled' => 'Aktiviert',
	'add new entry in table' => 'Neuen Eintrag in diese Tabelle einf&uuml;gen',
	'new entry' => 'Neuer Eintrag',
	'you really want to completely delete this row' => 'Wollen Sie diese Zeile wirklich l&ouml;schen? Sie ist damit unwiederbringlich entfernt, Deaktivieren blendet sie f&uuml;r Benutzer aus, bestehende Verkn&uuml;pfungen bleiben aber bestehen!',
	'required to be filled' => 'Feld muss ausgef&uuml;llt werden!',
	'successful deleted row completely' => 'Die Zeile wurde endg&uuml;tig gel&ouml;scht!',
	'successful changed row' => 'Zeile erfolgreich ge&auml;ndert',
	'insert row' => 'Zeile einf&uuml;gen',
	'row enabled, disable now' => 'Diese Tabellenzeile ist aktiv und kann nicht aktiviert werden, m&ouml;chten Sie sie stattdessen deaktivieren?',
	'row disabled, enable now' => 'Diese Tabellenzeile ist deaktiviert und kann nicht deaktiviert werden, m&ouml;chten Sie sie stattdessen aktivieren?',
	'enable row' => 'Zeile aktivieren',
	'disable row' => 'Zeile deaktivieren',
	'manage user, groups and permissions' => 'Benutzer, Gruppen und Berechtigungen verwalten',
	'user management' => 'Benutzerverwaltung',
	'group management' => 'Gruppenverwaltung',
	'permission management' => 'Berechtigungsverwaltung',
	'&lArr; back' => '&lArr; Zur&uuml;ck...',
	'successful saved permissions' => 'Berechtigungen erfolgreich gespeichert!',
	'completely remove permission on this navi entry' => 'Berechtigung f&uuml;r diesen Navigationseintrag vollst&auml;ndig entfernen',
	'add group' => 'Gruppe anlegen',
	'save group' => 'Gruppe speichern',
	'group name' => 'Gruppenname',
	'required group name!' => 'Der Gruppenname ist erforderlich!',
	'parent group' => 'Obergruppe',
	'required parent group!' => 'Die Obergruppe ist erforderlich!',
	'successful added group:' => 'Neue Gruppe erfolgreich angelegt:',
	'successful saved group:' => 'Gruppe erfolgreich gespeichert:',
	'do you want to completely remove this group?' => 'Wollen Sie diese Gruppe endg&uuml;ltig l&ouml;schen?',
	'successful deleted group!' => 'Die Gruppe wurde erfolgreich gel&ouml;scht!',
	'edit group:' => 'Gruppe bearbeiten:',
	'add user' => 'Benutzer anlegen',
	'save user' => 'Benutzer speichern',
	'repeat password' => 'Passwort wiederholen',
	'required username!' => 'Der Benutzername ist erforderlich!',
	'required password!' => 'Das Passwort ist erforderlich!',
	'required name!' => 'Der Name ist erforderlich!',
	'required email address!' => 'Die Emailadresse ist erforderlich!',
	'groups' => 'Gruppen',
	'successful added user:' => 'Neuen Benutzer erfolgreich angelegt:',
	'successful saved user:' => 'Benutzer erfolgreich gespeichert:',
	'do you want to completely remove this user?' => 'Wollen Sie diesen Benutzer endg&uuml;ltig l&ouml;schen?',
	'successful removed user!' => 'Der Benutzer wurde erfolgreich gel&ouml;scht!',
	'edit user:' => 'Benutzer bearbeiten:',
	'required field selection!' => 'Feld muss ausgew&auml;hlt werden!',
	'show protocol' => 'Protokoll anzeigen',
	'show PDF' => 'PDF anzeigen',
	'correct protocol' => 'Protokoll korrigieren',
	'new protocol' => 'Neues Protokoll',
	'edit protocol' => 'Protokoll bearbeiten',
	'show decisions' => 'Beschl&uuml;sse anzeigen',
	'delete protocol' => 'Protokoll l&ouml;schen',
	'protocols' => 'Protokolle',
	'protocols: listall' => 'Protokolle: Listenansicht',
	'protocols: details' => 'Protokolle: Details',
	'protocols: new protocol' => 'Protokolle: Neues Protokoll',
	'protocols: edit protocol' => 'Protokolle: Protokoll bearbeiten',
	'protocols: delete protocol' => 'Protokolle: Protokoll l&ouml;schen',
	'protocols: show protocol' => 'Protokolle: Protokoll anzeigen',
	'protocols: protocol as PDF' => 'Protokolle: Protokoll als PDF',
	'protocols: correct protocol' => 'Protokolle: Protokoll korrigieren',
	'protocols: show decisions' => 'Protokolle: Beschl&uuml;sse anzeigen',
	'kind' => 'Art',
	'location' => 'Ort',
	'finished correction' => 'Korrektur abgeschlossen',
	'protocol as PDF' => 'Protokoll als PDF',
	'show details' => 'Details anzeigen',
	'existing corrections, please check' => 'Korrekturen vorhanden, &uuml;berpr&uuml;fen',
	'protocol' => 'Protokoll',
	'show all decisions of this protocol' => 'Alle Beschl&uuml;sse dieses Protokolls anzeigen',
	'decisions' => 'Beschl&uuml;sse',
	'show protocol as PDF' => 'Protokoll als PDF anzeigen',
	'PDF' => 'PDF',
	'preset' => 'Vorlage',
	'participants (attendant)' => 'Teilnehmer (anwesend)',
	'participants (excused)' => 'Teilnehmer (entschuldigt)',
	'participants (without excuse)' => 'Teilnehmer (unentschuldigt)',
	'kind of meeting' => 'Art der Sitzung',
	'rights' => 'Rechte',
	'content/protocol text' => 'Inhalt/Protokolltext',
	'recorder' => 'Protokollant',
	'in progress' => 'in Bearbeitung',
	'correction enabled' => 'Korrekturfreigabe',
	'published' => 'ver&ouml;ffentlicht',
	'correctors' => 'Korrektoren',
	'required location' => 'Ort muss eingetragen werden!',
	'required to select kind of meeting' => 'Art der Sitzung muss ausgew&auml;hlt werden!',
	'required to select preset' => 'Vorlage muss ausgew&auml;hlt werden!',
	'required recorder' => 'Protokollant muss eingetragen werden!',
	'item' => 'TOP',
	'decision' => 'Beschluss',
	'editor is shown after preset selection' => 'Der Editor erscheint erst nach Auswahl der Vorlage!',
	'preset cannot be changed until saved' => 'Die Vorlage kann erst nach dem Speichern wieder angepasst werden!',
	'show conclusion of decisions' => 'Zusammenfassung der Beschl&uuml;sse dieses Protokolls anzeigen',
	'show decisions of this protocol' => 'Zeige Beschl&uuml;sse dieses Protokolls',
	'<b>successful saved correction</b>' => '<p>Korrektur erfolgreich gespeichert.</p>',
	'successful updated protocol' => 'Protokoll erfolgreich aktualisiert',
	'back to correction' => 'zur&uuml;ck zur Korrektur',
	'correction of' => 'Korrektur von',
	'list of existing corrections' => 'Liste der erstellten Korrekturen',
	'reviewed correction' => 'Korrektur bearbeitet',
	'original text' => 'Originaltext',
	'correction' => 'Korrektur',
	'compare correction' => 'Korrektur vergleichen',
	'goto protocol' => 'Gehe zum Protokoll...',
	'diff_tmceItem' => 'TOP',
	'diff_tmceDecision' => 'Beschluss',
	'<span>state:</span><br />' => '<span>Status:</span><br />',
	'published' => 'Ver&ouml;ffentlicht',
	'<span>date:</span><br />' => '<span>Datum:</span><br />',
	'<span>kind:</span><br />' => '<span>Art:</span><br />',
	'<span>location:</span><br />' => '<span>Ort:</span><br />',
	'<span>participants (attendant):</span><br />' => '<span>Teilnehmer (anwesend):</span><br />',
	'<span>participants (excused):</span><br />' => '<span>Teilnehmer (entschuldigt):</span><br />',
	'<span>participants (without excuse):</span><br />' => '<span>Teilnehmer (unentschuldigt):</span><br />',
	'<span>owner:</span><br />' => '<span>Besitzer:</span><br />',
	'<span>recorder:</span><br />' => '<span>Protokollant:</span><br />',
	/*
	 * help titles and messages
	 */
	'HELP_TITLE_error' => 'Fehler',
	'HELP_TITLE_1' => 'Info',
	'HELP_TITLE_2' => 'Datumsfeld',
	'HELP_TITLE_3' => 'Namen-/Bezeichnungsfeld',
	'HELP_TITLE_4' => 'Kurznamensfeld',
	'HELP_TITLE_5' => 'Typauswahlfeld',
	'HELP_TITLE_6' => 'Inhaltsfeld',
	'HELP_TITLE_7' => 'Gruppierungsauswahlfeld',
	'HELP_TITLE_8' => 'Ver&ouml;ffentlichungsauswahlfeld',
	'HELP_TITLE_9' => 'Neuer Termin',
	'HELP_TITLE_10' => 'Terminliste',
	'HELP_TITLE_11' => 'Aufgaben in der Terminliste',
	'HELP_TITLE_12' => 'L&ouml;schen', 
	'HELP_TITLE_13' => 'Filterliste',
	'HELP_TITLE_14' => 'Textfeld',
	'HELP_TITLE_15' => 'Login',
	'HELP_TITLE_16' => 'Auswahlbox',
	'HELP_TITLE_17' => 'Auswahlfeld',
	'HELP_TITLE_18' => 'Abh&auml;ngiges Auswahlfeld',
	'HELP_TITLE_19' => 'Tabelle ausw&auml;hlen',
	'HELP_TITLE_20' => 'Aufgaben',
	'HELP_TITLE_21' => 'Dateiliste',
	'HELP_TITLE_22' => 'Aufgaben in der Dateiliste',
	'HELP_TITLE_23' => 'Datei hochladen',
	'HELP_TITLE_24' => 'Dateiauswahlfeld',
	'HELP_TITLE_25' => 'Protokollliste',
	'HELP_TITLE_26' => 'Aufgaben in der Protokollliste',
	'HELP_TITLE_27' => 'Textfeld',
	'HELP_TITLE_28' => 'Vorlagenauswahl',
	'HELP_TITLE_29' => 'Neues Protokoll',
	'HELP_TITLE_30' => 'Protokoll korrigieren',
	'HELP_TITLE_31' => 'Protokoll Status',
	'HELP_TITLE_32' => 'Protokoll Korrektoren',
	'HELP_TITLE_33' => 'Beschl&uuml;sse',
	'HELP_TITLE_34' => 'Korrekturen vergleichen',
	'HELP_TITLE_35' => 'Korrekturen auflisten',
	'HELP_TITLE_36' => 'Ergebnisformat',
	'HELP_TITLE_37' => 'Beschreibung des Ergebnisses',
	'HELP_TITLE_38' => 'Ergebnis&uuml;bersicht',
	'HELP_TITLE_39' => 'Ortsfeld',
	'HELP_TITLE_40' => 'Neues Jahr erstellen',
	'HELP_TITLE_41' => 'Ergebnisliste',
	'HELP_TITLE_42' => 'Ergebnisliste einer Veranstaltung',
	'HELP_TITLE_43' => 'Buchhaltung: Preise bearbeiten',
	'HELP_TITLE_44' => 'Aufgaben in der Ergebnisliste',
	'HELP_TITLE_45' => 'Auswahl Einzel oder Mannschaft',
	'HELP_TITLE_46' => 'Kalenderansicht',
	'HELP_TITLE_47' => 'Farbfeld',
	'HELP_TITLE_48' => 'Feld externer Termin',
	'HELP_TITLE_49' => 'Auswahl des Schulferienjahrs',
	'HELP_TITLE_50' => 'Schulferien verwalten',
	'HELP_TITLE_51' => 'Neue Ehrung planen',
	'HELP_TITLE_52' => 'Auflistung der geplanten und durchgef&uuml;hrten Ehrungen',
	'HELP_TITLE_53' => 'Ehrung bearbeiten',
	'HELP_MESSAGE_error' => '<p>Dieses Hilfe-Thema konnte nicht gefunden werden.</p>',
	'HELP_MESSAGE_1' => '<p><b>JudoIntranet</b></p>
						<p>Author: Nils Bohrs<br />
						Version: r{$replace.version}<br />Lizenz: MIT</p>
						<p>&nbsp;</p>
						<p>Copyright &copy; 2011-{date(\'Y\')} Nils Bohrs</p>
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
	'HELP_MESSAGE_2' => '<p><b>Format</b>: <i>dd.mm.yyyy</i><br />
						<b>Standardwert</b>: <i>das heutige Datum</i></p>
						<p>Dieses Feld legt ein Datum f&uuml;r diesen Datensatz fest, das Feld
						hat eine Datumsauswahl zur Unterst&uuml;tzung, dieses sollte zur Sicherstellung
						des korrekten Formats verwendet werden.</p>',
	'HELP_MESSAGE_3' => '<p><b>Format</b>: <i>einzeiliger Text</i><br />
						<b>Standardwert</b>: <i>keiner</i><br />
						<b>Erlaubte Zeichen</b>: <i>"{$object->getGc()->get_config(\'name.desc\')|escape}"</i></p>
						<p>Dieses Feld legt den Namen oder die Bezeichnung f&uuml;r diesen Datensatz
						fest, der Wert erscheint zumeist in Listen oder &Uuml;berschriften.</p>',
	'HELP_MESSAGE_4' => '<p><b>Format</b>: <i>einzeiliger Text</i><br />
						<b>Standardwert</b>: <i>keiner</i><br />
						<b>Erlaubte Zeichen</b>: <i>"{$object->getGc()->get_config(\'name.desc\')|escape}"</i></p>
						<p>Dieses Feld legt einen Kurznamen f&uuml;r diesen Datensatz fest, der Wert wird in
						den Dateinamen der Ausschreibungen zur Abk&uuml;rzung verwendet. Wenn das Feld nicht
						ausgef&uuml;llt wird, wird der Wert beim Speichern auf die ersten drei Buchstaben
						des Namens-/Bezeichnungsfeldes gesetzt, der Wert wird immer in Gro&szlig;buchstaben
						umgewandelt.</p>',
	'HELP_MESSAGE_5' => '<p><b>Format</b>: <i>einzeiliges Auswahlfeld</i></p>
						<p>Dieses Feld legt den Typ f&uuml;r diesen Datensatz fest, z.B. bei Veranstaltunge
						oder Terminen die Art der Veranstaltung (Turnier, Lehrgang, etc.).</p>',
	'HELP_MESSAGE_6' => '<p><b>Format</b>: <i>mehrzeiliger Text</i><br />
						<b>Standardwert</b>: <i>keiner</i><br />
						<b>Erlaubte Zeichen</b>: <i>"{$object->getGc()->get_config(\'textarea.desc\')|escape}"</i></p>
						<p>Dieses Feld legt den Inhalt f&uuml;r diesen Datensatz fest, hier kann
						z.B. die genaue Beschreibung eines Termin eingetragen werden.</p>',
	'HELP_MESSAGE_7' => '<p><b>Format</b>: <i>Mehrfachauswahlfeld</i></p>
						<p>Dieses Feld legt die Gruppen fest, nach denen der Datensatz mittels
						Sortierung in den Listen angezeigt wird. Die Auswahl mehrerer Gruppen ist
						durch Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich, das
						Entfernen der Auswahl oder einzelner Gruppen ist ebenfalls mittels
						Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich.</p>',
	'HELP_MESSAGE_8' => '<p><b>Format</b>: <i>Auswahlbox</i></p>
						<p>Das Anhaken der Auswahlbox markiert diesen Datensatz als &ouml;ffentlich,
						wenn der Haken gesetzt ist, wird der Datensatz in den &ouml;ffentlichen Listen,
						also ohne Anmeldung sichtbar.</p>',
	'HELP_MESSAGE_9' => '<p>Formular zur Erstellung eines neuen Termins. Alle Felder, die
						mit einem roten <span class="required">*</span> gekennzeichnet sind, m&uuml;ssen ausgef&uuml;llt werden, das
						Formular l&auml;sst sich sonst nicht speichern.<br />Die erlaubten Zeichen
						werden in der Hilfe des jeweiligen Feldes erl&auml;tert, bei Fehleingaben
						wird eine entsprechende Meldung ausgegeben.</p>',
	'HELP_MESSAGE_10' => '<p>Diese Seite listet alle Termine auf, die noch nicht abgelaufen
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
						<li><img src="img/attachment_info.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; zeigt an, dass
						Dateien am Termin angeh&auml;ngt sind, ein Klick darauf &ouml;ffnet die Detailansicht des Termins.</li>
						<li><img src="img/result_info.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; zeigt an, dass
						Ergebnisse zu diesem Termin importiert wurden, ein Klick darauf &ouml;ffnet die Ergebnisliste zu diesem Termins.</li>
						</ul>',
	'HELP_MESSAGE_11' => '<p>Die Administration eines Termins oder einer
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
						<li><img src="img/res_new.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &Ouml;ffnet den Dialog um
						ein Ergebnis zu diesem Termin zu importieren, die Funktion ist ab dem Veranstaltungstag erreichbar.</li>
						<li><img src="img/refresh_pdf.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; Erzeugt die PDF-Ansicht der
						Ausschreibung manuell neu, z.B. nach Änderungen an den Vorgaben oder Benutzerdefinierten Tabellen.</li>
						<li><img src="img/show_presetform.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; Um eine Ausschreibung mit Daten
						zu f&uuml;llen muss ihr eine Vorlage zugewiesen werden, die die zu verwendenden Felder und das Aussehen festlegt.
						Ein Klick auf den Button &ouml;ffnet das Formularfeld zur Auswahl.
						<span class="Zebra_Form"><select class="control"><option>Ausschreibungsvorlage ausw&auml;hlen</option></select>
						<input class="submit" type="submit" value="+" /></span><br />Das Zuweisen der Vorlage erfolgt durch das
						Ausw&auml;hlen der Vorlage aus dem einzeiligen Auswahlfeld und anschlie&szlig;endem Zuf&uuml;gen durch den Button "+".</li>
						</ul>
						<p>Das Symbol <img src="img/public.png" alt="Bild im Hilfetext" /> in der entsprechenden Spalte zeigt den
						eingeloggten Benutzern an, ob der Termin &ouml;ffentlich ist, also ob ein nicht angemeldeter Benutzer den
						Termin (inkl. evtl. Ausschreibung) sehen kann.</p>',
	'HELP_MESSAGE_12' => '<p>Der Klick auf "Ja" l&ouml;scht den Datensatz endg&uuml;ltig,
						"Abbrechen" f&uuml;hrt zur&uuml;ck zur Liste.</p>',
	'HELP_MESSAGE_13' => '<p>Die eingeblendete Filterauswahl besteht aus drei
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
	'HELP_MESSAGE_14' => '<p><b>Format</b>: <i>mehrzeiliger Text</i><br />
						<b>Standardwert</b>: <i>keiner</i><br />
						<b>Erlaubte Zeichen</b>: <i>"{$object->getGc()->get_config(\'textarea.desc\')|escape}"</i><br />
						<b>Auswahl</b>: <i>Systemvorgaben oder Auswahl aus den letzten Eingaben</i></p>
						<p>Das Textfeld nimmt alle Zeichen laut <b>Erlaubte Zeichen</b> an, &uuml;ber
						das einzeilige Auswahlfeld k&ouml;nnen vorgefertigte Texte des Systems oder
						aus der Historie der zuletzt eingegebenen Texte ausgew&auml;hlt werden.</p>',
	'HELP_MESSAGE_15' => '
						{if array_key_exists(\'user not active\', $replace)}
							<p><b>Benutzer nicht aktiv</b></p>
							<p>Dieser Benutzer ist deaktiviert worden oder noch nicht aktiviert, falls
							diese Meldung zu unrecht erscheint, bitte beim Systembetreuer
							nachfragen.</p>
						{elseif array_key_exists(\'wrong password\', $replace)}
							<p><b>Falsches Passwort</b></p>
							<p>Es wurde versucht sich mit einem falschen Passwort an zu melden, falls
							diese Meldung zu unrecht erscheint, bitte die Schreibweise des Passworts
							pr&uuml;fen, oder beim Systembetreuer
							nachfragen.</p>
						{elseif array_key_exists(\'username not exists\', $replace)}
							<p><b>Benutzer existiert nicht</b></p>
							<p>Es wurde versucht sich mit einem nicht existierenden Benutzer an zu melden, falls
							diese Meldung zu unrecht erscheint, bitte die Schreibweise des Benutzernamens
							pr&uuml;fen, oder beim Systembetreuer
							nachfragen.</p>
						{else}
							<p><b>Login</b></p>
							<p>Um die nicht &ouml;ffentlichen Funktionen zu nutzen, muss in einem der
							beteiligten Gremien mitgearbeitet werden. Der Zugang wird durch die
							entsprechenden Vorst&auml;nde genehmigt und die Zugangsdaten (Benutzername
							und Passwort) werden durch den Systembetreuer
							zur Verf&uuml;gung gestellt.</p>
						{/if}',
	'HELP_MESSAGE_16' => '<p><b>Format</b>: <i>Auswahlbox</i></p>
						<p>Das Anhaken der Auswahlbox aktiviert die Einstellung oder den Wert, das
						Entfernen des Hakens deaktiviert sie.</p>',
	'HELP_MESSAGE_17' => '<p><b>Format</b>: <i>Auswahlfeld/Mehrfachauswahlfeld</i></p>
						<p>Dieses Feld erlaubt die Auswahl eines (bei einfachem Auswahlfeld) oder
						mehrfache Auswahl der vorgegebenen Werte. Die Auswahl mehrerer Werte ist
						durch Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich, das
						Entfernen der Auswahl oder einzelner Werte ist ebenfalls mittels
						Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich.</p>',
	'HELP_MESSAGE_18' => '<p><b>Format</b>: <i>abh&auml;ngigesAuswahlfeld</i></p>
						<p>Dieses Feld bietet von einander abh&auml;ngige Optionen zur Auswahl an. Die
						get&auml;tige Auswahl des ersten Felds beeinflusst die zur Auswahl stehenden
						Optionen des zweiten Auswahlfeldes.</p>',
	'HELP_MESSAGE_19' => '<p>Der nebenstehende Link blendet die Auswahl der
						administrierbaren Tabellen ein (z.B.: Vereine und deren Ansprechpartner).
						Ein Klick auf die gew&uuml;nschte Tabelle zeigt deren Inhalt an und bietet
						die Funktionen diese zu ver&auml;ndern, zu deaktivieren oder zu l&ouml;schen
						und neue Datens&auml;tze an zu legen.<br />
						Die Art und Zahl der Spalten unterscheidet sich je nach Tabelle, da
						unterschiedliche Daten gespeichert werden.</p>',
	'HELP_MESSAGE_20' => '<p></p>
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
	'HELP_MESSAGE_21' => '<p>Diese Seite listet alle Dateien auf getrennt nach hochgeladenen und zwischengespeicherten.
						Ein Klick auf den unterstrichenen Namen einer Datei &ouml;ffnet deren Details.</p>
						<ul>
						<li><img src="img/file_download.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; l&auml;dt die Datei herunter, der Browser
						&ouml;ffnet den Auswahldialog, der zur Entscheidung auffordert, was mit der Datei geschehen soll.</li>
						</ul>',
	'HELP_MESSAGE_22' => '<p>Die Administration einer Datei erfolgt &uuml;ber folgende Buttons:</p>
						<ul>
						<li><img src="img/file_edit.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet die Datei im
						Bearbeitungsmodus, hier k&ouml;nnen die einzelnen Felder des Datensatzes ge&auml;ndert werden.</li>
						<li><img src="img/file_delete.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; l&ouml;scht die Datei nach
						R&uuml;ckfrage endg&uuml;ltig.</li>
						</ul>',
	'HELP_MESSAGE_23' => '<p>Formular zum Hochladen einer neuen Datei. Alle Felder, die
						mit einem roten <span class="required">*</span> gekennzeichnet sind, m&uuml;ssen ausgef&uuml;llt werden, das
						Formular l&auml;sst sich sonst nicht speichern.<br />Die erlaubten Zeichen
						werden in der Hilfe des jeweiligen Feldes erl&auml;tert, bei Fehleingaben
						wird eine entsprechende Meldung ausgegeben.</p>',
	'HELP_MESSAGE_24' => '<p><b>Format</b>: <i>Datei-Upload</i></p>
						<p>Der Button "Durchsuchen..." &ouml;ffnet den Dialog zur Auswahl einer Datei vom lokalen Rechner. Durch
						einen Fehler in einer verwendeten Komponente wird die Datei nach dem Ausw&auml;hlen nicht mehr angezeigt,
						um eine falsch ausgew&auml;hlte Datei zu korrigieren, ist das Neuladen der Seite erforderlich.</p>',
	'HELP_MESSAGE_25' => '<p>Diese Seite listet alle Protokolle. Ein Klick auf den unterstrichenen
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
	'HELP_MESSAGE_26' => '<p>Die Administration eines Protokolls erfolgt &uuml;ber folgende Buttons
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
	'HELP_MESSAGE_27' => '<p><b>Format</b>: <i>ein-/mehrzeiliger Text</i><br />
						<b>Standardwert</b>: <i>keiner</i><br />
						<b>Erlaubte Zeichen</b>: <i>"{$object->getGc()->get_config(\'textarea.desc\')|escape}"</i></p>
						<p>In dieses Feld kann ein beliebiger Text eingetragen werden, entsprechend den o.a. erlaubten Zeichen.</p>',
	'HELP_MESSAGE_28' => '<p><b>Format</b>: <i>einzeiliges Auswahlfeld</i></p>
						Hier ist die Vorlage aus zu w&auml;hlen, in der der Datensatz als PDF angezeitg wird.<p>',
	'HELP_MESSAGE_29' => '<p>Formular zur Erstellung eines neuen Protokolls. Alle Felder, die
						mit einem roten <span class="required">*</span> gekennzeichnet sind, m&uuml;ssen ausgef&uuml;llt werden, das
						Formular l&auml;sst sich sonst nicht speichern.<br />Die erlaubten Zeichen
						werden in der Hilfe des jeweiligen Feldes erl&auml;tert, bei Fehleingaben
						wird eine entsprechende Meldung ausgegeben.</p>',
	'HELP_MESSAGE_30' => '<p>Der obere Teil der Ansicht zeigt auf der rechten Seite den Text des urspr&uuml;nglichen Protokolls
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
	'HELP_MESSAGE_31' => '<p>Der Status eines Protokolls besteht aus drei Auswahlm&ouml;glichkeiten:</p>
						<ul>
						<li><b>in Bearbeitung</b>: in diesem Status kann nur der Ersteller des Protokolls es bearbeiten, f&uuml;r
						alle anderen ist es nicht sichtbar.</li>
						<li><b>Korrekturfreigabe</b>: in diesem Status kann das Protokoll von den "Korrektoren" korrigiert werden,
						es ist dann nur f&uuml;r den Ersteller und die Korrektoren sichtbar mit jeweils unterschiedlichen Aufgaben.</li>
						<li><b>ver&ouml;ffentlicht</b>: in diesem Status ist das Protokoll fertig und sichtbar f&uuml;r alle die
						berechtigt sind es zu sehen (angemeldete Benutzer, oder alle).</li>
						</ul>',
	'HELP_MESSAGE_32' => '<p><b>Format</b>: <i>Mehrfachauswahlfeld</i></p>
						<p>Das Feld listet alle Benutzer auf, die sich anmelden k&ouml;nnen, hier kann ein oder mehrere Benutzer ausgew&auml;hlt
						werden, die das Protokoll korrigieren d&uuml;rfen. Die Auswahl mehrerer Werte ist
						durch Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich, das
						Entfernen der Auswahl oder einzelner Werte ist ebenfalls mittels
						Dr&uuml;cken und Halten der &lt;STRG&gt;-Taste m&ouml;glich.</p>',
	'HELP_MESSAGE_33' => '<p>Beschl&uuml;sse werden hier in Tabellen angezeigt:</p>
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
	'HELP_MESSAGE_34' => '<p>Zur Korrektur wird der Original-Protokolltext im Editor angezeigt und die Korrektoren
						d&uuml;rfen ihre Korrekturen direkt im Text vornehmen, so dass das Protokoll dem Stand entspricht, den sie
						f&uuml;r den korrekten halten.</p>',
	'HELP_MESSAGE_35' => '<p>Hier werden alle Korrekturen zu diesem Protokoll aufgelistet, es werden der Name des Korrektors
						und das Datum der Erstellung angezeigt. Der Klick auf eine Korrektur, &ouml;ffnet die Vergleichsansicht.</p>
						<p>Wenn hinter der Korrektur das Symbol <img src="img/done.png" alt="Bild im Hilfetext" /> angezeigt wird,
						wurde diese Korrektur als "schon bearbeitet" markiert.</p>',
	'HELP_MESSAGE_36' => '<p>Das Ergebnisformat muss ausgew&auml;hlt werden um den korrekten Import des Ergebnisses zu gew&auml;hrleisten.</p>',
	'HELP_MESSAGE_37' => '<p>Da mehrere Ergebnisse zu einer Veranstaltung importiert werden k&ouml;nnen, muss eine Beschreibung
						eine Beschreibung zur Unterscheidung eingegeben werden. Nicht nur bei mehreren Altersklassen empfiehlt sich diese
						in der Beschreibung kenntlich zu machen.</p>',
	'HELP_MESSAGE_38' => '<p>In der Ergebnis&uuml;bersicht werden alle Ergebnisse angezeigt, die den Veranstaltungen zugeordnet sind.
						Der Klick auf <img src="img/res_pdf.png" alt="Bild im Hilfetext" /> erzeugt eine Abrechnung, in der die
						Ausrichterpauschale und alle Meldegelder, aufgeschl&uuml;sselt pro Alterklasse und Verein, angegeben sind.</p>
						<p><img src="img/tasks_confirmed.png" alt="Bild im Hilfetext" />/<img src="img/tasks_unconfirmed.png" alt="Bild im Hilfetext" />
						zeigen den globalen Status der Bearbeitung des Ergebnisses an, der Status dient nur der Information und hat
						keine weiteren Funktionen. Der Klick auf den Button &auml;ndert den Status jeweils in den anderen.</p>',
	'HELP_MESSAGE_39' => '<p><b>Format</b>: <i>einzeiliger Text</i><br />
						<b>Standardwert</b>: <i>keiner</i><br />
						<b>Erlaubte Zeichen</b>: <i>"{$object->getGc()->get_config(\'name.desc\')|escape}"</i></p>
						<p>Dieses Feld legt den Ort f&uuml;r diesen Datensatz fest, der Wert wird &uuml;berschrieben
						sobald der Ort in der Ausschreibung festgelegt wird, beim L&ouml;schen der Ausschreibung verbleibt
						der letzte Wert aus der Ausschreibung.</p>',
	'HELP_MESSAGE_40' => '<p>Die Funktion "neues Jahr erstellen" kopiert sämtliche Einträge des aktuellen Jahrs und stellt sie deaktiviert
						zur weiteren Bearbeitung zur Verf&uuml;gung. Die Funktion wird ohne R&uuml;ckfrage ausgef&uuml;hrt, kann beliebig oft
						wiederholt werden und ist nicht r&uuml;ckg&auml;ngig zu machen.<br />Werte die sich im Vergleich zum laufenden Jahr
						&auml;ndern (mind. Jahrg&auml;nge) m&uuml;ssen manuell angepasst werden und jeder Eintrag, der verwendet werden soll,
						muss aktiviert werden.</p>',
	'HELP_MESSAGE_41' => '<p>Diese Seite listet alle Ergebnisse auf. Ein Klick auf den unterstrichenen
						Namen der Veranstaltung &ouml;ffnet ihre Details. Da es mehrere Ergebnisse zu einer Veranstaltung geben
						kann, enth&auml;lt die erste Spalte eine Beschreibung, in der &uuml;blicherweise auch die Altersklasse
						angegeben ist. Es stehen folgende weitere Ansichten zur Verf&uuml;gung:</p>
						<ul>
						<li><img src="img/res_details.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet
						das Ergebnis als Seitenansicht eingebettet in diese Seite, Drucken ist
						in dieser Ansicht nicht m&ouml;glich.</li>
						<li><img src="img/res_pdf.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet das
						Ergebnis direkt als PDF (ein entsprechendes Programm zur Anzeige
						wie der AdobeReader vorausgesetzt), in dieser Ansicht kann die Ausschreibung
						gedruckt oder gespeichert werden.</li>
						</ul>',
	'HELP_MESSAGE_42' => '<p>Diese Seite listet alle Ergebnisse der zugeh&ouml;rigen Veranstaltung auf. Ein Klick auf den unterstrichenen
						Namen der Veranstaltung &ouml;ffnet ihre Details. Da es mehrere Ergebnisse zu einer Veranstaltung geben
						kann, enth&auml;lt die erste Spalte eine Beschreibung, in der &uuml;blicherweise auch die Altersklasse
						angegeben ist. Es stehen folgende weitere Ansichten zur Verf&uuml;gung:</p>
						<ul>
						<li><img src="img/res_details.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet
						das Ergebnis als Seitenansicht eingebettet in diese Seite, Drucken ist
						in dieser Ansicht nicht m&ouml;glich.</li>
						<li><img src="img/res_pdf.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet das
						Ergebnis direkt als PDF (ein entsprechendes Programm zur Anzeige
						wie der AdobeReader vorausgesetzt), in dieser Ansicht kann die Ausschreibung
						gedruckt oder gespeichert werden.</li>
						</ul>',
	'HELP_MESSAGE_43' => '<p>Der Wert der Preise kann an dieser Stelle angepasst werden, die Preise werden u.a. bei der Erstellung
						der Rechnungen und Abrechnungen verwendet.</p>',
	'HELP_MESSAGE_44' => '<p>Die Administration eines Ergebnisses in der Ergebnisliste besteht nur aus einer Aktion:</p>
						<ul>
						<li><img src="img/res_delete.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; l&ouml;scht das Ergebnis nach
						R&uuml;ckfrage endg&uuml;ltig.</li>
						</ul>
						<p>Das Bearbeiten eines Ergebnisses im Nachhinein ist nicht m&ouml;glich.</p>',
	'HELP_MESSAGE_45' => '<p><b>Format</b>: <i>einzeiliges Auswahlfeld</i></p>
						<p>F&uuml;r einige Ergebnisformate ist nicht festgelegt, ob sie von einer Einzel- oder Mannschaftsveranstaltung
						stammen. Um in den Abrechnungsfunktionen die korrekten Werte zugrunde zu legen ist die Auswahl erforderlich und
						ein Pflichtfeld.</p>',
	'HELP_MESSAGE_46' => '<p>Diese Seite stellt alle Termine in einem Kalender dar. Die Navigation durch die Monate oder Wochen erfolgt
						in der Kopfzeile des Kalenders. Der Klick auf einen Termineintrag &ouml;ffnet dessen Details wie in der
						Listenansicht.</p>',
	'HELP_MESSAGE_47' => '<p><b>Format</b>: <i>Farbauswahl</i></p>
						<p>Durch Anklicken des farbigen Feldes &ouml;ffnet sich eine vorgegebene Auswahl an Farben, die dem Termin
						zugeordnet werden k&ouml;nnen. Der Klick auf eines der vorgegebenen Farbfelder &auml;ndert die Farbe entsprechend.</p>',
	'HELP_MESSAGE_48' => '<p><b>Format</b>: <i>Auswahlbox</i></p>
						<p>Das Anhaken der Auswahlbox markiert diesen Datensatz als externen Termin,
						ein externer Termin dient nur der Information und es kann keine Ausschreibung daf&uuml;r erstellt werden.</p>',
	'HELP_MESSAGE_49' => '<p>Diese Seite bietet das aktuelle und die beiden folgenden Jahre zur Schulferienverwaltung an. Ein Klick auf
						einen Eintrag &ouml;ffnet das entsprechende Jahr zur Bearbeitung.</p>',
	'HELP_MESSAGE_50' => '<p>Bei der Bearbeitung der Schulferientermine unterscheiden sich die Eintr&auml;ge durch festgelegte oder variable
						Namen. Die vorgegebenen haben einen unver&auml;nderbaren Namen, bei den Variablen kann er angepasst werden.<br />
						F&uuml;r jeden vorgegebenen Ferientermin ist das "von"-Datum ein Pflichtfeld, bei den variablen ist der Name ein
						Pflichtfeld. Wenn der Name eines variablen Ferienentrages nicht ge&auml;ndert wird (Vorgabe: "benutzerdefiniert_X", wobei
						"X" eine fortlaufende Zahl ist), wird der komplette Eintrag ignoriert. Wenn variable Ferientermine eingegeben werden,
						werden bei der nächsten Bearbeitung wieder f&uuml;nf Felder zur Bearbeitung angeboten.</p>',
	'HELP_MESSAGE_51' => '<p>Formular zur Erstellung einer neuen Ehrungsplanung. Alle Felder, die mit einem roten <span class="required">*</span>
						gekennzeichnet sind, m&uuml;ssen ausgef&uuml;llt werden, das Formular l&auml;sst sich sonst nicht speichern.<br />Die erlaubten
						Zeichen werden in der Hilfe des jeweiligen Feldes erl&auml;tert, bei Fehleingaben wird eine entsprechende Meldung ausgegeben.</p>',
	'HELP_MESSAGE_52' => '<p>Diese Seite listet alle geplanten und durchgef&uuml;hrten Ehrungen auf. Die Liste l&auml;sst sich &uuml;ber
						die oberhalb der Liste befindlichen Auswahlfelder und dem Suchfeld einschr&auml;nken.
						<ul>
						<li>Das Auswahlfeld <span class="Zebra_Form"><select class="control"><option>- Jahr ausw&auml;hlen -</option></select></span>
						bietet alle Jahre zur Auswahl an, f&uuml;r die es Eintr&auml;ge in der Datenbank gibt.<br />Das Jahr wird aus den Daten
						"Beginn der Planung", "Ehrung geplant" und "Datum der Ehrung" berechnet. Es wird hierbei immer das kleinste Datum verwendet,
						das feststeht, wenn also die Erhung noch nicht erfolgt ist, wird das Jahr der geplanten &Uuml;bergabe verwendet, wenn auch dieses
						noch nicht feststeht, das Jahr in dem die Planung begonnen wurde.</li>
						<li>Das Auswahlfeld <span class="Zebra_Form"><select class="control"><option>- Ehrengabe ausw&auml;hlen -</option></select></span>
						bietet alle in den angelegten Ehrungen verwendete Ehrengaben zur Auswahl an.</li>
						<li>Das Textfeld <span class="Zebra_Form"><input class="ui-autocomplete-input" type="text" /></span>
						kann zur direkten Suche nach den Namen der zu Ehrenden verwendet werden, ein Klick auf einen Ergebniseintrag &ouml;ffnet diesen in
						der Bearbeitungsansicht.</li>
						</ul>
						</p>
						<p>Die folgenden administrativen Funktionen stehen unter Aufgaben zur Verf&uuml;gung:</p>
						<ul>
						<li><img src="img/tribute_edit.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; &ouml;ffnet
						die Ehrung zur Bearbeitung.</li>
						<li><img src="img/tribute_delete.png" alt="Bild im Hilfetext" />&nbsp;:&nbsp; l&ouml;scht die Ehrung, &Auml;nderungen
						sind dann nicht mehr m&ouml;glich.</li>
						</ul>',
	'HELP_MESSAGE_53' => '<p>Seite zur Bearbeitung einer Ehrungsplanung, das Formular entspricht dem einer neuen Planung, es sind die bereits gespeicherten
						Daten vorausgef&uuml;llt.<br />Im unteren Bereich findet sich die Historie der Bearbeitungen, es k&ouml;nnen hier die bestehenden
						Verlaufseintr&auml;ge eingesehen werden (ein Klick auf einen Eintrag &ouml;ffnet die weiteren Daten) oder ein neuer Eintrag angelegt
						werden. Ein Verlaufseintrag ben&ouml;tigt einen Betreff, einen Typ und einen Inhaltstext, falls Eintr&auml;ge nachtr&auml;glich erstellt werden,
						kann das Datumsfeld &uuml;ber den Button <img src="css/zebra_form/calendar.png" alt="Bild im Hilfetext" /> eingeblendet werden.
						Nachtr&auml;glich erstellte Verlaufseintr&auml;ge sind an der Uhrzeit "00:00" erkennbar, ihre Sortierung nach Datum wird erst mit
						dem Neuladen der Seite korrigiert.</p>',
	/*
	 * ************************
	 */
	/*
	 * navigation
	 */
	'navi: mainPage' => 'Startseite',
	'navi: mainPage.login' => 'Login',
	'navi: mainPage.logout' => 'Logout',
	'navi: calendarPage' => 'Kalender',
	'navi: calendarPage.new' => 'Neuer Eintrag',
	'navi: calendarPage.listall' => 'Listenansicht',
	'navi: calendarPage.details' => 'Detailansicht',
	'navi: calendarPage.edit' => 'Eintrag editieren',
	'navi: calendarPage.delete' => 'Eintrag l&ouml;schen',
	'navi: calendarPage.calendar' => 'Kalenderansicht',
	'navi: calendarPage.schedule' => 'Terminplan',
	'navi: inventoryPage' => 'Inventar',
	'navi: inventoryPage.my' => 'Eigene verwalten',
	'navi: inventoryPage.listall' => 'Listenansicht',
	'navi: inventoryPage.give' => 'Objekt abgeben',
	'navi: inventoryPage.take' => 'Objekt annehmen',
	'navi: inventoryPage.cancel' => 'Objekt zur&uuml;ckziehen',
	'navi: inventoryPage.details' => 'Details',
	'navi: inventoryPage.movement' => '&Uuml;bergaben',
	'navi: announcementPage' => 'Ausschreibungen',
	'navi: announcementPage.listall' => 'Listenansicht',
	'navi: announcementPage.new' => 'Neuen Eintrag erstellen',
	'navi: announcementPage.edit' => 'Eintrag bearbeiten',
	'navi: announcementPage.delete' => 'Eintrag l&ouml;schen',
	'navi: announcementPage.details' => 'Details',
	'navi: announcementPage.topdf' => 'Eintrag als PDF',
	'navi: announcementPage.refreshpdf' => 'PDF erneuern',
	'navi: protocolPage' => 'Protokolle',
	'navi: protocolPage.listall' => 'Listenansicht',
	'navi: protocolPage.new' => 'Neues Protokoll erstellen',
	'navi: protocolPage.details' => 'Details',
	'navi: protocolPage.edit' => 'Protokoll bearbeiten',
	'navi: protocolPage.show' => 'Protokoll anzeigen',
	'navi: protocolPage.topdf' => 'Protokoll als PDF',
	'navi: protocolPage.delete' => 'Protokoll l&ouml;schen',
	'navi: protocolPage.correct' => 'Protokoll korrigieren',
	'navi: protocolPage.showdecisions' => 'Alle Beschl&uuml;sse anzeigen',
	'navi: administrationPage' => 'Administration',
	'navi: administrationPage.field' => 'Tabellen verwalten',
	'navi: administrationPage.defaults' => 'Vorgaben verwalten',
	'navi: administrationPage.useradmin' => 'Benutzer/Gruppen/Rechte',
	'navi: administrationPage.club' => 'Vereine verwalten',
	'navi: administrationPage.newYear' => 'Neues Jahr erstellen',
	'navi: administrationPage.schoolholidays' => 'Schulferien verwalten',
	'navi: filePage' => 'Dateien',
	'navi: filePage.listall' => 'Dateien auflisten',
	'navi: filePage.details' => 'Datei-Details',
	'navi: filePage.edit' => 'Datei bearbeiten',
	'navi: filePage.delete' => 'Datei l&ouml;schen',
	'navi: filePage.upload' => 'Datei hochladen',
	'navi: filePage.cached' => 'Datei herunterladen (zwischengespeichert)',
	'navi: filePage.attach' => 'Datei anh&auml;ngen',
	'navi: filePage.download' => 'Datei herunterladen (hochgeladen)',
	'navi: resultPage' => 'Ergebnisse',
	'navi: resultPage.listall' => 'Ergebnisse pro Termin auflisten',
	'navi: resultPage.details' => 'Ergebnis-Details',
	'navi: resultPage.delete' => 'Ergebnisse l&ouml;schen',
	'navi: resultPage.list' => 'Ergebnisse auflisten',
	'navi: resultPage.new' => 'Neues Ergebnis',
	'navi: resultPage.accounting' => 'Ergebnis Buchhaltung',
	'navi: accountingPage' => 'Buchhaltung',
	'navi: accountingPage.dashboard' => '&Uuml;bersicht',
	'navi: accountingPage.task' => 'Aufgabe',
	'navi: accountingPage.settings' => 'Einstellungen',
	'navi: tributePage' => 'Ehrungen',
	'navi: tributePage.listall' => 'Ehrungen auflisten',
	'navi: tributePage.new' => 'Neue Ehrung planen',
	/*
	 * ************************
	 */
	'permissions' => 'Berechtigungen',
	'data' => 'Daten',
	'files' => 'Dateien',
	'files: listall' => 'Dateien: Listenansicht',
	'files: details' => 'Dateien: Details',
	'files: upload file' => 'Dateien: Datei hochladen',
	'files: edit file' => 'Dateien: Datei bearbeiten',
	'files: delete file' => 'Dateien: Datei l&ouml;schen',
	'files: download file' => 'Dateien: Datei herunterladen',
	'files: attach files' => 'Dateien: Datei anh&auml;ngen',
	'upload file' => 'Datei hochladen',
	'edit file' => 'Datei bearbeiten',
	'delete file' => 'Datei l&ouml;schen',
	'attach file' => 'Datei anh&auml;ngen',
	'filetype' => 'Dateityp',
	'filename' => 'Dateiname',
	' download' => ' herunterladen',
	'uploaded' => 'Hochgeladen',
	'cached' => 'Zwischengespeichert',
	'table name calendar' => 'Ausschreibungen',
	'table name protocol' => 'Protokolle',
	'table name result' => 'Ergebnisse',
	'back' => 'Zur&uuml;ck',
	'download' => 'Herunterladen',
	'you really want to delete this file?' => 'Wollen Sie diese Datei wirklich l&ouml;schen?',
	'successful deleted file.' => 'Die Datei wurde erfolgreich gel&ouml;scht.',
	'attach file to:' => 'Datei anh&auml;ngen an:',
	'attached files:' => 'Angeh&auml;ngte Dateien:',
	'select file' => 'Datei ausw&auml;hlen',
	'upload' => 'Hochladen',
	'only the following file extensions are allowed!' => 'Es k&ouml;nnen nur Dateien mit folgenden Erweiterungen hochgeladen werden!',
	'could not upload file!' => 'Die Datei konnte nicht hochgeladen werden!',
	'text_plain' => 'Textdokument',
	'application_pdf' => 'PDF-Dokument',
	'Results' => 'Ergebnisse',
	'Results: listall' => 'Ergebnisse: Listenansicht',
	'Results: details' => 'Ergebnisse: Details',
	'Results: delete' => 'Ergebnisse: L&ouml;schen',
	'Results: import' => 'Ergebnisse: Importieren',
	'Results: list' => 'Ergebnisse: Liste f&uuml;r Veranstaltung',
	'Results: accounting' => 'Ergebnisse: Abrechnung',
	'listall' => 'Listenansicht',
	'event name' => 'Veranstaltung',
	'event date' => 'Datum',
	'event city' => 'Veranstaltungsort',
	'show' => 'Ansehen',
	'admin' => 'Aufgaben',
	'result details' => 'Ergebnis ansehen',
	'result pdf' => 'Ergebnis als PDF',
	'result delete' => 'Ergebnis l&ouml;schen',
	'error' => 'FEHLER',
	'delete' => 'l&ouml;schen',
	'cancel' => 'Abbrechen',
	'delete confirm' => 'Wollen Sie diesen Eintrag wirklich l&ouml;schen?',
	'delete done' => 'Der Eintrag wurde erfolgreich gel&ouml;scht.',
	'result new' => 'Ergebnis anf&uuml;gen',
	'result attached' => 'Ergebnis vorhanden',
	'result import' => 'Ergebnis importieren (Datei ausw&auml;hlen)',
	'result import check club' => 'Ergebnis importieren (Vereine pr&uuml;fen)',
	'result list' => 'Ergebnisse auflisten',
	'result accounting' => 'Ergebnisse abrechnen',
	'preset' => 'Vorlage',
	'help' => 'Hilfe',
	'import format' => 'Ergebnisformat',
	'ResultImporterMm5export' => 'MM5 Textexport',
	'ResultImporterSpreadsheet' => 'Tabellenvorlage',
	'resultfile' => 'Ergebnisdatei ausw&auml;hlen',
	'next' => 'Weiter',
	'error date required' => 'Datum muss ausgew&auml;hlt werden!',
	'error date check' => 'Korrektes Datum muss ausgew&auml;hlt werden!',
	'error text required' => 'Das Feld muss ausgef&uuml;llt werden!',
	'error select' => 'Feld muss ausgew&auml;ht werden!',
	'error allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden!',
	'error upload' => 'Die Datei konnte nicht hochgeladen werden!',
	'error required' => 'Das Feld ist erforderlich!',
	'save' => 'Speichern',
	'result import name' => 'Name',
	'result import club orig' => 'Verein (importiert)',
	'result import club correct' => 'Verein (Korrektur)',
	'result import agegroup' => 'Altersklasse',
	'result import weightclass' => 'Gewicht',
	'result import place' => 'Platz',
	'toggle imported results' => 'Importiertes Ergebnis ein-/ausblenden',
	'imported successful' => 'erfolgreich importiert.',
	'calendar details' => 'Termindetails',
	'result description' => 'Beschreibung (z.B.: Altersklassen)',
	'result desc' => 'Beschreibung',
	'Accounting' => 'Buchhaltung',
	'Accounting: dashboard' => 'Buchhaltung: &Uuml;bersicht',
	'Accounting: task' => 'Aufgaben',
	'Accounting: settings' => 'Einstellungen',
	'dashboard' => '&Uuml;bersicht',
	'unconfirmed' => 'unbest&auml;tigt',
	'confirmed' => 'best&auml;tigt',
	'click to confirm' => 'Klicken um zu best&auml;tigen',
	'click to unconfirm' => 'Klicken um Best&auml;tigung aufzuheben',
	'modified by' => 'ge&auml;ndert von',
	'name' => 'Name',
	'date' => 'Datum',
	'desc' => 'Beschreibung',
	'last modified' => 'Erstellt',
	'actions' => 'Aufgaben',
	'bill as pdf' => 'Rechnung als PDF',
	'bill' => 'Rechnung',
	'Costs' => 'Preise',
	'identifier' => 'Systembezeichnung',
	'type' => 'Typ',
	'value' => 'Wert',
	'value [EUR]' => 'Wert [EUR]',
	'payment' => 'Einzahlung',
	'payback' => 'Auszahlung',
	'costs base' => 'Sockelbetrag',
	'costs singleParticipant' => 'Teilnehmer (Einzel)',
	'costs teamParticipant' => 'Teilnehmer (Mannschaft)',
	'settings' => 'Einstellungen',
	'city' => 'Ort',
	'city<br />' => '<span>Ort:</span><br />',
	'files attached<br />' => '<span>Angeh&auml;ngte Dateien:</span><br />',
	'navigation entry' => 'Navigationseintrag',
	'visible' => 'sichtbar',
	'not visible' => 'nicht sichtbar',
	'edit group' => 'Gruppe bearbeiten',
	'delete group' => 'Gruppe l&ouml;schen',
	'edit user' => 'Benutzer bearbeiten',
	'delete user' => 'Benutzer l&ouml;schen',
	'text/plain' => 'Textdokument',
	'application/pdf' => 'PDF-Dokument',
	'- choose -' => '- w&auml;hlen -',
	'* Please select an option' => '* Bitte wählen Sie eine Option',
	'saving failed, please contact the system administrator' => 'Speichern fehlgeschlagen, bitte Systembetreuer kontaktieren',
	'show older appointments' => '&auml;ltere Termine anzeigen...',
	'archived appointments' => 'Archivierte Termine',
	'validity' => 'G&uuml;ltigkeit',
	'club' => 'Vereine',
	'contact' => 'Ansprechpartner',
	'judo' => 'Judo',
	'judo_belt' => 'G&uuml;rtelfarben',
	'location' => 'Hallen',
	'protocol_types' => 'Protokolltypen',
	'staff' => 'Referenten',
	'defaults' => 'Vorgabewerte',
	'unknown error' => 'unbekannter Fehler',
	'provided row not exists' => 'angegebene Zeile existiert nicht',
	'not authorized to perform this action' => 'keine Berechtigung f&uuml;r diese Aktion',
	'Refresh this table' => 'Diese Tabelle aktualisieren',
	'read/list' => 'Lesen/Auflisten',
	'file_type' => 'Dateityp',
	'reload page' => 'Seite neu laden',
	'Create new year' => 'Erstelle neues Jahr',
	'New year created' => 'Neues Jahr erstellt',
	'Successfully created year #?year' => 'Neues Jahr #?year erfolgreich erstellt',
	'unknown/deleted' => 'unbekannt/gel&ouml;scht',
	'go to protocol' => 'Protokolldetails anzeigen',
	'open decisions' => 'Beschl&uuml;sse &ouml;ffnen',
	'Click to choose from' => 'Datei w&auml;hlen aus',
	'single/team' => 'Einzel/Mannschaft',
	'single' => 'Einzel',
	'team' => 'Mannschaft',
	'refresh announcement pdf file' => 'PDF erneuern',
	'start date' => 'Beginn',
	'end date' => 'Ende',
	'start date<br />' => '<span>Beginn</span><br />',
	'end date<br />' => '<span>Ende</span><br />',
	'end date after start date' => 'Ende muss nach Beginn liegen',
	'error loading entries' => 'Fehler beim Laden der Eintr&auml;ge',
	'loading...' => 'wird geladen...',
	'by' => 'von',
	'modified' => 'ge&auml;ndert',
	'appointment' => 'Termin',
	'color<br />' => '<span>Farbe:</span><br />',
	'color' => 'Farbe',
	'is external<br />' => '<span>extern:</span><br />',
	'is external' => 'extern',
	'is external appointment' => 'externer Termin',
	'Saving this as "external" appointment will delete any existing announcement! Continue?' => 'Wenn dieser Termin als "extern" gespeichert wird, werden vorhandene Ausschreibungen gelöscht! Fortfahren?',
	'show preset selection' => 'Vorlagenauswahl einblenden',
	'external' => 'extern',
	'manage school holidays' => 'Schulferien verwalten',
	'Please choose a year to edit:' => 'Bitte ein Jahr zur Bearbeitung ausw&auml;hlen:',
	'Edit school holiday for year #?year' => 'Schulferien f&uuml;r das Jahr #?year bearbeiten',
	'from:' => 'von:',
	'to:' => 'bis:',
	'userdefined' => 'benutzerdefiniert',
	'schedule' => 'Terminplan',
	'testimonials' => 'Ehrengaben',
	'Tributes' => 'Ehrungen',
	'Tributes: listall' => 'Ehrungen auflisten',
	'Tributes: new' => 'Neue Ehrung planen',
	'Tributes: delete' => 'Ehrung l&ouml;schen',
	'plan new tribute' => 'Neue Ehrung planen',
	'list tributes' => 'Ehrungen auflisten',
	'year' => 'Jahr',
	'testimonial' => 'Ehrengabe',
	'planned date' => 'geplant',
	'tribute start date' => 'begonnen',
	'tribute date' => 'geehrt',
	'select year' => 'Jahr ausw&auml;hlen',
	'select testimonial' => 'Ehrengabe ausw&auml;hlen',
	'Show all' => 'Alle anzeigen',
	'edit tribute' => 'Ehrung bearbeiten',
	'delete tribute' => 'Ehrung l&ouml;schen',
	'no results' => 'keine Ergebnisse',
	'planned tribute on' => 'Ehrung geplant am',
	'tribute given on' => 'Ehrung &uuml;berreicht am',
	'description' => 'Beschreibung',
	'tribute_history_type' => 'Ehrungsverlaufseintr&auml;ge',
	'System entry' => 'Systemeintrag',
	'Started planning tribute' => 'Planung begonnen',
	'Started planning tribute on' => 'Planung begonnen am',
	'Saved successfully.' => 'Erfolgreich gespeichert.',
	'Tributes: edit' => 'Ehrung bearbeiten',
	'edit tribute' => 'Ehrung bearbeiten',
	'Add new history entry' => 'Neuen Verlaufseintrag hinzuf&uuml;gen',
	'saving' => 'wird gespeichert',
	'save entry' => 'Eintrag speichern',
	'missing data' => 'fehlende Daten',
	'Subject' => 'Betreff',
	'Type' => 'Typ',
	'Content' => 'Eintrag',
	'delete tribute' => 'Ehrung l&ouml;schen',
	'Date' => 'Datum',
	'Click to change date' => 'Klicken um Datum zu &auml;ndern',
	'Monday' => 'Montag',
	'Tuesday' => 'Dienstag',
	'Wednesday' => 'Mittwoch',
	'Thursday' => 'Donnerstag',
	'Friday' => 'Freitag',
	'Saturday' => 'Samstag',
	'Sunday' => 'Sonntag',
	'January' => 'Januar',
	'February' => 'Februar',
	'March' => 'M&auml;rz',
	'April' => 'April',
	'May' => 'Mai',
	'June' => 'Juni',
	'July' => 'Juli',
	'August' => 'August',
	'September' => 'September',
	'October' => 'Oktober',
	'November' => 'November',
	'December' => 'Dezember',
	'Today' => 'Heute',
	'Delete' => 'L&ouml;schen',
//	'' => '',
);


?>
