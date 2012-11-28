<div id="confirm">
	<p>{$message}</p>
	{if $form!=''}<p>{$form}<span{if $spanparams!=''} {$spanparams}{/if}><a{if $link.params!=''} {$link.params}{/if} href="{$link.href|escape}" title="{$link.title}">{$link.content}</a></span></p>{/if}
</div>