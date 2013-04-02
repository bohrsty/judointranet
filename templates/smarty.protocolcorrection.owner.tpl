{if isset($list)}
<p><b>{$caption}</b></p>
{for $i=0 to count($list)-1}
<p><a href="{$list.$i.href|escape}" title="{$list.$i.title}">{$list.$i.text}</a>{if $list.$i.img != false}&nbsp;<img src="{$list.$i.img.src}" alt="{$list.$i.img.alt}" title="{$list.$i.img.title}" />{/if}</p>
{/for}
{/if}
{if isset($c) and $c==true}
<div id="{$diffOut}"></div>
{$form}
{/if}
{if isset($message)}
<p>{$message.message}</p>
<p><a href="{$message.href|escape}" title="{$message.title}">{$message.text}</a></p>
{/if}