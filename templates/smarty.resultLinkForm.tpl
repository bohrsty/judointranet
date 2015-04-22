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
<span class="spanLink"><img id="divi{$id}_{$rid}" src="img/show_linkform.png" alt="{lang}show link selection{/lang}" title="{lang}show link selection{/lang}" /></span>
<div id="div{$id}_{$rid}" class="linkForm Zebra_Form">
	<select id="select_{$id}_{$rid}" class="control">
		<option value="">{lang}- choose -{/lang}</option>{foreach $options as $key => $value}<option value="{$key}"{if $key==$lcid} selected="selected"{/if}>{$value}</option>{/foreach}
	</select>
</div>
<script type="text/javascript">
	$("#div{$id}_{$rid}").hide();
	$("#divi{$id}_{$rid}").click(function(event) {ldelim}
		var positions = $("#divi{$id}_{$rid}").position();
		$("#div{$id}_{$rid}").css({ldelim}
			top: positions.top,
			left: positions.left-$("#div{$id}_{$rid}").width(),
			position: "absolute"
		{rdelim});
		$("#div{$id}_{$rid}").fadeToggle();
	{rdelim});
	$(document).click(function(event) {ldelim}
		if($(event.target).closest("#div{$id}_{$rid}").length == 0 && $(event.target).closest("#divi{$id}_{$rid}").length == 0) {ldelim}
			if($("#div{$id}_{$rid}").is(":visible")) {ldelim}
				$("#div{$id}_{$rid}").fadeToggle();
			{rdelim}
		{rdelim}
	{rdelim});
	$("#select_{$id}_{$rid}").change(function() {ldelim}
		$.ajax({ldelim}
			method: "POST",
			url: "{$url}",
			data: {ldelim}cid: "{$id}", lcid: $("#select_{$id}_{$rid}").val(){rdelim},
			cache: false
		{rdelim})
		.done(function(data) {ldelim}
			var response = $.parseJSON(data);
			if(response.result == "ERROR") {ldelim}
				$("#select_{$id}_{$rid}").validationEngine("showPrompt", response.message, "error", "topLeft", true);
			{rdelim} else {ldelim}
				$("div.jTable").jtable("reload");
			{rdelim}
		{rdelim});
	{rdelim});
</script>