{extends file='smarty.page.tpl'}
{block name=main}
			<h2>{if isset($caption)}{$caption}{/if}</h2>
			{if isset($tablelinks)}{$tablelinks}{/if}
			{$main}
{/block}