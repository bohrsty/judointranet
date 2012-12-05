<h3>{$inventory}</h3>
<h4>{$date}</h4>
<p><a href="{$back.href}" title="{$back.title}">{$back.content}</a></p>
<h4>{$user}</h4>
{for $i=0 to (count($data)-1)}
<p><b>{$data.$i.name}</b>: {$data.$i.value}</p>
{/for}