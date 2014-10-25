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

<table id="result.listallResults" class="content">
	<tr>
		<th>
			{$th.desc}
		</th>
		<th class="name">
			{$th.name}
		</th>
		<th class="date">
			{$th.date}
		</th>
		<th class="city">
			{$th.city}
		</th>
		<th class="show">
			{$th.show}
		</th>
{if $loggedin}
		<th class="admin">
			{$th.admin}&nbsp;{if isset($helpListAdmin)}{$helpListAdmin}{/if}
		</th>
{/if}
	</tr>
{for $i=0 to (count($resultList)-1)}
	<tr class="result.listallResults.tr{cycle values=" even, odd"}">
		<td>
			{$resultList.$i.desc}
		</td>
		<td class="name">
			<a href="{$resultList.$i.name.href|escape}" title="{$resultList.$i.name.title}">{$resultList.$i.name.name}</a>
		</td>
		<td>
			{$resultList.$i.date}
		</td>
		<td>
			{$resultList.$i.city}
		</td>
		<td>
{for $j=0 to (count($resultList.$i.show)-1)}
			<a href="{$resultList.$i.show.$j.href|escape}" title="{$resultList.$i.show.$j.title}"><img src="{$resultList.$i.show.$j.src}" alt="{$resultList.$i.show.$j.alt}" class="icon" title="{$resultList.$i.show.$j.alt}" /></a>
{/for}	
		</td>
{if $loggedin}
		<td class="admin">
{for $j=0 to (count($resultList.$i.admin)-1)}
			<a href="{$resultList.$i.admin.$j.href|escape}" title="{$resultList.$i.admin.$j.title}"><img src="{$resultList.$i.admin.$j.src}" alt="{$resultList.$i.admin.$j.alt}" class="icon" title="{$resultList.$i.admin.$j.alt}" /></a>
{/for}
		</td>
{/if}
	</tr>
{/for}	
</table>