{for $i=0 to (count($data)-1)}
<table id="protocol_showdecisions">
	<tr class="head">
		<td class="date">
			<b>{$data.$i.date}</b>
		</td>
		<td class="type">
			<b>{$data.$i.type}</b>
		</td>
		<td>
			<b>{$data.$i.location}</b>
		</td>
	</tr>
{for $j=0 to count($data.$i.decisions) -1}
	<tr class="decision{cycle values=" even, odd"}">
		<td colspan="3">
			{$data.$i.decisions.$j}	
		</td>
	</tr>
{/for}
</table>
{/for}