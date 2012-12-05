<p><a{if $link.params!=''} {$link.params}{/if} href="#" title="{$link.title}">{$link.content}</a></p>
<div{if $divparams!=''} {$divparams}{/if}>
	<p>
{for $i=0 to (count($r)-1)}
		<a href="{$r.$i.href|escape}" title="{$r.$i.title}">{$r.$i.content}</a>
{/for}
	</p>
	<p>
{for $i=0 to (count($dl)-1)}
		<a href="{$dl.$i.href|escape}" title="{$dl.$i.title}">{$dl.$i.content}</a>
{/for}
	</p>
	<p>
{for $i=0 to (count($gl)-1)}
		<a href="{$gl.$i.href|escape}" title="{$gl.$i.title}">{$gl.$i.content}</a>
{/for}
	</p>
</div>