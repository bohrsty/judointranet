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
{if isset($error) && $error===true}
	<div class="tributeHistoryEntry">
	 	<div class="divHistorySubject ui-corner-all historyError">
			<span class="historyError">{$errorMessage}</span>
		</div>
		<div class="divHistoryContent">
			&nbsp;
		</div>
	</div>
{else}
{assign var=historyType value=$entry->getType()}
	<div class="tributeHistoryEntry">
	 	<div class="divHistorySubject ui-corner-all ui-state-default">
			<span class="historyDate">{date('d.m.Y H:i', strtotime($entry->getLastModified()))}</span> <span class="historySubject">{$entry->getSubject()}</span> <span class="historyType">{$historyType.name}</span>
		</div>
		<div class="divHistoryContent">
{if $entry->getContent() != ''}
			{$entry->getContent()}
{else}
			&nbsp;
{/if}
		</div>
	</div>
{if isset($isApi) && $isApi === true}
	<script type="text/javascript">$(".divHistorySubject").unbind("click").click(function() { $(this).parent().find(".divHistoryContent").slideToggle(); });</script>
{/if}
{/if}