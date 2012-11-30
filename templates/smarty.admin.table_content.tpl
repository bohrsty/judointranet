<p id="pagelinks">
	{$pages}&nbsp;{for $i=0 to (count($pl)-1)}<a{if $pl.$i.params!=''} {$pl.$i.params}{/if} href="{$pl.$i.href|escape}" title="{$pl.$i.title}">{$pl.$i.content}</a>&nbsp;{/for}&nbsp;{$toof}&nbsp;<a{if $newlink.params!=''} {$newlink.params}{/if} href="{$newlink.href|escape}" title="{$newlink.title}">{$newlink.content}</a>
</p>
<table>
	<tr>
{for $i=0 to (count($data.0.th)-1)}
		<th>{$data.0.th.$i.content}</th>
{/for}
{for $j=0 to (count($data)-1)}
	<tr class="{cycle values="odd,even"}">
		<td>
			<a href="{$data.$j.td.0.edit.href|escape}" title="{$data.$j.td.0.edit.title}"><img src="{$data.$j.td.0.edit.src}" alt="{$data.$j.td.0.edit.alt}"></a><a href="{$data.$j.td.0.disenable.href|escape}" title="{$data.$j.td.0.disenable.title}"><img src="{$data.$j.td.0.disenable.src}" alt="{$data.$j.td.0.disenable.alt}"></a><a href="{$data.$j.td.0.delete.href|escape}" title="{$data.$j.td.0.delete.title}"><img src="{$data.$j.td.0.delete.src}" alt="{$data.$j.td.0.delete.alt}"></a>
		</td>
{for $k=1 to (count($data.$j.td)-1)}
		<td>{$data.$j.td.$k.content|escape:'htmlall'|nl2br}</td>
{/for}
	</tr>
{/for}
</table>