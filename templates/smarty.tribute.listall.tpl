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
<p>
	<span class="Zebra_Form">
		<span class="spanLink" id="reset">{lang}Show all{/lang}</span>&nbsp;
		<select name="{$yearId}" id="{$yearId}">
			<option value="">- {lang}select year{/lang} -</option>
{foreach Tribute::getAllYears() as $year}
			<option value="{$year}">{$year}</option>'
{/foreach}
		</select>
		<select name="{$testimonialId}" id="{$testimonialId}">
			<option value="">- {lang}select testimonial{/lang} -</option>
{foreach Tribute::getAllTestimonials() as $testimonial}
			<option value="{$testimonial.id}">{$testimonial.name}</option>
{/foreach}
		</select>
		<select name="{$stateId}" id="{$stateId}">
			<option value="">- {lang}select state{/lang} -</option>
{foreach Tribute::getAllStates() as $state}
			<option value="{$state.id}">{$state.name}</option>
{/foreach}
		</select>
		<select name="{$clubId}" id="{$clubId}">
			<option value="">- {lang}select club{/lang} -</option>
{foreach Page::readClubs(true) as $id => $club}
			<option value="{$id}">{$club.name}</option>
{/foreach}
		</select>
		<input type="text" name="{$searchId}" id="{$searchId}" />
	</span>
</p>
<div id="{$containerId}" class="jTable"></div>