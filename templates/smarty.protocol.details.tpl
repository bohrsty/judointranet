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
{if isset($links)}
<div class="detailLinks">
{if $status}
{for $i=0 to count($links)-1}
	<a href="{$links.$i.href|escape}" title="{$links.$i.title}">{$links.$i.name}</a>
{/for}
{/if}
</div>
{/if}
<div class="protocol-details">
{if isset($data)}
{foreach $data as $entry}
	<p class="details">{$entry}</p>
{/foreach}
	<p class="details">{Object::lang('class.ProtocolView#details#text#attached')}</p>
{if isset($files) && count($files) > 0}
	<ul>
{foreach $files as $file}
	<li>{$file->getName()} - <a href="{$fileHref}{$file->getId()}" title="{$file->getFilename()}">{$file->getFilename()}</a> ({$file->getFileTypeAs('name')})</li>
{/foreach}
	</ul>
{else}
<p>{Object::lang('class.ProtocolView#details#text#none')}</p>
{/if}
{/if}
</div>