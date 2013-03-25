{if isset($decisionlink) and $decisionlink.number > 0}
<div class="protocolDetailLinks"><a href="{$decisionlink.href|escape}" title="{$decisionlink.title} ({$decisionlink.number})">{$decisionlink.text}</a></div>
{/if}
<div class="protocol-page-line">
	<div class="protocol-page">
		{$page}
	</div>
</div>