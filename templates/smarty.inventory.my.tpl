<table id="inventory.my">
	<tr>
		<th class="{$th.name.class}">{$th.name.content}</th>
		<th class="{$th.number.class}">{$th.number.content}</th>
		{if $loggedin}<th class="{$th.admin.class}">{$th.admin.content}</th>{/if}
	</tr>
{for $i=0 to (count($data)-1)}
	<tr class="inventory.my.tr {cycle values='even,odd'}">
		<td><a href="{$data.$i.name.href|escape}" title="{$data.$i.name.title}">{$data.$i.name.content}</a></td>
		<td>{$data.$i.number}</td>
		{if $loggedin}<td><a href="{$data.$i.admin.href|escape}" title="{$data.$i.admin.title}">{$data.$i.admin.content}</a></td>{/if}
	</tr>
{/for}
</table>