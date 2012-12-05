<div id="inventory.detail">
	<h3>{$caption}</h3>
	<p><b>{$accessorylist}:</b></p>
	<p>{$accessories}</p>
	<table>
{for $i=0 to (count($data)-1)}
		<tr class="inventory.movements.tr {cycle values='even,odd'}">
			<td><a href="{$data.$i.href|escape}" title="{$data.$i.title}">{$data.$i.content}</a><td>
			<td>{$data.$i.name}<td>
		</tr>
{/for}
	</table>
</div>