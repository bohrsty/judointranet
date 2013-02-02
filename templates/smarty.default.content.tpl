<h3>{$caption}</h3>
{for $i=0 to (count($text)-1)}
<h4>{$text.$i.caption}</h4>
<p>{$text.$i.text}</p>
{/for}