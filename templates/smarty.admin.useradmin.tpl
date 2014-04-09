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
<div id="tabs">
	<ul>
{for $i=0 to (count($data)-1)}
		<li><a href="#tab-{$i}" title="{$data.$i.tab}">{$data.$i.tab}</a></li>
{/for}
	</ul>
{for $i=0 to (count($data)-1)}
	<div id="tab-{$i}">
		<p>{$data.$i.caption}</p>
		{if isset($data.$i.action) && $data.$i.action != ''}<p><a href="administration.php?id=user&amp;action={$data.$i.action|escape}" title="{$backName}">{$backName}</a></p>{/if}
		{$data.$i.content}
		{if isset($data.$i.action) && $data.$i.action != ''}<p><a href="administration.php?id=user&amp;action={$data.$i.action|escape}" title="{$backName}">{$backName}</a></p>{/if}
	</div>
{/for}
</div>