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
 * this file is called by zebra_form if custom template is used.
 * it requires that $form->render() takes an array as third argument with
 * 0 => $formIds and 1 => 'smarty-template-file-name'
 */

// prepare variables
$formIds = $variables[0];
$template = $variables[1];
$permissionConfig = $variables[2];


// prepare elements
$elements = array();
$permissions = array();
// walk through $formIds
foreach($formIds as $elementName => $settings) {
	
	$elements[$elementName]['label'] = (isset(${'label'.ucfirst($elementName)}) ? ${'label'.ucfirst($elementName)} : '');
	// check if checkbox or radio
	if($settings['type'] == 'checkbox' || $settings['type'] == 'checkboxex' || $settings['type'] == 'radio' || $settings['type'] == 'radios') {
		$elements[$elementName]['element'] = (isset(${$elementName.'_'.$settings['default']}) ? ${$elementName.'_'.$settings['default']} : '');
	} else {
		$elements[$elementName]['element'] = (isset(${$elementName}) ? ${$elementName} : '');
	}
	$elements[$elementName]['note'] = (isset(${'note'.ucfirst($elementName)}) ? ${'note'.ucfirst($elementName)} : '');	
}

// walk through permission ids
foreach($permissionConfig['ids'] as $permissionName => $settings) {
	
	$permissions[$permissionName]['label'] = (isset(${'label'.ucfirst($permissionName)}) ? ${'label'.ucfirst($permissionName)} : '');
	$permissions[$permissionName]['element']['r'] = (isset(${$permissionName.'_r'}) ? ${$permissionName.'_r'} : '');
	$permissions[$permissionName]['element']['w'] = (isset(${$permissionName.'_w'}) ? ${$permissionName.'_w'} : '');
	$permissions[$permissionName]['note'] = (isset(${'note'.ucfirst($permissionName)}) ? ${'note'.ucfirst($permissionName)} : '');	
}


// get smarty template
$sForm = new JudoIntranetSmarty();

// assign elements, permissions and buttons
$sForm->assign('elements', $elements);
$sForm->assign('permissions', $permissions);
$sForm->assign('buttonSubmit',$buttonSubmit);
// assign errors
$sForm->assign('error',(isset($zf_error) ? $zf_error : (isset($error) ? $error : '')));
// assign tab names
$sForm->assign('tabElements', Object::lang('zebraTemplate#tabs#name#elements'));
$sForm->assign('tabPermissions', Object::lang('zebraTemplate#tabs#name#permissions'));
// assign permission heads
$sForm->assign('iconRead', $permissionConfig['iconRead']);
$sForm->assign('iconEdit', $permissionConfig['iconEdit']);


// echo template
echo $sForm->fetch($template);
?>
