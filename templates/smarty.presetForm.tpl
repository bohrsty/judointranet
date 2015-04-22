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
<span class="spanLink"><img id="pi{$id}" src="img/show_presetform.png" alt="{lang}show preset selection{/lang}" title="{lang}show preset selection{/lang}" /></span>
<p id="p{$id}" class="presetForm Zebra_Form">
	<select id="select_{$id}" class="control validate[required]">
		<option value="">{lang}- choose -{/lang}</option>{foreach $options as $key => $value}<option value="{$key}">{$value}</option>{/foreach}
	</select>
	<input id="input_{$id}" class="submit" type="submit" value="{lang}+{/lang}"></input>
	<script type="text/javascript">
		$("#p{$id}").hide();
		$("#pi{$id}").click(function() {ldelim}
			var positions = $("#pi{$id}").position();
			$("#p{$id}").css({ldelim}
				top: positions.top,
				left: positions.left-$("#p{$id}").width(),
				position: "absolute"
			{rdelim});
			$("#p{$id}").fadeToggle();
		{rdelim});
		$(document).click(function(event) {ldelim}
			if($(event.target).closest("#p{$id}").length == 0 && $(event.target).closest("#pi{$id}").length == 0) {ldelim}
				if($("#p{$id}").is(":visible")) {ldelim}
					$("#p{$id}").fadeToggle();
				{rdelim}
			{rdelim}
		{rdelim});
		$("#input_{$id}").click(function() {ldelim}
			var value = $("#select_{$id}").val();
			if(value == "") {ldelim}
				$("#select_{$id}").validationEngine("showPrompt", "{lang}* Please select an option{/lang}", "error", "topLeft", true);
			{rdelim} else {ldelim}
				$.ajax({ldelim}
					url: "{$url}",
					data: {ldelim}cid: "{$id}", pid: value{rdelim},
					cache: false
				{rdelim})
				.done(function(data) {ldelim}
					var response = $.parseJSON(data);
					if(response.result == "OK") {ldelim}
						$("div.jTable").jtable("reload");
					{rdelim} else {ldelim}
						$("#select_{$id}").validationEngine("showPrompt", response.message, "error", "topLeft", true);
					{rdelim}
				{rdelim});
			{rdelim}
		{rdelim});
	</script>
</p>