<p>
	<a{if $params!=''} {$params}{/if} href="{$href|escape}" title="{$title}">{$content}</a>
</p>
<div id="tablelinks">
{for $i=0 to (count($data)-1)}
	<p {$class}>
		<a{if $data.$i.params!=''} {$data.$i.params}{/if} href="{$data.$i.href|escape}" title="{$data.$i.title}">{$data.$i.content}</a>
	</p>
{/for}
</div>