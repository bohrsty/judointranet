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
<div class="ui-corner-all ui-state-active" id="showTributeFilter">{lang}Filter tributes{/lang}</div>
<div id="tributeFilter" class="Zebra_Form">
	<p>
		<span class="spanLink" id="reset">{lang}Show all{/lang}</span>
	</p>
	<p>
		{lang}Filter tributes by{/lang}:<br />
		<select name="{$yearId}" id="{$yearId}" class="tributeFilter">
			<option value="">- {lang}select year{/lang} -</option>
{foreach Tribute::getAllYears() as $year}
			<option value="{$year}">{$year}</option>'
{/foreach}
		</select>
		<select name="{$categoryId}" id="{$categoryId}" class="tributeFilter">
			<option value="">- {lang}select testimonial category{/lang} -</option>
{foreach Tribute::getAllTestimonialCategories() as $category}
			<option value="{$category.id}">{$category.name}</option>
{/foreach}
		</select>
		<select name="{$testimonialId}" id="{$testimonialId}" class="tributeFilter">
			<option value="">- {lang}select testimonial{/lang} -</option>
{foreach Tribute::getAllTestimonials() as $testimonial}
			<option value="{$testimonial.id}">{$testimonial.name}</option>
{/foreach}
		</select>
		<select name="{$stateId}" id="{$stateId}" class="tributeFilter">
			<option value="">- {lang}select state{/lang} -</option>
{foreach Tribute::getAllStates() as $state}
			<option value="{$state.id}">{$state.name}</option>
{/foreach}
		</select>
		<select name="{$clubId}" id="{$clubId}" class="tributeFilter">
			<option value="">- {lang}select club{/lang} -</option>
{foreach Tribute::readClubs() as $id => $club}
			<option value="{$id}">{$club.name}</option>
{/foreach}
		</select>&nbsp;
		<input type="radio" name="bool" value="and" checked="checked" /> {lang}AND{/lang}&nbsp;
		<input type="radio" name="bool" value="or" /> {lang}OR{/lang}
	</p>
	<p>
		{lang}Fulltext search{/lang} ({lang}name and club{/lang}):<br />
		<input type="text" name="{$searchId}" id="{$searchId}" />
	</p>
</div>
<div id="{$containerId}" class="jTable"></div>