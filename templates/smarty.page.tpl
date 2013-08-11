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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>{$title}</title>
		<link rel="stylesheet" type="text/css" href="css/start/jquery-ui-1.10.3.custom.css" />
		<link rel="stylesheet" type="text/css" href="css/page.css" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.ui.datepicker-de.js"></script>
{if $hierselect}
		<script type="text/javascript" src="js/quickform.js"></script>
		<script type="text/javascript" src="js/quickform-hierselect.js"></script>
{/if}
{if isset($tinymce) and $tinymce}
		<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
{/if}
{if isset($jsdifflib) and $jsdifflib}
		<script type="text/javascript" src="js/difflib.js"></script>
		<script type="text/javascript" src="js/diffview.js"></script>
{/if}
		<script type="text/javascript">
			{literal}$(document).ready(function(){
				$(function($) {
					$( '.{/literal}{$help.buttonClass}{literal}' ).each(function() {
						$.data(this, 'dialog',
							$(this).next('.{/literal}{$help.dialogClass}{literal}').dialog({
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
								closeText: '{/literal}{$help.closeText}{literal}'
							})
						);
					}).click(function() {
						var isOpen = $.data( this, 'dialog' ).dialog( 'isOpen' );
						if(isOpen) {
							$.data( this, 'dialog' ).dialog( 'moveToTop' );
						} else {
							$.data( this, 'dialog' ).dialog( 'open' );
						}
					});
				});
			});{/literal}
		</script>
{if $jquery}
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
				<p><img src="img/logo.png" alt="Logo" title="JudoIntranet" /></p>
			</div>
{for $i=0 to (count($data)-1)}
			<div class="navi_{$data.$i.level}{if $data.$i.level!=0}_{if $active==$data.$i.id && $file==$data.$i.file}a{else}i{/if}{/if}">
				<a href="{$data.$i.href}" title="{$data.$i.title}">{$data.$i.content}</a>
			</div>
{/for}
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
	</body>
</html>