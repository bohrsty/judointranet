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
{if !isset($output)}
{$error}
{foreach $holidays as $htmlName => $holiday}
{assign var=name value=$elements[$holiday.htmlName]}
{assign var=date value=$elements[$holiday.htmlDate]}
{assign var=endDate value=$elements[$holiday.htmlEndDate]}
<div class="holiday row{cycle values=", even"}">
	<div class="cell">
		{if $holiday.fix === true}<b>{$holiday.name}</b><br />{else}{$name.element}{/if}{lang}from:{/lang} {$date.element} {lang}to:{/lang} {$endDate.element}
	</div>
	<div class="clear"></div>
</div>
{/foreach}
<div class="row last">
	{$buttonSubmit}
</div>
{else}
<table class="schoolHoliday">
	<tr>
		<th class="name">{lang}name{/lang}</th>
		<th class="date">{lang}start date{/lang}</th>
		<th class="endDate">{lang}end date{/lang}</th>
	</tr>
{foreach $output as $row}
	<tr>
		<td><b>{$row.name}</b></td>
		<td>{$row.date}</td>
		<td>{$row.endDate}</td>
	</tr>
{/foreach}
</table>
{/if}