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
<p><span{if $link.params!=''} {$link.params}{/if} title="{$link.title}">{$link.content}</span>{if isset($link.help) and $link.help!=''}&nbsp;{$link.help}{/if}</p>
<div id="filterDialog" title="{$dialogTitle}">
	<div class="resetFilter">
		<p class="filterEntry center">{$resetFilter}</p>
{for $i=0 to (count($r)-1)}
		<p class="filterEntry">
			<a href="{$r.$i.href|escape}" title="{$r.$i.title}">{$r.$i.content}</a>
		</p>
{/for}
	</div>
	<div id="filterTabs">
		<ul>
			<li><a href="#filterTabs-1" title="{$chooseDate}">{$dateFilter}</a></li>
			<li><a href="#filterTabs-2" title="{$chooseGroup}">{$groupFilter}</a></li>
		</ul>
		<div id="filterTabs-1">
			
			<p class="filterEntry center">{$chooseDate}</p>
{for $i=0 to (count($dl)-1)}
			<p class="filterEntry">
				<a href="{$dl.$i.href|escape}" title="{$dl.$i.title}">{$dl.$i.content}</a>
			</p>
{/for}
		</div>
		<div id="filterTabs-2">
			<p class="filterEntry center">{$chooseGroup}</p>
{for $i=0 to (count($gl)-1)}
			<p class="filterEntry">
				<a href="{$gl.$i.href|escape}" title="{$gl.$i.title}">{$gl.$i.content}</a>
			</p>
{/for}
		</div>
	</div>
</div>