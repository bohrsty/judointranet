/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

"use strict";

// import required modules
import React, {Component} from 'react';
import {FormControl} from 'react-bootstrap';
import PropTypes from 'prop-types';


/**
 * Component for the textarea field component
 */
class FieldTextarea extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		return (
			<FormControl
				componentClass="textarea"
				{...this.props}
			/>
		);
	}
}


// set prop types
FieldTextarea.propTypes = {
	placeholder: PropTypes.string.isRequired,
	value: PropTypes.string.isRequired,
	onChange: PropTypes.func.isRequired
};


// export
export default FieldTextarea;
