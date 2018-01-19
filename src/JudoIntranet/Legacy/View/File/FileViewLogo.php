<?php

/*
 * This file is part of the JudoIntranet project.
*
* (c) Nils Bohrs
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace JudoIntranet\Legacy\View\File;

/**
 * class FileViewLogo implements the control of the id "logo" file page
 */
class FileViewLogo extends \FileView {

	/*
	 * class-variables
	 */
	private $smarty;

	/*
	 * getter/setter
	 */

	/*
	 * constructor/destructor
	 */
	public function __construct() {

		// setup parent
		parent::__construct();

		// create smarty object
		$this->smarty = new \JudoIntranetSmarty();
	}


	/**
	 * show() generates the output of the page
	 *
	 * @return string output for the page to be added to the template
	 */
	public function show() {

		// pagecaption
		$this->getTpl()->assign('pagecaption', _l('logos').'&nbsp;'.$this->helpButton(HELP_MSG_FILELOGO));

		// return
		return $this->getListallLogos();
	}


	/**
	 * getListallLogos() generates the logo listing
	 *
	 * @return string HTML string of the generated listing
	 */
	private function getListallLogos() {
		
		// get allowed filetypes
		$allowedFileTypes = $this->getAllowedFiletypes();
		// get max file size
		$maxFileSize = ($this->getGc()->get_config('logo.maxFileSize') != '' ? $this->getGc()->get_config('logo.maxFileSize').' * 1024' : ' 0');
		
		// check configuration
		$configComplete = (
			$this->getGc()->get_config('logo.allowedFileTypes') != ''
			&& $this->getGc()->get_config('logo.maxFileSize') != ''
		);
		$this->smarty->assign('configComplete', $configComplete);
		
		// get logos
		$repositoryLogo = $this->getDoctrine()->getRepository('JudoIntranet:Logo');
		$logos = $repositoryLogo->findByValid(1);
		
		// assign to template
		$this->smarty->assign('logoFiles', $logos);
		
		// prepare api signature for upload
		// get random id
		$randomIdUpload = \Object::getRandomId();
		
		// collect data for signature
		$dataUpload = array(
				'apiClass' => 'LogoFileUpload',
				'apiBase' => 'file.php',
				'randomId' => $randomIdUpload,
		);
		$_SESSION['api'][$randomIdUpload] = $dataUpload;
		$_SESSION['api'][$randomIdUpload]['time'] = time();
		$signedApiUpload = base64_encode(hash_hmac('sha256', json_encode($dataUpload), $this->getGc()->get_config('global.apikey')));
		
		// prepare api signature for delete
		// get random id
		$randomIdDelete = \Object::getRandomId();
		
		// collect data for signature
		$dataDelete = array(
				'apiClass' => 'LogoFileDelete',
				'apiBase' => 'file.php',
				'randomId' => $randomIdDelete,
		);
		$_SESSION['api'][$randomIdDelete] = $dataDelete;
		$_SESSION['api'][$randomIdDelete]['time'] = time();
		$signedApiDelete = base64_encode(hash_hmac('sha256', json_encode($dataDelete), $this->getGc()->get_config('global.apikey')));
		
		// assign to template
		$this->smarty->assign('randomIdDelete', $randomIdDelete);
		$this->smarty->assign('signedApiDelete', $signedApiDelete);
		
		// activate jquery upload file
		$this->getTpl()->assign('jqueryUploadFile', true);
		
		// add java script
		$this->add_jquery('
				var uploadMessage = $(\'<div id="uploadMessage"></div>\');
				var uploadObject = $("#uploadFile").uploadFile({
					url: "api/internal.php?id='.$randomIdUpload.'&signedApi='.$signedApiUpload.'",
					returnType: "json",
					'.$allowedFileTypes.',
					uploadButtonClass: "button",
					dragDrop: true,
					autoSubmit: false,
					multiple: false,
					maxFileCount: 1,
					maxFileSize: '.$maxFileSize.',
					onSuccess: function(files, response, xhr, pd) {
						$("body").append(uploadMessage);
						var windowWidth = $(window).width();
						uploadMessage.css({
								"background-color": (response.result == "ERROR" ? "#ff915f" : "#91ff5f"),
								"border-color": (response.result == "ERROR" ? "red" : "green"),
								"margin-left": windowWidth * 0.1 / 2,
								"top": $(document).scrollTop() + 20
							})
							.text(response.message)
							.fadeIn();
						setTimeout(function() {
							uploadMessage.fadeOut(3000);
						}, 5000);
						if(response.result == "OK") {
							$(\'#noFiles\').remove();
							var deleteFile = $(\'<img src="img/common_delete.png" id="deleteFile\'+response.data.id+\'" class="deleteFile" alt="'._l('delete').'" title="'._l('delete file').'" />\');
							var newFile = $(\'<div id="fileEntry\'+response.data.id+\'" class="fileEntry"></div>\');
							var newImg = $(\'<img src="\'+response.data.src+\'" alt="\'+response.data.name+\'" title="\'+response.data.name+\'" />\');
							var br = $(\'<br />\');
							var newName = response.data.name;
							var script = $(\'<script>\').attr("type", "text/javascript").text(\'$(document).on("click","#deleteFile\'+response.data.id+\'" , function() {$("#confirmDelete").dialog("option", "buttons",[{"text": "'.html_entity_decode(_l('Delete'), ENT_XHTML).'", "click": function() {$.ajax({method: "POST",url: "api/internal.php?id='.$randomIdDelete.'&signedApi='.$signedApiDelete.'",data: {"confirmed":true,"logoId":\'+response.data.id+\'},dataType: "json",success: function(data) {if(data.result == "OK") {$("#fileEntry\'+response.data.id+\'").fadeOut(1000, function(){$(this).remove()});} else {var deleteMessage = $("<div>").attr("id", "deleteMessage"+response.data.id);$("body").append(deleteMessage);var windowWidth = $(window).width();deleteMessage.css({"margin-left": windowWidth * 0.1 / 2,"top": $(document).scrollTop() + 20}).text(data.message).fadeIn();setTimeout(function() {deleteMessage.fadeOut(3000);}, 5000);}}});$(this).dialog("close");}},{"text": "'.html_entity_decode(_l('Cancel'), ENT_XHTML).'", "click": function() {$(this).dialog("close");}}]);$("#confirmDelete").dialog("open");});\');
							newFile.append(deleteFile).append(newImg).append(br).append(newName).append(script);
							$(\'#logoFiles\').append(newFile);
						}
					},
					onError: function(files, status, message, pd) {
						$("body").append(uploadMessage);
						var windowWidth = $(window).width();
						uploadMessage.css({
								"background-color":"#ff915f",
								"border-color": "red",
								"margin-left": windowWidth * 0.1 / 2,
								"top": $(document).scrollTop() + 20
							})
							.text(message)
							.fadeIn();
						setTimeout(function() {
							uploadMessage.fadeOut(3000);
						}, 5000);
					},
					dragDropStr: "<span><b>Dateien hier hineinziehen (Drag & Drop)</b></span>",
					abortStr: "Abbrechen",
					cancelStr: "Entfernen",
					deletelStr: "Löschen",
					doneStr: "Fertig",
					multiDragErrorStr: "Mehrere Dateien per Drag & Drop ist nicht erlaubt.",
					extErrorStr: "nicht erlaubt. Erlaubte Dateitypen: ",
					duplicateErrorStr: "nicht erlaubt. Datei existiert bereits.",
					sizeErrorStr: "nicht erlaubt. Maximale Dateigröße: ",
					uploadErrorStr: "Hochladen ist nicht erlaubt.",
					maxFileCountErrorStr: " nicht erlaubt. Maximale Anzahl Dateien: ",
					downloadStr: "Herunterladen",
					uploadFolder: "tmp"
				});
				$("#upload").click(function(e) {
					e.preventDefault();
					uploadObject.startUpload();
				});
			');
		
		// return
		return $this->smarty->fetch('smarty.file.logo.tpl');
	}
	
	
	/**
	 * getAllowedFiletypes() generates the allowedTypes and acceptFiles option for jquery-upload-file
	 * from config and database
	 * 
	 * @return string the generated options string
	 */
	private function getAllowedFiletypes() {
		
		// prepare return
		$allowedTypes = 'allowedTypes: "';
		$acceptFiles = 'acceptFiles: "';
		
		// get allowed filetype ids
		$config = $this->getGc()->get_config('logo.allowedFileTypes');
		$allowedFiletypeIds = explode(',', $config);
		
		// check if $config is empty
		if($config != '') {
		
			// create query builder
			$repositoryFileType = $this->getDoctrine()->getRepository('JudoIntranet:FileType');
			$qb = $repositoryFileType->createQueryBuilder('ft');
			
			// create query
			$query = $qb
				->where($qb->expr()->in('ft.id', $allowedFiletypeIds))
				->orderBy('ft.name', 'ASC')
				->getQuery();
			
			// get result
			$fileTypes = $query->getResult();
			
			// get mime type and extension as string for jquery-upload-file
			foreach($fileTypes as $fileType) {
				$allowedTypes .= $fileType->getExtension().',';
				$acceptFiles .= $fileType->getMimeType().',';
			}
			$allowedTypes = substr($allowedTypes, 0, -1);
			$acceptFiles = substr($acceptFiles, 0, -1);
		}
		
		// return
		return $allowedTypes.'",'.PHP_EOL.$acceptFiles.'"';
	}
}