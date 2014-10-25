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
<script type="text/javascript">
	$(function(){ldelim}
		$("#imported_results").hide()
		$("#toggleImportedResults").click(function(){ldelim}
			$("#imported_results").slideToggle();
		{rdelim});
	{rdelim});
</script>
<div class="message messageInfo">
	<p><b>{$filename}</b> {lang}imported successful{/lang}</p>
</div>
<p id="toggleImportedResults" title="{lang}toggle imported results{/lang}">{lang}toggle imported results{/lang}</p>
<div id="imported_results">
{foreach $result->getAgegroups() as $agegroup => $countAgegroups}
	<div class="result_agegroup">
		<p class="agegroup">{$agegroup}</p>
{foreach $result->getWeightclasses($agegroup) as $weightclass => $countWeightclasses}
		<div class="result_weightclass">
			<p class="weightclass">{$weightclass} kg</p>
			<table class="content">
{foreach $result->getStandings($agegroup, $weightclass) as $standing}
				<tr class="result_tr{cycle values=" odd, even"}">
					<td class="result_place">{$standing.place}. Platz</td>
					<td class="result_name">{$standing.name}</td>
					<td class="result_club">{$standing.club_name}</td>
				</tr>
{/foreach}
			</table>
		</div>
{/foreach}
	</div> 
{/foreach}
</div>