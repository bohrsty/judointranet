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
'setup' => array(
		'page' => array(
			'init' => array(
				'name' => 'Setup'
			),
		),
		'init' => array(
			'title' => array(
				'default' => 'Setup',
				'setup' => 'Setup: Installation/Update',
			),
			'Error' => array(
				'NotAuthorized' => 'FEHLER - Nicht berechtigt'
			),
		),
		'general' => array(
			'message' => array(
				'actions.repeat' => 'wiederholen...',
				'actions.forward' => 'weiter...',
				'actions.homepage' => 'zur Startseite...',
				'actions.finished' => 'beenden...',
			),
		),
		'runSetup' => array(
			'message.upToDate' => array(
				'caption' => 'Installation aktuell',
				'message' => 'Die Installation ist auf dem neuesten Stand, keine automatischen
								Aktionen m&ouml;glich.',
			),
			'message.codeLower' => array(
				'caption' => 'Code veraltet',
				'message' => 'Der verwendete Quellcode ist veraltet gegen&uuml;ber der Datenbankversion,
								bitte aktualisieren Sie den Quellcode, z.B.: per git.',
			),
			'message.dbEmpty' => array(
				'caption' => 'Leere Datenbank',
				'message' => 'Die angegebene Datenbank ist leer, bitte auf "weiter..." klicken, um die
								Installation zu starten.',
			),
			'message.upgradeRequired' => array(
				'caption' => 'Aktualisierung erforderlich',
				'message' => 'Die Datenbankversion ist veraltet, bitte auf "weiter..." klicken, um die
								Installation zu starten.',
			),
			'message.finalError' => array(
				'caption' => 'Nichtbehebbarer Fehler',
				'message' => 'Das System ist in einem nicht definierten Fehlerzustand, manuelle
								Analyse notwendig!',
			),
			'message.dbError' => array(
				'caption' => 'Fehler bei der Datenbankverbindung',
				'message' => 'Die Verbindung zur Datenbank ist fehlgeschlagen, u.a.
								Meldung pr&uuml;fen!',
			),
			'message.noConfigError' => array(
				'caption' => 'Fehler beim Lesen der Konfiguration',
				'message' => 'Es konnte keine Konfigurationsdatei gefunden werden, oder die
								notwendigen Einstellungen sind nicht vollst&auml;ndig!<br />
								Bitte die notwendigen Felder unten ausf&uuml;llen und "weiter"
								klicken.<br /><br />',
			),
			'message.ConfigInfo' => array(
				'caption' => 'Konfiguration',
				'message' => 'Folgende Konfigurationseinstellungen in die Datei "cnf/config.ini"
								eintragen, oder bestehende durch die neuen ersetzen:',
			),
			'form' => array(
				'note.host' => 'Hostname oder IP-Adresse des MySQL-/MariaDB-Servers (z.B.: localhost)',
				'note.username' => 'Benutzername f&uuml;r die Verbindung zur Datenbank (z.B.: judointranet)',
				'note.password' => 'Kennwort des Benutzers f&uuml;r die Verbindung zur Datenbank
									(z.B.: judointranet)',
				'note.database' => 'Name der Datenbank (z.B.: judointranet)',
				'note.timezone' => 'Zeitzone die f&uuml;r die Datumsfunktionen (z.B.: Europe/Berlin).
									Nur anpassen, wenn die Zeitzone von der Vorgabe abweicht!',
				'note.locale' => 'Lokalisierung von Datum, Zahlen, W&auml;hrung, etc. (z.B.: de_DE@euro).
									Nur anpassen, wenn die Lokalisierung von der Vorgabe abweicht!',
				'required.host' => '"host" ist eine erforderliche Eingabe!',
				'required.username' => '"username" ist eine erforderliche Eingabe!',
				'required.password' => '"password" ist eine erforderliche Eingabe!',
				'required.database' => '"database" ist eine erforderliche Eingabe!',
				'regexp.allowedChars' => 'Es k&ouml;nnen nur folgende Zeichen eingegeben werden:',
				'submitButton' => 'weiter...',
			),
		),
		'checkAccess' => array(
			'message.setupKey' => array(
				'caption' => 'Setup-Schl&uuml;ssel fehlt',
				'message' => 'Der Schl&uuml;ssel zur Absicherung des Setup-Vorgangs in der
								Datei "cnf/setup.ini" ist leer oder nicht aktuell. Bitte kopieren
								Sie "cnf/setup.ini.dist" nach "cnf/setup.ini" und/oder tragen
								Sie die folgende Zeichenkette anstelle der aktuellen Zeichenkette
								als Wert der Konfigurationseinstellung "setupKey" in die Datei
								"cnf/setup.ini" ein und klicken Sie dann auf "wiederholen...".',
			),
		),
		'initMysql' => array(
			'error' => array(
				'initMysqlNotExistsReadable' => '"init_mysql.sql" existiert nicht oder nicht lesbar!',
				'dbQueryFailed' => 'Einlesen der Datenbank-Struktur fehlgeschlagen:<br />',
				'notMigratedEntries' => 'Es konnten nicht alle Eintr&auml;ge &uuml;bertragen werden,
										in folgender Tabelle m&uuml;ssen die &uuml;brigen Eintr&auml;ge
										 manuell migriert werden und die Tabelle anschlie&szlig;end
										 gel&ouml;scht werden:<br />',
			),
		),
		'doInstall' => array(
			'error' => array(
				'caption' => 'Fehler bei der Installation',
				'message' => 'Bei der Installation ist folgender Fehler aufgetreten:',
			),
		),
		'doUpgrade' => array(
			'error' => array(
				'caption' => 'Fehler bei der Installation',
				'message' => 'Beim Aktualisieren der Datenbank ist folgender Fehler aufgetreten:',
			),
			'success' => array(
				'caption' => 'Aktualisierung erfolgreich',
				'message' => 'Die Aktualisierung der Datenbank war erfolgreich.<br />
								JudoIntranet l&auml;uft nun in der Version:',
			),
		),
	),
);

?>
