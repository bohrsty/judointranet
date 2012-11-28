				$( "#{$elementid}" ).datepicker();
				$( "#{$elementid}" ).datepicker( "option", $.datepicker.regional[ "de" ] );
				$( "#{$elementid}" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
				{if $dateFormat!='' && $dateValue!=''}$( "#{$elementid}" ).datepicker( "setDate", $.datepicker.parseDate("{$dateFormat}", "{$dateValue}") );{/if}