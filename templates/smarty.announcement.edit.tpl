{for $i=0 to (count($v)-1)}
<p>
	<b>{$v.$i.name}:</b> {$v.$i.value|nl2br}
</p>
{/for}