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
{if $naviStyle=='default'}
{for $i=0 to (count($data)-1)}
			<div class="navi_{$data.$i.level}{if $data.$i.level!=0}_{if $param==$data.$i.param && $file==$data.$i.file}a{else}i{/if}{/if}">
				<a href="{$data.$i.href}" title="{$data.$i.name}">{$data.$i.name}</a>
			</div>
{/for}
{elseif $naviStyle=='accordion'}
				<h3>
					<a href="{$data.0.href}" title="{$data.0.name}">{$data.0.name}</a>
				</h3>
				<div>
{for $i=1 to (count($data)-1)}
					<div class="navi_{$data.$i.level}{if $data.$i.level!=0}_{if $param==$data.$i.param && $file==$data.$i.file}a{else}i{/if}{/if}">
						<a href="{$data.$i.href}" title="{$data.$i.name}">{$data.$i.name}</a>
					</div>
{/for}
				</div>
{/if}