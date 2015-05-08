{* ********************************************************************************************
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
 * ********************************************************************************************}
<div class="ui-corner-all ui-state-active" id="showUpload">{lang}Attach files{/lang}</div>
<div id="uploadForm">
	<div id="uploadFile">{lang}Browse...{/lang}</div>
	<div id="uploadButton" class="row last">
		{lang}File type{/lang}: <select id="fileType" class="control validate[required]">
			<option value="">{lang}- choose -{/lang}</option>
{foreach $fileTypes as $fileType}
			<option value="{$fileType.id}">{$fileType.name}</option>
{/foreach}
		</select>&nbsp;
		<input type="submit" id="upload" class="submit" value="{lang}Upload{/lang}" />
	</div>
</div>
<div class="ui-corner-all ui-state-default" id="showFiles">{lang}Files{/lang} (<span id="countFiles">{count($tributeFiles)}</span>)</div>
<div id="tributeFiles" class="round">
{if count($tributeFiles) > 0}
<div id="confirmDelete" title="{lang}Confirm{/lang}">{lang}You really want to delete?{/lang}</div>
<script type="text/javascript">{literal}$('#confirmDelete').dialog({modal: true,autoOpen: false,width: 400,height: 250,show: {effect: 'fade',duration: 500},dialogClass: "noClose"});{/literal}</script>
{foreach $tributeFiles as $file}
	<div class="fileEntry" id="fileEntry{$file->getId()}">
		<img src="img/common_delete.png" id="deleteFile{$file->getId()}" class="deleteFile" alt="{lang}delete{/lang}" title="{lang}delete file{/lang}" />
		<img src="api/filesystem/tribute_file/{$file->getId()}?thumb=1" alt="{$file->getName(false)}" title="{$file->getType('name')}" /><br />
		<a href="api/filesystem/tribute_file/{$file->getId()}" title="{lang file=$file->getName()}download #?file{/lang}">{$file->getName(false)}</a>
		<script type="text/javascript">{literal}$('#deleteFile{/literal}{$file->getId()}{literal}').click(function() {$('#confirmDelete').dialog('option', 'buttons',[{"text": "{/literal}{html_entity_decode(_l('Delete'), 32)}{literal}", "click": function() {$.ajax({method: "POST",url: "api/filesystem/tribute_file/{/literal}{$file->getId()}{literal}/delete",data: {"confirmed":true},dataType: "json",success: function(data) {if(data.result == "OK") {$('#fileEntry{/literal}{$file->getId()}{literal}').fadeOut(1000, function(){$(this).remove()});var countFiles = + $('#countFiles').text();$('#countFiles').text(countFiles - 1);} else {var deleteMessage = $('<div id="deleteMessage"></div>');$("body").append(deleteMessage);var windowWidth = $(window).width();deleteMessage.css({"margin-left": windowWidth * 0.1 / 2,"top": $(document).scrollTop() + 20}).text(data.message).fadeIn();setTimeout(function() {deleteMessage.fadeOut(3000);}, 5000);}}});$(this).dialog('close');}},{"text": "{/literal}{html_entity_decode(_l('Cancel'), 32)}{literal}", "click": function() {$(this).dialog('close');}}]);$('#confirmDelete').dialog('open');});{/literal}</script>
	</div>
{/foreach}
{else}
	<p id="noFiles">{lang}no files{/lang}</p>
{/if}
</div>