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
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.ui.datepicker-de.js"></script>
{if isset($zebraform) and $zebraform}
		<link rel="stylesheet" type="text/css" href="css/zebra_form/zebra_form.css" />
		<script type="text/javascript" src="js/zebra_form.js"></script>
{/if}
{if isset($tinymce) and $tinymce}
		<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
{/if}
{if isset($jsdifflib) and $jsdifflib}
		<script type="text/javascript" src="js/difflib.js"></script>
		<script type="text/javascript" src="js/diffview.js"></script>
{/if}
		<script type="text/javascript" src="js/page.js"></script>
		<link rel="stylesheet" type="text/css" href="css/start/jquery-ui-1.10.3.custom.min.css" />
		<link rel="stylesheet" type="text/css" href="css/page.css" />
		<script type="text/javascript">
{literal}
			$(function() {
				$({/literal}{$helpids}{literal}).each(function() {
					var i = this;
					$( '#{/literal}{$help.dialogClass}{literal}-'+i ).dialog({
						autoOpen: false,
						show: {
							effect: '{/literal}{$help.effect}{literal}',
							duration: {/literal}{$help.effectDuration}{literal}
						},
						position: { 
							my: 'right center', 
							at: 'right center', 
							of: window
						},
						closeText: '{/literal}{$help.closeText}{literal}',
						minWidth: 600,
						minHeight: 250,
						maxHeight: 600
					});
					$( '#{/literal}{$help.buttonClass}{literal}-'+i ).click(function() {
						$( "#{/literal}{$help.dialogClass}{literal}-"+i ).dialog( "open" );
						var isOpen = $( "#{/literal}{$help.dialogClass}{literal}-"+i ).dialog( 'isOpen' );
						if(isOpen) {
							$( "#{/literal}{$help.dialogClass}{literal}-"+i ).dialog( 'moveToTop' );
						} else {
							$( "#{/literal}{$help.dialogClass}{literal}-"+i ).dialog( 'open' );
						}
					});
				})
			});
{/literal}
{if isset($permissionJs) && $permissionJs}
{literal}
			function clearRadio(radioName) {
				$('[name=' + radioName + ']').prop('checked', false)
			}
			function selectRadio(radioId) {
				$('[id=' + radioId + ']').prop('checked', true)
			}
{/literal}
{/if}
{if isset($tabsJs) && $tabsJs}
{literal}
			$(function() {
				$( "#tabs" ).tabs();
			});
{/literal}
{/if}
		</script>
{if $manualjquery!=''}
		<script type="text/javascript">
			$(document).ready(function(){ldelim}
				// jQuery functions go here...
				{$manualjquery}
			{rdelim});
		</script>
{/if}
{if isset($tinymce) and $tinymce}
		<script type="text/javascript">
			tinyMCE.init({ldelim}
		        // General options
		        mode : "exact",
		        elements : "{$tmce.element}",
		        theme : "advanced",
		        plugins : "spellchecker,pagebreak,style",
		        language : "de",
		
		        // Theme options
		        theme_advanced_buttons1 : "bold,|,styleselect,|,undo,redo,|,spellchecker",
		        theme_advanced_buttons2 : "",
		        theme_advanced_buttons3 : "",
		        theme_advanced_toolbar_location : "top",
		        theme_advanced_toolbar_align : "left",
		        theme_advanced_resizing : true,
		
		        // Skin options
		        skin : "o2k7",
		        skin_variant : "silver",
		
		        // CSS in used editor
		        content_css : "{$tmce.css}",
		
				// Style formats
				style_formats : [
					{ldelim}title : '{$tmce.transitem}', block : 'p', classes : 'tmceItem'{rdelim},
					{ldelim}title : '{$tmce.transdecision}', block : 'p', classes : 'tmceDecision'{rdelim},
				],
		{rdelim});
		</script>
{/if}
		{$head}
	</head>
	<body>
		<div id="navi">
			<div id="logo">
				<p><img src="{$systemLogo}" alt="Logo" title="JudoIntranet" /></p>
			</div>
{$navigation}
		</div>
		<div id="content">
			<div class="headinfo">
				<div class="logininfo">
					{$logininfo}
				</div>
				<div class="helpabout">
					{$helpabout}
				</div>
			</div>
			{block name="main"}Default Text{/block}
		</div>
		{$helpmessages}
	</body>
</html>