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
 <table class="content width100">
 	<tr>
 {foreach $ths as $th}
 		<th>{$th}</th>
 {/foreach}
 	</tr>
 {foreach $data as $row}
 	<tr class="resultTaskList{cycle values=" even, odd"}">
 		<td class="center"><a href="{$row.state.href|escape}"><img src="img/tasks_{$row.state.src}.png" alt="{$row.state.alt}" title="{$row.state.title}" /></a></td>
 		<td>{$row.name}</td>
 		<td>{$row.date}</td>
 		<td>{$row.desc}</td>
 		<td title="{$row.last_modified.title}">{$row.last_modified.text}</td>
 		<td>{foreach $row.actions as $action}<a href="{$action.href|escape}"><img src="img/tasks_pdf.png" alt="{$action.alt}" title="{$action.title}" /></a>{/foreach}</td>
 	</tr>
 {/foreach}
 </table>