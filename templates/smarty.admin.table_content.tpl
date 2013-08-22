{* ********************************************************************************************
 * Copyright (c) 2011 Nils Bohrs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 * 
 * Thirdparty licenses see LICENSE
 * 
 * ********************************************************************************************}
<p id="pagelinks">
	{$pages}&nbsp;{for $i=0 to (count($pl)-1)}<a{if $pl.$i.params!=''} {$pl.$i.params}{/if} href="{$pl.$i.href|escape}" title="{$pl.$i.title}">{$pl.$i.content}</a>&nbsp;{/for}&nbsp;{$toof}&nbsp;<a{if $newlink.params!=''} {$newlink.params}{/if} href="{$newlink.href|escape}" title="{$newlink.title}">{$newlink.content}</a>
</p>
<table class="content adminTableContent">
	<tr>
{for $i=0 to (count($data.0.th)-1)}
		<th>{$data.0.th.$i.content}</th>
{/for}
{if count($data) >= 1}
{for $j=0 to (count($data)-1)}
	<tr class="{cycle values="odd,even"}">
		<td class="adminTasks">
			<a href="{$data.$j.td.0.edit.href|escape}" title="{$data.$j.td.0.edit.title}"><img src="{$data.$j.td.0.edit.src}" alt="{$data.$j.td.0.edit.alt}"></a><a href="{$data.$j.td.0.disenable.href|escape}" title="{$data.$j.td.0.disenable.title}"><img src="{$data.$j.td.0.disenable.src}" alt="{$data.$j.td.0.disenable.alt}"></a><a href="{$data.$j.td.0.delete.href|escape}" title="{$data.$j.td.0.delete.title}"><img src="{$data.$j.td.0.delete.src}" alt="{$data.$j.td.0.delete.alt}"></a>	
		</td>
{for $k=1 to (count($data.$j.td)-1)}
		<td>{if $data.$j.td.$k.escape==true}{$data.$j.td.$k.content|escape:'htmlall'|nl2br}{else}{$data.$j.td.$k.content}{/if}</td>
{/for}
	</tr>
{/for}
{/if}
</table>
<p id="pagelinks">
	{$pages}&nbsp;{for $i=0 to (count($pl)-1)}<a{if $pl.$i.params!=''} {$pl.$i.params}{/if} href="{$pl.$i.href|escape}" title="{$pl.$i.title}">{$pl.$i.content}</a>&nbsp;{/for}&nbsp;{$toof}&nbsp;<a{if $newlink.params!=''} {$newlink.params}{/if} href="{$newlink.href|escape}" title="{$newlink.title}">{$newlink.content}</a>
</p>