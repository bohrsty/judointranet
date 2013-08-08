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
<table id="protocol.listall">
	<tr>
		<th class="date">
			{$th.date}
		</th>
		<th class="type">
			{$th.type}
		</th>
		<th class="location">
			{$th.location}
		</th>
		<th class="show">
			{$th.show}
		</th>
{if $loggedin}
		<th class="admin">
			{$th.admin}
		</th>
{/if}
	</tr>
{for $i=0 to (count($list)-1)}
	<tr class="protocol.listall.tr{cycle values=" even, odd"}">
		<td class="date">
			<a href="{$list.$i.date.href|escape}" title="{$list.$i.date.title}">{$list.$i.date.date}</a>
		</td>
		<td>
			{$list.$i.type}
		</td>
		<td>
			{$list.$i.location}
		</td>
		<td>
			<a href="{$list.$i.show.0.href|escape}" title="{$list.$i.show.0.title}"><img src="{$list.$i.show.0.src}" alt="{$list.$i.show.0.alt}" class="icon" title="{$list.$i.show.0.alt}" /></a>
			<a href="{$list.$i.show.1.href|escape}" title="{$list.$i.show.1.title}"><img src="{$list.$i.show.1.src}" alt="{$list.$i.show.1.alt}" class="icon" title="{$list.$i.show.1.alt}" /></a>	
		</td>
{if $loggedin}
		<td class="admin">
{if isset($list.$i.admin.0)}
				<a href="{$list.$i.admin.0.href|escape}" title="{$list.$i.admin.0.title}"><img src="{$list.$i.admin.0.src}" alt="{$list.$i.admin.0.alt}" class="icon" title="{$list.$i.admin.0.alt}" /></a>
{/if}
{if isset($list.$i.admin.1)}
				<a href="{$list.$i.admin.1.href|escape}" title="{$list.$i.admin.1.title}"><img src="{$list.$i.admin.1.src}" alt="{$list.$i.admin.1.alt}" class="icon" title="{$list.$i.admin.1.alt}" /></a>
{/if}
{if isset($list.$i.admin.2)}
				<a href="{$list.$i.admin.2.href|escape}" title="{$list.$i.admin.2.title}"><img src="{$list.$i.admin.2.src}" alt="{$list.$i.admin.2.alt}" class="icon" title="{$list.$i.admin.2.alt}" /></a>
{/if}
		</td>
{/if}
	</tr>
{/for}	
</table>