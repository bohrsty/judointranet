<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>{$title}</title>
{if $jquery}
		<link rel="stylesheet" type="text/css" href="css/start/jquery-ui-1.8.20.custom.css" />
{/if}
		<link rel="stylesheet" type="text/css" href="css/page.css" />
{if $jquery}
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.ui.datepicker-de.js"></script>
{/if}
{if $hierselect}
		<script type="text/javascript" src="js/quickform.js"></script>
		<script type="text/javascript" src="js/quickform-hierselect.js"></script>
{/if}
{if $jquery}
		<script type="text/javascript">
			$(document).ready(function(){ldelim}
				// jQuery functions go here...
				{$manualjquery}
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
			<div class="logininfo">
				{$logininfo}
			</div>
			{block name="main"}Default Text{/block}
		</div>
	</body>
</html>