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
<div id="newHistoryEntryButton" class="ui-corner-all ui-state-active">
{lang}Add new history entry{/lang}
</div>
<div id="newHistoryEntryForm" class="Zebra_Form">
	<div class="row">
		{lang}Subject{/lang}: <input type="text" class="control text" name="historySubject" id="historySubject" />
		{lang}Type{/lang}: <select name="historyType" class="control" id="historyType">
			<option value="">{lang}- choose -{/lang}</option>
{foreach $typeOptions as $option}
			<option value="{$option.id}">{$option.name}</option>
{/foreach}
		</select>
	</div>
	<div class="row">
		{lang}Content{/lang}: <textarea name="historyContent" class="control" id="historyContent"></textarea>
	</div>
	<div class="row last">
		<input type="button" class="submit" name="historySubmit" id="historySubmit" value="{lang}save entry{/lang}" />
	</div>
</div>
<div id="tributeHistoryEntries">
{foreach $tributeHistory as $entry}
{include file="smarty.tributeHistoryEntry.tpl" entry=$entry}
{/foreach}
</div>