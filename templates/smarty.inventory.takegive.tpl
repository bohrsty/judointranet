<h2>{$caption}</h2>
{if isset($takefrom)}<p>{$takefrom}</p>{/if}
{if isset($accessoryinfo)}<p>{$accessoryinfo}</p>{/if}
{if $form!=''}
{$form}
{else}
<h3>{$action}</h3>
<p>{$accesoryaction}</p>
{for $i=0 to (count($v)-1)}
<p>
	<b>{$data.$i.name}:</b> {$data.$i.value|nl2br}
</p>
{/for}
{/if}