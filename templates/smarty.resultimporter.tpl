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
{$error}
<div class="row">
{if isset($elements.preset.label) && $elements.preset.label!=''}
	{$elements.preset.label}
{/if}
{if isset($elements.preset.element) && $elements.preset.element!=''}
	{$elements.preset.element}
{/if}
{if isset($elements.preset.note) && $elements.preset.note!=''}
	{$elements.preset.note}
{/if}
</div>
<table class="content">
	<tr>
		<th>{lang}result import name{/lang}</th>
		<th>{lang}('result import club orig{/lang}</th>
		<th>{lang}('result import club correct{/lang}</th>
		<th>{lang}('result import agegroup{/lang}</th>
		<th>{lang}('result import weightclass{/lang}</th>
		<th>{lang}('result import place{/lang}</th>
	</tr>
{for $i=0 to count($data)-1}
	<tr class="resultImportCorrect{cycle values=" even, odd"}">
		<td>{$data.$i.name}</td>
		<td>{$data.$i.club}</td>
{assign var=clubid value='club_'|cat:$i}
		<td>{$elements.$clubid.element}</td>
		<td>{$data.$i.agegroup}</td>
		<td>{$data.$i.weightclass} kg</td>
		<td class="center">{$data.$i.place}</td>
	</tr>
{/for}
</table>
<div class="row last">
	{$buttonSubmit}
</div>