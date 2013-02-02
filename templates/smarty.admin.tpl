{extends file='smarty.page.tpl'}
{block name=main}
			{if isset($pagename)}<h2>{$pagename}</h2>{/if}
			{if isset($pagecaption)}<h4>{$pagecaption}</h4>{/if}
			{if isset($caption)}<h4>{$caption}</h4>{/if}
			{if isset($tablelinks)}{$tablelinks}{/if}
			{$main}
{/block}