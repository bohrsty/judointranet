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
<div id="tabs">
	<ul>
		<li><a href="#tab-1" title="{$tabElements}">{$tabElements}</a></li>
		<li><a href="#tab-2" title="{$tabPermissions}">{$tabPermissions}</a></li>
	</ul>
	<div id="tab-1">
{foreach $elements as $element}
		<div class="row{cycle values=", even"}">
{if isset($element.label) && $element.label!=''}
			{$element.label}
{/if}
{if isset($element.element) && $element.element!=''}
			{$element.element}
{/if}
{if isset($element.note) && $element.note!=''}
			{$element.note}
{/if}
		</div>
{/foreach}
	</div>
	<div id="tab-2">
{foreach $permissions as $permissionName => $permission}
		<div class="row{cycle values=", even"}">
{if isset($permission.element) && $permission.element!=''}
			{$iconRead.$permissionName}{$permission.element.r}&nbsp;{$iconEdit.$permissionName}{$permission.element.w}
{/if}
{if isset($permission.label) && $permission.label!=''}
			{$permission.label}
{/if}
{if isset($permission.note) && $permission.note!=''}
			{$permission.note}
{/if}
		</div>
{/foreach}
	</div>
</div>
<div class="row last">
	{$buttonSubmit}
</div>