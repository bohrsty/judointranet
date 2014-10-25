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
		<th>{lang}edit group{/lang}</th>
		<th>{lang}delete group{/lang}</th>
	</tr>
{foreach $groups as $gid => $group}
{if $gid != 1}
	<tr class="{cycle values="even,odd"}">
		<td class="width400">
			<a href="administration.php?id=user&amp;action={$action|escape}&amp;subaction=groupedit&amp;gid={$gid|escape}" title="{$group->getName()} {lang}edit{/lang}">{$group->getName()}</a>
		</td>
		<td>
			{if !$group->getUsed()}<a href="administration.php?id=user&amp;action={$action|escape}&amp;subaction=groupdelete&amp;gid={$gid|escape}" title="{$group->getName()} {lang}delete{/lang}"><img src="img/group_delete.png" alt="{$group->getName()} {lang}delete{/lang}"/></a>{/if}
		</td>
	</tr>
{/if}
{/foreach}
</table>