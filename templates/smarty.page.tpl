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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>{$title}</title>
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
		<script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.11.4.min.js"></script>
		<script type="text/javascript" src="{if is_file('js/jquery.ui.datepicker-{$sLang}.js')}js/jquery.ui.datepicker-{$sLang}.js{else}js/jquery.ui.datepicker-de.js{/if}"></script>
		<script type="text/javascript" src="js/jquery.ui.autocomplete.html.js"></script>
{if isset($zebraform) and $zebraform}
		<link rel="stylesheet" type="text/css" href="css/zebra_form/zebra_form.css" />
		<script type="text/javascript" src="js/zebra_form.js"></script>
{/if}
{if isset($tinymce) and $tinymce}
		<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
{/if}
{if isset($jtable) and $jtable}
		<script type="text/javascript" src="js/jtable/jquery.jtable.min.js"></script>
		<script type="text/javascript" src="{if is_file('js/jtable/localization/jquery.jtable.{$sLang}.js')}js/jtable/localization/jquery.jtable.{$sLang}.js{else}js/jtable/localization/jquery.jtable.de.js{/if}"></script>
{/if}
{if isset($validationEngine) and $validationEngine}
		<script type="text/javascript" src="js/validationEngine.jquery/jquery.validationEngine.js"></script>
		<script type="text/javascript" src="{if is_file('js/validationEngine.jquery/languages/jquery.validationEngine-{$sLang}.js')}js/validationEngine.jquery/languages/jquery.validationEngine-{$sLang}.js{else}js/validationEngine.jquery/languages/jquery.validationEngine-de.js{/if}"></script>
		
{/if}
{if isset($fullcalendar) and $fullcalendar}
		<script type="text/javascript" src="js/moment-with-locales.min.js"></script>
		<script type="text/javascript" src="js/fullcalendar/fullcalendar.min.js"></script>
		<script type="text/javascript" src="{if is_file('js/fullcalendar/lang/{$sLang}.js')}js/fullcalendar/lang/{$sLang}.js{else}js/fullcalendar/lang/de.js{/if}"></script>
		<link rel="stylesheet" type="text/css" href="css/fullcalendar.min.css" />
{/if}
{if isset($simpleColor) and $simpleColor}
		<script type="text/javascript" src="js/jquery-simple-color/jquery.simple-color.min.js"></script>
{/if}
{if isset($jsdifflib) and $jsdifflib}
		<script type="text/javascript" src="js/difflib.js"></script>
		<script type="text/javascript" src="js/diffview.js"></script>
{/if}
{if isset($jqueryUploadFile) and $jqueryUploadFile}
		<script type="text/javascript" src="js/jquery-upload-file/jquery.uploadfile.min.js"></script>
		<script type="text/javascript" src="js/jquery.form.js"></script>
{/if}
		<script type="text/javascript" src="js/page.js"></script>
{if isset($jtable) and $jtable}
		<link rel="stylesheet" type="text/css" href="css/jtable/jtable_jqueryui.min.css" />
{/if}
{if isset($jqueryUploadFile) and $jqueryUploadFile}
		<link rel="stylesheet" type="text/css" href="css/uploadfile.min.css" />
{/if}
{if isset($validationEngine) and $validationEngine}
		<link rel="stylesheet" type="text/css" href="css/validationEngine.jquery.css" />
{/if}
		<link rel="stylesheet" type="text/css" href="css/start/jquery-ui-1.11.4.min.css" />
		<link rel="stylesheet" type="text/css" href="css/page.css" />
		<script type="text/javascript">
{if isset($usersettingsJsToToggle) && isset($usersettingsJsId) && isset($usersettingsJsTime)}
			$(function() { $("{$usersettingsJsToToggle}").hide(); $("{$usersettingsJsId}").click(function() {ldelim} $("{$usersettingsJsToToggle}").slideToggle({$usersettingsJsTime}); {rdelim}); });
{/if}
{if (isset($permissionJs) && $permissionJs) || (isset($userAdminJs) && $userAdminJs)}
{literal}
			function clearRadio(radioName) { $('[name=' + radioName + ']').prop('checked', false) }
			function selectRadio(radioId) { $('[id=' + radioId + ']').prop('checked', true) }
{/literal}
{/if}
{if isset($tabsJs) && $tabsJs}
			$(function() {ldelim} $( "#tabs" ).tabs({if isset($tabsJsOptions)}{$tabsJsOptions}{/if}); {rdelim});
{/if}
{if isset($accordionJs) && $accordionJs}
{literal}
			$(function() { $( "#accordion" ).accordion({ icons: false, heightStyle: "content", active: {/literal}{$accordionActive}{literal} }); });
{/literal}
{/if}
		</script>
{if isset($manualjquery) && $manualjquery!='' || isset($help)}
		<script type="text/javascript">
			$(document).ready(function() {ldelim}
{literal}
				$("a[rel='external']").attr("target", "_blank");
				$(function() {
					$(".{/literal}{$help.buttonClass}{literal}").click(function() {
						var id = this.id.substr(1);
						var dialogDiv = $('<div id="dialog_'+id+'" title="{/literal}{lang}help{/lang}{literal}" style="display: none"></div>');
						$.ajax({
							url: 'api/help.php?hid='+id,
							cache: false
						})
						.done(function(data) {
							var response = $.parseJSON(data);
							dialogDiv.append('<h3>'+response.title+'</h3>');
							dialogDiv.append(response.content);
							dialogDiv.append('<p class="helpId">['+id+']</p>');
						});
						$('body').append(dialogDiv);
						dialogDiv.dialog({
							autoOpen: false,
							show: {
								effect: '{/literal}{$help.effect}{literal}',
								duration: {/literal}{$help.effectDuration}{literal}
							},
							position: { 
								my: 'right top', 
								at: 'right top', 
								of: window
							},
							closeText: '{/literal}{$help.closeText}{literal}',
							minWidth: 600,
							minHeight: 250,
							maxHeight: 600,
							close: function(event, ui) {dialogDiv.remove();}
						});
						var isOpen = dialogDiv.dialog('isOpen');
						if(isOpen) {
							dialogDiv.dialog('moveToTop');
						} else {
							dialogDiv.dialog('open');
						}
					})
				});
{/literal}
{if isset($naviSecondJs) && $naviSecondJs===true}
{literal}
				$.each([{/literal}{$naviSecondIds}{literal}], function(id, value) {
					$(document).on('click', '#naviItem_'+ value +' a', function(event) {
						event.preventDefault();
						$.ajax({
							url:'api/calendar/schedule/'+ value,
							dataType: 'json',
							cache: false
						})
						.done(function(response) {
							var div = $('<div>')
								.appendTo($('body'))
								.dialog({
									autoOpen: true,
									modal: true,
									position:
									{
										my: 'center',
										at: 'center',
										of: window
									},
									minWidth: 600,
									minHeight: 250,
									maxHeight: 500,
									closeText: '{/literal}{$naviSecondCloseText}{literal}',
									close: function() {div.remove();}
								});
							if(response.result == 'OK') {
								div.append($('<p>')
										.text(response.data.message)
										.css({"font-weight":"bold"})
								);
								$.each(response.data.values, function(id, entry) {
									div.append($('<p>')
										.text(entry.text)
										.append($('<a>')
											.attr('href', 'calendar.php?id=schedule&year='+ entry.year)
											.attr('title', entry.title)
											.text(entry.year)
										)
									)
								});
							} else {
								div.append($('<p>').text(response.message));
							}
						});
					});
				});
{/literal}
{/if}
{if isset($jsRedirect) && $jsRedirect === true}
				setTimeout(function() {ldelim} window.location.replace('{$jsRedirectUri}'); {rdelim}, {$jsRedirectTimeout});
{/if}
				{$manualjquery}
			{rdelim});
		</script>
{/if}
{if isset($tinymce) and isset($tmce)}
		<script type="text/javascript">
			function initTinyMce(contentCss) {ldelim}
				tinyMCE.init({ldelim}
			        // General options
			        mode : "exact",
			        elements : "{$tmce.element}",
			        theme : "advanced",
			        plugins : "spellchecker,pagebreak,style,table",
			        language : "{if is_file('js/tiny_mce/langs/{$sLang}.js')}{$sLang}{else}de{/if}",
			        height : 500,
			
			        // Theme options
			        theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,fontselect,fontsizeselect,styleselect,removeformat,|,undo,redo,|,spellchecker,|,tablecontrols,table,row_props,cell_props,delete_col,delete_row,col_after,col_before,row_after,row_before,split_cells,merge_cells",
			        theme_advanced_buttons2 : "",
			        theme_advanced_buttons3 : "",
			        theme_advanced_toolbar_location : "top",
			        theme_advanced_toolbar_align : "left",
			        theme_advanced_resizing : true,
			
			        // Skin options
			        skin : "o2k7",
			        skin_variant : "silver",
			
			        // CSS in used editor
			        content_css : contentCss,
			
					// Style formats
					style_formats : [
						{ldelim}title : '{$tmce.transitem}', block : 'p', classes : 'tmceItem'{rdelim},
						{ldelim}title : '{$tmce.transdecision}', block : 'p', classes : 'tmceDecision'{rdelim},
					],
				{rdelim});
			{rdelim}
			
			$(function() {ldelim}
{if $tmce.action == 'new'}
				var messageElement = $('<p>').addClass('protocolMessage').text('{lang}editor is shown after preset selection{/lang}');
				var messageSelect = $('<p>').addClass('protocolMessage').text('{lang}preset cannot be changed until saved{/lang}').hide();
				var tmceElement = $("#{$tmce.element}").hide();
				var selectPreset = $("#{$protocolSelectPreset}");
				tmceElement.after(messageElement);
				selectPreset.after(messageSelect);
				selectPreset.change(function(){ldelim}
					var paths = {$protocolPaths};
					var value = selectPreset.val();
					if(value != '') {ldelim}
						initTinyMce(paths[value]);
						tmceElement.slideDown();
						messageElement.slideUp();
						selectPreset.slideUp();
						messageSelect.slideDown();
					{rdelim}
				{rdelim});
{else}
				initTinyMce('{$tmce.css}');
{/if}
			{rdelim});
		</script>
{/if}
		{if isset($head)}{$head}{/if}
	</head>
	<body>
		<div id="navi">
			<div id="logo">
				<p><img src="{$systemLogo}" alt="Logo" title="JudoIntranet" /></p>
			</div>
{if !isset($setupDisabledNavi)}
			{if isset($accordionJs) && $accordionJs}<div id="accordion">{/if}
{$navigation}
			{if isset($accordionJs) && $accordionJs}</div>{/if}
{/if}
		</div>
		<div id="content">
			<div class="headinfo">
{if isset($logininfo)}
				<div class="logininfo">
					{$logininfo}
				</div>
{/if}
{if isset($helpabout)}
				<div class="helpabout">
					{$helpabout}
				</div>
{/if}
			</div>
			{block name="main"}Default Text{/block}
		</div>
		{if isset($helpmessages)}{$helpmessages}{/if}
	</body>
</html>