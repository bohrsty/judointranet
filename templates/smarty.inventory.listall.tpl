<table id="inventory.listall">
	<tr>
{for $i=0 to (count($th)-1)}
		<th class="{$th.$i.class}">{$th.$i.content}</th>
{/for}
	</tr>
{for $j=0 to (count($data)-1)}
	<tr class="inventory.listall.tr {cycle values='even,odd'}">
		<td><a href="{$data.$j.name.href|escape}" title="{$data.$j.name.title}">{$data.$j.name.content}</a></td>
		<td>{$data.$j.number}</td>
		<td>{$data.$j.owner}</td>
		<td>{$data.$j.status}</td>
	</tr>
{/for}
</table>