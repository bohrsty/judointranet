{if isset($decisionlink) and $decisionlink.number > 0}
<p><a href="{$decisionlink.href}" title="{$decisionlink.title} ({$decisionlink.number})">{$decisionlink.text}</a></p>
{/if}
<div class="protocol-page-line">
	<div class="protocol-page">
		{$page}
	</div>
</div>