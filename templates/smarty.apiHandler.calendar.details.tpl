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
 <table>
 	{if count($data.announcement) > 0 || count($data.files) > 0}<tr>
 		<td colspan="4">
 			{if isset($data.announcement.details) && isset($data.announcement.pdf)}<a href="{$data.announcement.details|escape}"><img src="img/ann_details{if $data.announcementDraft}_draft_{$data.locale}{/if}.png" alt="{if $data.announcementDraft}{lang}show announcement draft{/lang}{else}{lang}show announcement{/lang}{/if}" title="{if $data.announcementDraft}{lang}show announcement draft{/lang}{else}{lang}show announcement{/lang}{/if}" /></a>
 			<a href="{$data.announcement.pdf|escape}"><img src="img/ann_pdf{if $data.announcementDraft}_draft_{$data.locale}{/if}.png" alt="{if $data.announcementDraft}{lang}show announcement pdf draft{/lang}{else}{lang}show announcement pdf{/lang}{/if}" title="{if $data.announcementDraft}{lang}show announcement pdf draft{/lang}{else}{lang}show announcement pdf{/lang}{/if}" /></a>{/if}
 			{if isset($data.announcement.result)}<a href="{$data.announcement.result|escape}"><img src="img/result_info.png" alt="{lang}result attached{/lang}" title="{lang}result attached{/lang}" /></a>{/if}
 		</td>
 	</tr>{/if}
 	{if !is_null($data.color)}<tr>
 		<td colspan="4" class="color" style="background-color: {$data.color}; border-color: {$data.color};">&nbsp;</td>
 	</tr>{/if}
 	<tr>
 		<td colspan="4" class="bold">{$data.name}</td>
 	</tr>
 	<tr>
 		{if $data.end != ''}<td class="bold">{lang}start date{/lang}:</td>
 		<td>{date('d.m.Y', $data.start)}</td>{else}<td colspan="2" class="bold">{lang}date{/lang}:</td>{/if}
 		{if $data.end != ''}<td class="bold">{lang}end date{/lang}:</td>
 		<td>{date('d.m.Y', $data.end)}</td>{else}<td colspan="2">{date('d.m.Y', $data.start)}</td>{/if}
 	</tr>
 	<tr>
 		<td class="bold">{lang}city{/lang}:</td>
 		<td style="width: 50%;">{$data.city}</td>
 		<td class="bold">{lang}type{/lang}:</td>
 		<td>{lang}{$data.type}{/lang}</td>
 	</tr>
 	{if $data.content != ''}<tr>
 		<td colspan="4">{$data.content}</td>
 	</tr>{/if}
 	{if count($data.files) > 0}<tr>
 		<td class="bold">{lang}files{/lang}:</td>
 		<td colspan="3">{foreach $data.files as $file}
 			<a href="{$file.href|escape}" title="{lang}download{/lang}">{$file.filename}</a><br />
 		{/foreach}
 		</td>
 	</tr>{/if}
 	<tr>
 		<td colspan="4" class="font8 alignRight"><span class="bold">{lang}modified{/lang}:</span> {date('d.m.Y', $data.lastModified)} {lang}by{/lang} {$data.modifiedBy} [{$data.id}]</td>
 	</tr>
 </table>