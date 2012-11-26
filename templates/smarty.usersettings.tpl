<div id="usersettings">
{for $i=0 to (count($us)-1)}
	<p class="usersettings">
		<a{if $us.$i.params!=''} {$us.$i.params}{/if} href="{$us.$i.href|escape}" title="{$us.$i.title}">{$us.$i.content}</a>
	</p>
{/for}
</div>