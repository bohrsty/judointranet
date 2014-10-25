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
<table class="content">
	<tr>
		<th>{lang}navigation entry{/lang}</th>
		<th><img src="img/visible.png" alt="{lang}visible{/lang}" title="{lang}visible{/lang}">/<img src="img/not_visible.png" alt="{lang}not visible{/lang}" title="{lang}not visible{/lang}"></th>
	</tr>
{foreach $navi as $entry}
{foreach $entry->getSubItems() as $subEntry}
{if !in_array($subEntry->getId(), $entriesNotShown)}
	<tr class="{cycle values="even,odd"}">
		<td class="width400">
			<a href="administration.php?id=user&amp;action={$action|escape}&amp;nid={$subEntry->getId()|escape}" title="{lang}{$subEntry->getName()}{/lang}">{lang}{$entry->getName()}{/lang} &rarr; {lang}{$subEntry->getName()}{/lang}</a>
		</td>
		<td class="center">
			{if $subEntry->getShow()==1}<img src="img/visible.png" alt="{lang}visible{/lang}" title="{lang}visible{/lang}">{else}<img src="img/not_visible.png" alt="{lang}not visible{/lang}" title="{lang}not visible{/lang}">{/if}
		</td>
	</tr>
{/if}
{/foreach}
{/foreach}
</table>