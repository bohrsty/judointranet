<table id="calendar.listall">
	<tr>
		<th class="date">
			{$th.date}
		</th>
		<th class="name">
			{$th.name}
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
	<tr class="calendar.listall.tr{cycle values=" even, odd"}">
		<td class="date">
			{$list.$i.date}
		</td>
		<td>
			<a href="{$list.$i.name.href|escape}" title="{$list.$i.name.title}">{$list.$i.name.name}</a>
		</td>
		<td>
{if $list.$i.show.0.show}
			<a href="{$list.$i.show.0.href|escape}" title="{$list.$i.show.0.title}"><img src="{$list.$i.show.0.src}" alt="{$list.$i.show.0.alt}" class="icon" title="{$list.$i.show.0.alt}" /></a>
			<a href="{$list.$i.show.1.href|escape}" title="{$list.$i.show.1.title}"><img src="{$list.$i.show.1.src}" alt="{$list.$i.show.1.alt}" class="icon" title="{$list.$i.show.1.alt}" /></a>
{/if}			
		</td>
		<td class="admin">
{if $list.$i.admin.0.admin}
			<div class="admin-links">
				<a href="{$list.$i.admin.0.href|escape}" title="{$list.$i.admin.0.title}"><img src="{$list.$i.admin.0.src}" alt="{$list.$i.admin.0.alt}" class="icon" title="{$list.$i.admin.0.alt}" /></a>
				<a href="{$list.$i.admin.1.href|escape}" title="{$list.$i.admin.1.title}"><img src="{$list.$i.admin.1.src}" alt="{$list.$i.admin.1.alt}" class="icon" title="{$list.$i.admin.1.alt}" /></a>
			</div>
{if $list.$i.annadmin.0.preset==0}
			{$form}
{else}
			<div class="admin-links">
				<a href="{$list.$i.annadmin.0.href|escape}" title="{$list.$i.annadmin.0.title}"><img src="{$list.$i.annadmin.0.src}" alt="{$list.$i.annadmin.0.alt}" class="icon" title="{$list.$i.annadmin.0.alt}" /></a>
				<a href="{$list.$i.annadmin.1.href|escape}" title="{$list.$i.annadmin.1.title}"><img src="{$list.$i.annadmin.1.src}" alt="{$list.$i.annadmin.1.alt}" class="icon" title="{$list.$i.annadmin.1.alt}" /></a>
			</div>
{/if}
{/if}
		</td>
	</tr>
{/for}	
</table>