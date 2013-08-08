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
{if isset($list)}
<p><b>{$caption}</b></p>
{for $i=0 to count($list)-1}
<p><a href="{$list.$i.href|escape}" title="{$list.$i.title}">{$list.$i.text}</a>{if $list.$i.img != false}&nbsp;<img src="{$list.$i.img.src}" alt="{$list.$i.img.alt}" title="{$list.$i.img.title}" />{/if}</p>
{/for}
{/if}
{if isset($c) and $c==true}
<div id="{$diffOut}"></div>
{$form}
{/if}
{if isset($message)}
<p>{$message.message}</p>
<p><a href="{$message.href|escape}" title="{$message.title}">{$message.text}</a></p>
{/if}