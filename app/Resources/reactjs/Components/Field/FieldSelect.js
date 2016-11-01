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


/**
 * Component for the select field component
 */
class FieldSelect extends Component {
	
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
				componentClass="select"
				value={this.props.value}
				onChange={this.props.onChange.bind(this)}
			>
			{
				this.props.options.map((option, optionId) =>
					<option value={option.value} title={option.title} key={optionId}>{option.name}</option>
				)
			}
			</FormControl>
		);
	}
}


// set prop types
FieldSelect.propTypes = {
	value: React.PropTypes.string.isRequired,
	onChange: React.PropTypes.func.isRequired,
	options: React.PropTypes.array.isRequired
};


// export
export default FieldSelect;
