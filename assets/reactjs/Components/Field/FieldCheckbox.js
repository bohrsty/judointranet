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
import {Checkbox} from 'react-bootstrap';
import PropTypes from 'prop-types';


/**
 * Component for the checkbox field component
 */
class FieldCheckbox extends Component {
	
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
			<Checkbox
				{...this.props}
			/>
		);
	}
}


// set prop types
FieldCheckbox.propTypes = {
	value: PropTypes.bool.isRequired,
	onChange: PropTypes.func.isRequired
};


// export
export default FieldCheckbox;
