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
		<th>{lang}edit user{/lang}</th>
		<th>{lang}delete user{/lang}</th>
	</tr>
{foreach $users as $user}
{if $user->get_id() != 1}
	<tr class="{cycle values="even,odd"}">
		<td class="width400">
			<a href="administration.php?id=user&amp;action={$action|escape}&amp;subaction=useredit&amp;uid={$user->get_id()|escape}" title="{$user->get_userinfo('name')} {lang}edit{/lang}">{$user->get_userinfo('name')}</a>
		</td>
		<td>
			{if !$user->getUsed()}<a href="administration.php?id=user&amp;action={$action|escape}&amp;subaction=userdelete&amp;uid={$user->get_id()|escape}" title="{$user->get_userinfo('name')} {lang}delete{/lang}"><img src="img/user_delete.png" alt="{$user->get_userinfo('name')} {lang}delete{/lang}"/></a>{/if}
		</td>
	</tr>
{/if}
{/foreach}
</table>