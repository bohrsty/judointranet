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
<p><a{if $link.params!=''} {$link.params}{/if} href="#" title="{$link.title}">{$link.content}</a></p>
<div{if $divparams!=''} {$divparams}{/if}>
	<p>
{for $i=0 to (count($r)-1)}
		<a href="{$r.$i.href|escape}" title="{$r.$i.title}">{$r.$i.content}</a>
{/for}
	</p>
	<p>
{for $i=0 to (count($dl)-1)}
		<a href="{$dl.$i.href|escape}" title="{$dl.$i.title}">{$dl.$i.content}</a>
{/for}
	</p>
	<p>
{for $i=0 to (count($gl)-1)}
		<a href="{$gl.$i.href|escape}" title="{$gl.$i.title}">{$gl.$i.content}</a>
{/for}
	</p>
</div>