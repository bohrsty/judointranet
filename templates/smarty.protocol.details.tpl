{if isset($links)}
<div class="protocolDetailLinks">
{if $status}
{for $i=0 to count($links)-1}
	<a href="{$links.$i.href|escape}" title="{$links.$i.title}">{$links.$i.name}</a>
{/for}
{/if}
</div>
{/if}
<div class="protocol-details">
{if isset($data)}
{foreach $data as $entry}
	<p class="details">{$entry}</p>
{/foreach}
{/if}
</div>