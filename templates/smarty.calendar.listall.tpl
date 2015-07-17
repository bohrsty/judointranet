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
<div id="showFilterButton" class="ui-corner-all ui-state-active">
{lang}show filter{/lang}{if isset($helpButton) and $helpButton!=''}&nbsp;{$helpButton}{/if}
</div>
<div id="filterDialog" class="round">
	<div class="filter">
		<p class="filterEntry center nopointer">{lang}select date{/lang} (<img src="img/common_delete.png" alt="{lang}reset date filter{/lang}" title="{lang}reset date filter{/lang}" id="resetDate" class="pointer" />)</p>
		<input type="text" id="dateFrom" class="dateInput" /> - <input type="text" id="dateTo" class="dateInput" />
{foreach $datefilter as $filter}
		<p class="filterEntry dateFilter" title="{$filter.from}-{$filter.to}">{$filter.name}</p>
{/foreach}
	</div>
	<div class="filter">
		<p class="filterEntry center nopointer">{lang}select group{/lang} (<span id="groupAll">{lang}all{/lang}</span> / <span id="groupNone">{lang}none{/lang}</span>)</p>
{foreach $groupfilter as $filter}
		<p class="filterEntry">
			<input type="checkbox" class="groupFilterCheckbox" name="groupfilter[]" id="gcb{$filter.id}" /><span class="groupFilterText" title="{$filter.name}">{$filter.name}</span>
		</p>
{/foreach}
	</div>
</div>
<div id="{$containerId}" class="jTable"></div>