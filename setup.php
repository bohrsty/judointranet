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

// required lib
require('lib/common.inc.php');

// start session
session_start();

// get default config
$defaultConfig = parse_ini_file(JIPATH.'/cnf/default.ini', true);

// create template
$tpl = new JudoIntranetSmarty();
// set global variables in template
$tpl->assign('pagename', lang('setup#page#init#name'));
$tpl->assign('setupDisabledNavi', true);
$tpl->assign('systemLogo', 'img/logo.png');

// check versions
if(isset($_SESSION['setup']['version'])) {
	$checkVersion = $_SESSION['setup']['version'];
} else {
	$checkVersion = checkDbVersion();
}

// check access
$access = checkAccess();

if($access === true) {
	
	// smarty
	$tpl->assign('title', lang('setup#init#title#setup'));
	$tpl->assign('main', runSetup($checkVersion));
	$tpl->assign('jquery', true);
	$tpl->assign('zebraform', true);
	$tpl->assign('tinymce', false);
	
} else {
	
	// smarty
	$tpl->assign('title', lang('setup#init#Error#NotAuthorized'));
	$tpl->assign('main', $access);
	$tpl->assign('jquery', true);
	$tpl->assign('zebraform', false);
	$tpl->assign('tinymce', false);
}


// global smarty
$tpl->display('smarty.main.tpl');







/*
 * #############################
 * # functions                 #
 * #############################
 */
/**
 * lang reads the translated string for the given marker and returns it
 * 
 * @param string $string string to be parsed and "translated", splitmarker "#"
 * @return string translated value of the string
 */
function lang($string) {
	
	// split string
	$i = explode('#',$string,4);
	
	// check language
	$lang = 'de_DE';
	if(isset($_GET['l'])) {
		$lang = checkValidChars('getvalue', $_GET['l']);
	} elseif(isset($_SESSION['setup']['l'])) {
		$lang = $_SESSION['setup']['l'];
	}
	$_SESSION['setup']['l'] = $lang;
	
	// import lang-file
	if(is_file(JIPATH.'/cnf/lang/setupLang.'.$lang.'.php')) {
		include(JIPATH.'/cnf/lang/setupLang.'.$lang.'.php');
	} else {
		return '[language "'.$lang.'" not found]';
	}
	
	// check if is translated
	if(!isset($lang[$i[0]][$i[1]][$i[2]][$i[3]])) {
		return $string.' not translated';
	} else {
		return $lang[$i[0]][$i[1]][$i[2]][$i[3]];
	}
}


/**
 * checkValidChars($type, $value) checks the given $value by given $type using regexp
 * 
 * @param string $type type of string to choose regexp
 * @param string $value string to check
 * 
 * @return mixed false if not permitted chars, the value else
 */
function checkValidChars($type, $value) {
		
	// regexps
	$regexp = array(
  						'getvalue' => '/^[a-zA-Z0-9\.\-_\+\/=]*$/',
  						'postvalue' => '/^[a-zA-Z0-9äöüÄÖÜß\.,\-_\+!§\$%&\/()\[\]\{\}=`´;:\*#~\?<>|"@ \n\r\t]*$/',
	);
	
	// check if $value is string
	if(is_string($value) || is_numeric($value)) {
		
		// check if not permitted chars
		if(!preg_match($regexp[$type],$value)) {
	
			// nonconform return false
			return false;
		}
	}

	// correct return value
	return $value;
}


/**
 * checkAccess() checks if user has entered the setup code or generates the form
 * 
 * @return mixed true if has access, the form to enter the access code otherwise
 */
function checkAccess() {
	
	// check session variables
	if(isset($_SESSION['setup']['access'])) {
		return true;
	} else {
		
		// get setup key from config.ini
		$config = parse_ini_file(JIPATH.'/cnf/setup.ini', true);
		$setupKey = $config['setupKey'];
		
		// check setup keys
		if(isset($_SESSION['setup']['setupKey']) && $setupKey == $_SESSION['setup']['setupKey']) {
			
			// check hash against setup.ini
			if($setupKey == $_SESSION['setup']['setupKey']) {
				
				// set access
				$_SESSION['setup']['access'] = true;
				
				// return true
				return true;
			}
		} else {
			
			// generate hash and save in session
			if(!isset($_SESSION['setup']['setupKey'])) {
				$_SESSION['setup']['setupKey'] = hash_hmac('sha256', time(), 'judointranet');
			}
			
			// create actions
			$data['actions'] = array(
					array(
							'href' => $_SERVER['REQUEST_URI'],
							'title' => lang('setup#general#message#actions.repeat'),
							'name' => lang('setup#general#message#actions.repeat'),
						),
				);
			
			// set variables
			$data['type'] = 'messageWarn';
			$data['caption'] = lang('setup#checkAccess#message.setupKey#caption');
			$data['message'] = lang('setup#checkAccess#message.setupKey#message');
			$data['value'] = $_SESSION['setup']['setupKey'];
			
			// return
			return message($data);
		}
	}
}


/**
 * message($data) creates the HTML code of a message for the given $data
 * 
 * @param array $data array containing the required settings (type, caption, message, actions)
 * @return string HTML code of the message
 */
function message($data) {
	
	// display message
	$sInfo = new JudoIntranetSmarty();
	
	// assign variables
	$sInfo->assign('messageType', $data['type']);
	$sInfo->assign('messageCaption', $data['caption']);
	$sInfo->assign('messageMessage', $data['message']);
	$sInfo->assign('messageValue', (isset($data['value']) ? $data['value'] : ''));
	$sInfo->assign('messageActions', (isset($data['actions']) ? $data['actions'] : array()));
	
	// return
	return $sInfo->fetch('smarty.message.tpl');
}


/**
 * runSetup($checkVersion) executes the setup steps
 * 
 * @param int $checkVersion the result of the version check or the database connection
 * @return string HTML code to be displayed during the setup process
 */
function runSetup($checkVersion) {
	
	// get default config
	global $defaultConfig;
	
	// switch $checkVersion
	switch($checkVersion) {
		
		case VERSION_NO_CONFIG:
			
			// check step two
			if(isset($_SESSION['setup'][VERSION_NO_CONFIG]['config'])) {
				
				// create actions
				$data['actions'] = array(
						array(
								'href' => $_SERVER['REQUEST_URI'],
								'title' => lang('setup#general#message#actions.forward'),
								'name' => lang('setup#general#message#actions.forward'),
							),
					);
				
				// set variables
				$data['type'] = 'messageInfo';
				$data['caption'] = lang('setup#runSetup#message.ConfigInfo#caption');
				$data['message'] = lang('setup#runSetup#message.ConfigInfo#message');
				$data['value'] = nl2br($_SESSION['setup'][VERSION_NO_CONFIG]['config']);
			} else {
			
				// create config form
				// prepare form
				$form = new Zebra_Form(
						'setupConfig',		// id/name
						'post',					// method
						'setup.php'	// action
					);
				// set language
				$form->language('deutsch');
				// set docktype xhtml
				$form->doctype('xhtml');
				
				// elements
				// host
				$formIds['host'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelHost',	// id/name
						'host',			// for
						'host',			// label text
						array('inside' => true,)	// label inside
					);
				$host = $form->add(
								$formIds['host']['type'],		// type
								'host'		// id/name
					);
				$form->add(
						'note',			// type
						'noteHost',		// id/name
						'host',			// for
						lang('setup#runSetup#form#note.host')	// note text
					);
				
				// add rules
				$host->set_rule(
						array(
								'regexp' => array(
										$defaultConfig['regexp']['textarea.regexp.zebra'],
										'error',
										lang('setup#runSetup#form#regexp.allowedChars').' ['.$defaultConfig['regexp']['textarea.desc'].']',
									),
								'required' => array(
										'error',
										lang('setup#runSetup#form#required.host'),
									),
							)
					);
				
				// username
				$formIds['username'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelUsername',	// id/name
						'username',			// for
						'username',			// label text
						array('inside' => true,)	// label inside
					);
				$host = $form->add(
								$formIds['username']['type'],		// type
								'username'		// id/name
					);
				$form->add(
						'note',			// type
						'noteUsername',		// id/name
						'username',			// for
						lang('setup#runSetup#form#note.username')	// note text
					);
				
				// add rules
				$host->set_rule(
						array(
								'regexp' => array(
										$defaultConfig['regexp']['textarea.regexp.zebra'],
										'error',
										lang('setup#runSetup#form#regexp.allowedChars').' ['.$defaultConfig['regexp']['textarea.desc'].']',
									),
								'required' => array(
										'error',
										lang('setup#runSetup#form#required.username'),
									),
							)
					);
				
				// password
				$formIds['password'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelPassword',	// id/name
						'password',			// for
						'password',			// label text
						array('inside' => true,)	// label inside
					);
				$host = $form->add(
								$formIds['password']['type'],		// type
								'password'		// id/name
					);
				$form->add(
						'note',			// type
						'notePassword',		// id/name
						'password',			// for
						lang('setup#runSetup#form#note.password')	// note text
					);
				
				// add rules
				$host->set_rule(
						array(
								'regexp' => array(
										$defaultConfig['regexp']['textarea.regexp.zebra'],
										'error',
										lang('setup#runSetup#form#regexp.allowedChars').' ['.$defaultConfig['regexp']['textarea.desc'].']',
									),
								'required' => array(
										'error',
										lang('setup#runSetup#form#required.password'),
									),
							)
					);
				
				// database
				$formIds['database'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelDatabase',	// id/name
						'database',			// for
						'database',			// label text
						array('inside' => true,)	// label inside
					);
				$host = $form->add(
								$formIds['database']['type'],		// type
								'database'		// id/name
					);
				$form->add(
						'note',			// type
						'noteDatabase',		// id/name
						'database',			// for
						lang('setup#runSetup#form#note.database')	// note text
					);
				
				// add rules
				$host->set_rule(
						array(
								'regexp' => array(
										$defaultConfig['regexp']['textarea.regexp.zebra'],
										'error',
										lang('setup#runSetup#form#regexp.allowedChars').' ['.$defaultConfig['regexp']['textarea.desc'].']',
									),
								'required' => array(
										'error',
										lang('setup#runSetup#form#required.database'),
									),
							)
					);
				
				// timezone
				$formIds['timezone'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelTimezone',	// id/name
						'timezone',			// for
						'default_time_zone',			// label text
						array('inside' => true,)	// label inside
					);
				$host = $form->add(
								$formIds['timezone']['type'],		// type
								'timezone',		// id/name
								$defaultConfig['global']['default_time_zone']	// default
					);
				$form->add(
						'note',			// type
						'noteTimezone',		// id/name
						'timezone',			// for
						lang('setup#runSetup#form#note.timezone')	// note text
					);
				
				// add rules
				$host->set_rule(
						array(
								'regexp' => array(
										$defaultConfig['regexp']['textarea.regexp.zebra'],
										'error',
										lang('setup#runSetup#form#regexp.allowedChars').' ['.$defaultConfig['regexp']['textarea.desc'].']',
									),
							)
					);
				
				// locale
				$formIds['locale'] = array('valueType' => 'string', 'type' => 'text',);
				$form->add(
						'label',		// type
						'labelLocale',	// id/name
						'locale',			// for
						'locale',			// label text
						array('inside' => true,)	// label inside
					);
				$host = $form->add(
								$formIds['locale']['type'],		// type
								'locale',		// id/name
								$defaultConfig['global']['locale']	// default
					);
				$form->add(
						'note',			// type
						'noteLocale',		// id/name
						'locale',			// for
						lang('setup#runSetup#form#note.locale')	// note text
					);
				
				// add rules
				$host->set_rule(
						array(
								'regexp' => array(
										$defaultConfig['regexp']['textarea.regexp.zebra'],
										'error',
										lang('setup#runSetup#form#regexp.allowedChars').' ['.$defaultConfig['regexp']['textarea.desc'].']',
									),
							)
					);
				
				// submit-button
				$form->add(
						'submit',		// type
						'buttonSubmit',	// id/name
						lang('setup#runSetup#form#submitButton')	// value
					);
				
				// validate form
				if($form->validate()) {
					
					// get data
					$data = array();
					foreach($formIds as $name => $settings) {
						
						// check $_POST values
						$data[$name] = checkValidChars('postvalue', $_POST[$name]);
					}
					
					// prepare $_SESSION['setup'][VERSION_NO_CONFIG]['config']
					$_SESSION['setup'][VERSION_NO_CONFIG]['config'] = '
; configuration
; copy from defaults.ini and adapt to override defaults

[global]'.
($data['timezone'] != $defaultConfig['global']['default_time_zone'] ? PHP_EOL.'default_time_zone="'.$data['timezone'].'"' : '').
($data['locale'] != $defaultConfig['global']['locale'] ? PHP_EOL.'locale="'.$data['locale'].'"' : '').'

[db]
; database-credentials
host="'.$data['host'].'"
username="'.$data['username'].'"
password="'.$data['password'].'"
database="'.$data['database'].'"
';

					// create actions
					$data['actions'] = array(
							array(
									'href' => $_SERVER['REQUEST_URI'],
									'title' => lang('setup#general#message#actions.forward'),
									'name' => lang('setup#general#message#actions.forward'),
								),
						);
					
					// set variables
					$data['type'] = 'messageInfo';
					$data['caption'] = lang('setup#runSetup#message.ConfigInfo#caption');
					$data['message'] = lang('setup#runSetup#message.ConfigInfo#message');
					$data['value'] = nl2br($_SESSION['setup'][VERSION_NO_CONFIG]['config']);
				} else {
					
					// set variables
					$data['type'] = 'messageError';
					$data['caption'] = lang('setup#runSetup#message.noConfigError#caption');
					$data['message'] = lang('setup#runSetup#message.noConfigError#message').$form->render('', true);
				}
			}
			
			// return
			return message($data);
			
			break;
		
		case VERSION_DB_ERROR:
			
			// create actions
			$data['actions'] = array(
					array(
							'href' => $_SERVER['REQUEST_URI'],
							'title' => lang('setup#general#message#actions.repeat'),
							'name' => lang('setup#general#message#actions.repeat'),
						),
				);
			
			// set variables
			$data['type'] = 'messageError';
			$data['caption'] = lang('setup#runSetup#message.dbError#caption');
			$data['message'] = lang('setup#runSetup#message.dbError#message');
			$data['value'] = $_SESSION['setup']['dbConnectError'];
			
			// return
			return message($data);
			
			break;
		
		case VERSION_EQUAL:
			
			// create actions
			$data['actions'] = array(
					array(
							'href' => 'index.php',
							'title' => lang('setup#general#message#actions.homepage'),
							'name' => lang('setup#general#message#actions.homepage'),
						),
				);
			
			// set variables
			$data['type'] = 'messageInfo';
			$data['caption'] = lang('setup#runSetup#message.upToDate#caption');
			$data['message'] = lang('setup#runSetup#message.upToDate#message');
			
			// return
			return message($data);
			
			break;
		
		case VERSION_LOWER:
			
			// set variables
			$data['type'] = 'messageWarn';
			$data['caption'] = lang('setup#runSetup#message.codeLower#caption');
			$data['message'] = lang('setup#runSetup#message.codeLower#message');
			
			// return
			return message($data);
			
			break;
		
		case VERSION_EMPTY_DB:
			
			// create actions
			$data['actions'] = array(
					array(
							'href' => $_SERVER['REQUEST_URI'],
							'title' => lang('setup#general#message#actions.forward'),
							'name' => lang('setup#general#message#actions.forward'),
						),
				);
			
			// set variables
			$data['type'] = 'messageWarn';
			$data['caption'] = lang('setup#runSetup#message.dbEmpty#caption');
			$data['message'] = lang('setup#runSetup#message.dbEmpty#message');
			
			// set install mode
			$_SESSION['setup']['action'] = 'install';
			
			// return
			return message($data);
			
			break;
		
		case VERSION_HIGHER:
			
			// create actions
			$data['actions'] = array(
					array(
							'href' => $_SERVER['REQUEST_URI'],
							'title' => lang('setup#general#message#actions.forward'),
							'name' => lang('setup#general#message#actions.forward'),
						),
				);
			
			// set variables
			$data['type'] = 'messageWarn';
			$data['caption'] = lang('setup#runSetup#message.upgradeRequired#caption');
			$data['message'] = lang('setup#runSetup#message.upgradeRequired#message');
			
			// set upgrade mode
			$_SESSION['setup']['action'] = 'upgrade';
			
			// return
			return message($data);
			
			break;
		
		case VERSION_DO_INSTALL:
			
			return doInstall();
			
			break;
		
		case VERSION_DO_UPGRADE:
			
			return doUpgrade();
			
			break;
		
		default:
			// set variables
			$data['type'] = 'messageError';
			$data['caption'] = lang('setup#runSetup#message.finalError#caption');
			$data['message'] = lang('setup#runSetup#message.finalError#message');
			
			// return
			return message($data);
			
			break;
		
	}
}
	
	
/**
 * doInstall() installs judointranet into an empty database and generate the views
 * 
 * @return string HTML to be displayed during install
 */
function doInstall() {
	
	// enable sql functions and import them
	define('SETUPSQL', true);
	include_once(JIPATH.'/sql/setupSql.php');
	
	// import initial sql
	$returnInit = initMysql();
	if($returnInit['returnValue'] === false) {
		
		// message
		// set variables
		$data['type'] = 'messageError';
		$data['caption'] = lang('setup#doInstall#error#caption');
		$data['message'] = lang('setup#doInstall#error#message');
		$data['value'] = $returnInit['returnMessage'];
		return message($data);
	}
	
	// upgrade to last version
	return doUpgrade();
}


/**
 * doUpgrade() upgrades and migrates the data of judointranet in an existing database and 
 * generate the views
 * 
 * @return string HTML to be displayed during upgrade
 */
function doUpgrade() {
	
	// enable sql functions and import them
	if(!defined('SETUPSQL')) {
		define('SETUPSQL', true);
	}
	include_once(JIPATH.'/sql/setupSql.php');
	
	// upgrade sql
	$returnInit = versionMysql();
	if($returnInit['returnValue'] === false) {
		
		// message
		// set variables
		$data['type'] = 'messageError';
		$data['caption'] = lang('setup#doUpgrade#error#caption');
		$data['message'] = lang('setup#doUpgrade#error#message');
		$data['value'] = $returnInit['returnMessage'];
	} else {
		
		// get version
		$version = Db::singleValue('SELECT `value` FROM `config` WHERE `name`=\'global.version\'');
		// create actions
		$data['actions'] = array(
				array(
						'href' => 'index.php',
						'title' => lang('setup#general#message#actions.finished'),
						'name' => lang('setup#general#message#actions.finished'),
					),
			);
		
		// set variables
		$data['type'] = 'messageInfo';
		$data['caption'] = lang('setup#doUpgrade#success#caption');
		$data['message'] = lang('setup#doUpgrade#success#message');
		$data['value'] = 'r'.str_pad($version, 3, '0', STR_PAD_LEFT);
		
		// clear setup session
		unset($_SESSION['setup']);
	}
	
	// return
	return message($data);
}


?>