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
<div class="ui-corner-all ui-state-active" id="showUpload">{lang}Attach files{/lang}</div>
<div id="uploadForm">
	<div id="uploadFile">{lang}Browse...{/lang}</div>
	<div id="uploadButton" class="row last">
		{lang}File type{/lang}: <select id="fileType" class="control validate[required]">
			<option value="">{lang}- choose -{/lang}</option>
{foreach $fileTypes as $fileType}
			<option value="{$fileType.id}">{$fileType.name}</option>
{/foreach}
		</select>&nbsp;
		<input type="submit" id="upload" class="submit" value="{lang}Upload{/lang}" />
	</div>
</div>