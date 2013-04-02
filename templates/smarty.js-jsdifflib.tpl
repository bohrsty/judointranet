
				var base = difflib.stringAsLines($("#{$dl.protDiffBase}").val());
				var newtxt = difflib.stringAsLines($("#{$dl.protDiffNew}").val());
				var sm = new difflib.SequenceMatcher(base, newtxt);
				var opcodes = sm.get_opcodes();
				var diffoutputdiv = $("#{$dl.protDiffOut}");
				while (diffoutputdiv.firstChild) diffoutputdiv.removeChild(diffoutputdiv.firstChild);
				diffoutputdiv.append(diffview.buildView({ldelim}
				    baseTextLines: base,
				    newTextLines: newtxt,
				    opcodes: opcodes,
				    baseTextName: "{$dl.protDiffBaseCaption}",
				    newTextName: "{$dl.protDiffNewCaption}",
				    contextSize: "none"
				{rdelim}));