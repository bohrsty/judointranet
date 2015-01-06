/* ********************************************************************************************
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
 * ********************************************************************************************/
 
/*************************************************************
 * callback functions for clientside validation (zebra_form) *
 *************************************************************/ 
 
/**
 * callbackCheckSelect(args) checks if a value other than '' is selected
 * 
 * @param array args arguments to check
 * @return bool true, if args is not empty, false otherwise
 */
function callbackCheckSelect(args) {
	
	// check values
	if(args == '') {
		return false;
	}
	return true;
}




/***************************************************
 * functions to style some elements via javascript *
 ***************************************************/

/**
 * hideJsdiffTextareas() hides the "value" textareas by hiding their parents
 *
 * @return void
 */
function hideJsdiffTextareas() {

	// style parent element of the hidden jsdiff textareas
	$("#protDiffBase").parent().css("display", "none");
	$("#protDiffBase").attr("disabled", true);
	$("#protDiffNew").parent().css("display", "none");
	$("#protDiffNew").attr("disabled", true);
}


/**
 * elementsOneRow(firstSelector, secondSelector, dummySelector, display) moves the second 
 * select element into the row of the first and changes the position
 *
 * @param string firstSelector DOM selector for the first select element
 * @param string secondSelector DOM selector for the second select element
 * @param string dummySelector DOM selector for the hidden dummy element to keep odd/even view
 * @param string display CSS value for the display attribute (block or inline)
 */
function elementsOneRow(firstSelector, secondSelector, dummySelector, display) {
	
	// prepare elements
	var first = $('#' + firstSelector);
	var second = $('#' + secondSelector);
	var dummy = $('#' + dummySelector);
	// get note (may be undefined)
	var note = $('#note' + ucfirst(secondSelector));

	// get parent of second
	var secondParent = second.parent();
	// remove second
	second.remove();
	// append to parent of the first
	first.parent().append(second);
	// add note if exist
	if(typeof(note) !== 'undefined') {
		first.parent().append(note);
	}
	// remove parent of second
	secondParent.remove();
	
	// style elements to be block
	first.css('display', display);
	second.css('display', display);
	
	// hide dummy select to correct odd/even look
	dummy.parent().css("display", "none");
	dummy.attr('disabled', true);
}


/**
 * attachFileDialog(id, value) attaches dialog functionality to zebra_form row elements
 * by using the labels
 * 
 * @param int id the id of the actual $.each() element
 * @param string value the value of the actual $.each() element
 */
function attachFileDialog(id, value) {
	
	// get parent
	var element = $('#'+value).parent();
	element.hide();
}




/***********************************************************************
 * functions to implement the hierselect functionality for zebra_forms *
 ***********************************************************************/
 
 /**
  * zebraHierselect(select1Selector, select1Array, select2Selector, select2Array)
  * generates the hierselect functionality in zebra_forms
  *
  * @param string select1Selector DOM selector for the first select element
  * @param string select2Selector DOM selector for the second select element
  * @param array select2Array array containing the values and texts for the second select element in relation to the first value
  * @param int value of the second select element to be selected
  */
function zebraHierselect(select1Selector, select2Selector, select2Array, select2Value) {

	// get value of the first select element
	var value = $(select1Selector).val();

	// save first option and reset value
	var optionFirst = $(select2Selector).find('option:first');
	optionFirst.attr('value', '');
	// remove all options
	$(select2Selector).children().remove();
	// read first option
	$(select2Selector).append(optionFirst);
	
	// walk through array[value] and append options if value != ''
	if(value != '') {
		$.each(select2Array[value], function(newValue, name) {
			$(select2Selector).append(
				'<option value="' + newValue + '">' + name +'</option>'
			);
		});
	} else {
		$(select2Selector).append(optionFirst);
	}
	
	// get option to be selected
	var optionSelected = $(select2Selector).find('[value="' + select2Value + '"]');
	if(select2Value != 0) {
		optionSelected.attr('selected', true);
	}
}



/*********************
 * various functions *
 *********************/
/**
 * ucfirst(string) turns the first character of string into upper case
 *
 * @param string string string thats first character should be upper case
 * @return string the modified string
 */
function ucfirst(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}