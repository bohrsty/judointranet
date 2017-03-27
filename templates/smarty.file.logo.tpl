{*****************************************************************************
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *****************************************************************************}
{if $configComplete === true}
<div id="logoUploadForm" class="Zebra_Form">
	<div id="uploadFile">{lang}Browse...{/lang}</div>
	<div id="uploadButton" class="row last">
		<input type="submit" id="upload" class="submit" value="{lang}Upload{/lang}" />
	</div>
</div>
{else}
<div>
	<p>
		{lang}The config for uploading logos is not complete, please check that none of the config entries starting with "logo." aren't empty.{/lang}
	</p>
</div>
{/if}
<div id="logoFiles" class="round">
	<div id="uploadedFiles">
		<p class="bold">{lang}Available logos{/lang}:</p>
		<div id="confirmDelete" title="{lang}Confirm{/lang}">{lang}You really want to delete?{/lang}</div>
		<script type="text/javascript">{literal}$('#confirmDelete').dialog({modal: true,autoOpen: false,width: 400,height: 250,show: {effect: 'fade',duration: 500},dialogClass: "noClose"});{/literal}</script>
{if count($logoFiles) > 0}
{foreach $logoFiles as $file}
		<div class="fileEntry" id="fileEntry{$file->getId()}">
			<img src="img/common_delete.png" id="deleteFile{$file->getId()}" class="deleteFile" alt="{lang}delete{/lang}" title="{lang}delete file{/lang}" />
			<img src="{$file->getAsImgSrc()}" alt="{$file->getName()}" title="{$file->getName()}" /><br />
			{$file->getName()}
			<script type="text/javascript">{literal}$('#deleteFile{/literal}{$file->getId()}{literal}').click(function() {$('#confirmDelete').dialog('option', 'buttons',[{"text": "{/literal}{html_entity_decode(_l('Delete'), 32)}{literal}", "click": function() {$.ajax({method: "POST",url: "api/internal.php?id={/literal}{$randomIdDelete}{literal}&signedApi={/literal}{$signedApiDelete}{literal}",data: {"confirmed":true,"logoId":{/literal}{$file->getId()}{literal}},dataType: "json",success: function(data) {if(data.result == "OK") {$('#fileEntry{/literal}{$file->getId()}{literal}').fadeOut(1000, function(){$(this).remove()});} else {var deleteMessage = $('<div id="deleteMessage"></div>');$("body").append(deleteMessage);var windowWidth = $(window).width();deleteMessage.css({"margin-left": windowWidth * 0.1 / 2,"top": $(document).scrollTop() + 20}).text(data.message).fadeIn();setTimeout(function() {deleteMessage.fadeOut(3000);}, 5000);}}});$(this).dialog('close');}},{"text": "{/literal}{html_entity_decode(_l('Cancel'), 32)}{literal}", "click": function() {$(this).dialog('close');}}]);$('#confirmDelete').dialog('open');});{/literal}</script>
		</div>
{/foreach}
{else}
		<p id="noFiles">{lang}no logos available{/lang}</p>
{/if}
	</div>
</div>