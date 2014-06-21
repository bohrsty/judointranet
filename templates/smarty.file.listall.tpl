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
		<li><a href="#tab-1" title="{$tabDownload}">{$tabDownload}</a></li>
		<li><a href="#tab-2" title="{$tabCached}">{$tabCached}</a></li>
	</ul>
	<div id="tab-1">
		<table id="file.listallFiles" class="content">
			<tr>
				<th class="name">
					{$th.name}
				</th>
				<th class="filetype">
					{$th.filetype}
				</th>
				<th class="filename">
					{$th.filename}
				</th>
				<th class="show">
					{$th.show}
				</th>
		{if $loggedin}
				<th class="admin">
					{$th.admin}&nbsp;{if isset($helpListAdmin)}{$helpListAdmin}{/if}
				</th>
		{/if}
			</tr>
		{for $i=0 to (count($fileList)-1)}
			<tr class="file.listallFiles.tr{cycle values=" even, odd"}">
				<td class="name">
					<a href="{$fileList.$i.name.href|escape}" title="{$fileList.$i.name.title}">{$fileList.$i.name.name}</a>
				</td>
				<td>
					{$fileList.$i.filetype}
				</td>
				<td title="{$fileList.$i.filename.title}">
					{$fileList.$i.filename.name}
				</td>
				<td>
					<a href="{$fileList.$i.show.0.href|escape}" title="{$fileList.$i.show.0.title}"><img src="{$fileList.$i.show.0.src}" alt="{$fileList.$i.show.0.alt}" class="icon" title="{$fileList.$i.show.0.alt}" /></a>	
				</td>
		{if $loggedin}
				<td class="admin">
		{if isset($fileList.$i.admin.0)}
						<a href="{$fileList.$i.admin.0.href|escape}" title="{$fileList.$i.admin.0.title}"><img src="{$fileList.$i.admin.0.src}" alt="{$fileList.$i.admin.0.alt}" class="icon" title="{$fileList.$i.admin.0.alt}" /></a>
		{/if}
		{if isset($fileList.$i.admin.1)}
						<a href="{$fileList.$i.admin.1.href|escape}" title="{$fileList.$i.admin.1.title}"><img src="{$fileList.$i.admin.1.src}" alt="{$fileList.$i.admin.1.alt}" class="icon" title="{$fileList.$i.admin.1.alt}" /></a>
		{/if}
		{if isset($fileList.$i.admin.2)}
						<a href="{$fileList.$i.admin.2.href|escape}" title="{$fileList.$i.admin.2.title}"><img src="{$fileList.$i.admin.2.src}" alt="{$fileList.$i.admin.2.alt}" class="icon" title="{$fileList.$i.admin.2.alt}" /></a>
		{/if}
				</td>
		{/if}
			</tr>
		{/for}	
		</table>
	</div>
	<div id="tab-2">
		{foreach $cachedList as $table => $entry}

		<h4>{$cachedList.$table.name}</h4>
		<table id="file.listallCached" class="content">
			<tr>
				<th class="name">
					{$th.name}
				</th>
				<th class="filetype">
					{$th.filetype}
				</th>
				<th class="filename">
					{$th.filename}
				</th>
				<th class="show">
					{$th.show}
				</th>
			</tr>
			{for $i=0 to (count($entry)-2)}
			<tr class="file.listallCached.tr{cycle values=" even, odd"}">
				<td class="name">
					<a href="{$entry.$i.name.href|escape}" title="{$entry.$i.name.title}">{$entry.$i.name.name}</a>
				</td>
				<td>
					{$entry.$i.filetype}
				</td>
				<td title="{$entry.$i.filename.title}">
					{$entry.$i.filename.name}
				</td>
				<td>
					<a href="{$entry.$i.show.0.href|escape}" title="{$entry.$i.show.0.title}"><img src="{$entry.$i.show.0.src}" alt="{$entry.$i.show.0.alt}" class="icon" title="{$entry.$i.show.0.alt}" /></a>	
				</td>
			</tr>
			{/for}
		</table>
		{/foreach}
	</div>
</div>