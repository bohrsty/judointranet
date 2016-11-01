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
import Datetime from 'react-datetime';


/**
 * Component for the datepicker field component
 */
class FieldDatepicker extends Component {
	
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
			<Datetime
				timeFormat={false}
				closeOnSelect
				{...this.props}
			/>
		);
	}
}


// set prop types
FieldDatepicker.propTypes = {
	value: React.PropTypes.oneOfType([
		React.PropTypes.object,
		React.PropTypes.string
	]).isRequired,
	onChange: React.PropTypes.func.isRequired
};


// export
export default FieldDatepicker;
