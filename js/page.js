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
 ************************************************************* 
 
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
 ***************************************************

/**
 * hideJsdiffTextareas() hides the "value" textareas by hiding their parents
 *
 * @return void
 */
function hideJsdiffTextareas() {

	// style parent element of the hidden jsdiff textareas
	$("#protDiffBase").parent().css("display", "none");
	$("#protDiffNew").parent().css("display", "none");
}