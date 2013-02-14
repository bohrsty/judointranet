<table id="protocol.listall">
	<tr>
		<th class="date">
			{$th.date}
		</th>
		<th class="type">
			{$th.type}
		</th>
		<th class="location">
			{$th.location}
		</th>
		<th class="show">
			{$th.show}
		</th>
{if $loggedin}
		<th class="admin">
			{$th.admin}
		</th>
{/if}
	</tr>
{for $i=0 to (count($list)-1)}
	<tr class="protocol.listall.tr{cycle values=" even, odd"}">
		<td class="date">
			<a href="{$list.$i.date.href|escape}" title="{$list.$i.date.title}">{$list.$i.date.date}</a>
		</td>
		<td>
			{$list.$i.type}
		</td>
		<td>
			{$list.$i.location}
		</td>
		<td>
			<a href="{$list.$i.show.0.href|escape}" title="{$list.$i.show.0.title}"><img src="{$list.$i.show.0.src}" alt="{$list.$i.show.0.alt}" class="icon" title="{$list.$i.show.0.alt}" /></a>
			<a href="{$list.$i.show.1.href|escape}" title="{$list.$i.show.1.title}"><img src="{$list.$i.show.1.src}" alt="{$list.$i.show.1.alt}" class="icon" title="{$list.$i.show.1.alt}" /></a>	
		</td>
{if $loggedin}
		<td class="admin">
{if $list.$i.admin.0.admin}
			<div class="admin-links">
{if isset($list.$i.admin.0)}
				<a href="{$list.$i.admin.0.href|escape}" title="{$list.$i.admin.0.title}"><img src="{$list.$i.admin.0.src}" alt="{$list.$i.admin.0.alt}" class="icon" title="{$list.$i.admin.0.alt}" /></a>
{/if}
{if isset($list.$i.admin.1)}
				<a href="{$list.$i.admin.1.href|escape}" title="{$list.$i.admin.1.title}"><img src="{$list.$i.admin.1.src}" alt="{$list.$i.admin.1.alt}" class="icon" title="{$list.$i.admin.1.alt}" /></a>
{/if}
{if isset($list.$i.admin.2)}
				<a href="{$list.$i.admin.2.href|escape}" title="{$list.$i.admin.2.title}"><img src="{$list.$i.admin.2.src}" alt="{$list.$i.admin.2.alt}" class="icon" title="{$list.$i.admin.2.alt}" /></a>
{/if}
			</div>
{/if}
		</td>
{/if}
	</tr>
{/for}	
</table>