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
import Select from 'react-select';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';


/**
 * Component for the attachment field component
 */
@provideTranslations
class FieldAttachment extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
        
        // set translation
        this.t = this.props.t;
	}
    
    
    /**
     * loadOptions()
     * retrieves the data from api
     * 
     * @return Promise the promise from the fetch call
     */
    loadOptions() {
        
    	// fetch the options from url
    	return fetch(this.props.url)
        .then((response) => {
        	return response.json();
        }).then((json) => {
    		return { options: json };
        });
    }
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		return (
			<Select.Async
				name="select-attachment"
				value={this.props.value}
				onChange={this.props.onChange.bind(this)}
				loadOptions={this.loadOptions.bind(this)}
				isLoading={true}
			    multi={true}
			    placeholder={this.t('FieldAttachment.placeholder')}
			/>
		);
	}
}


// set prop types
FieldAttachment.propTypes = {
	value: PropTypes.oneOfType([
        PropTypes.number,
        PropTypes.string,
        PropTypes.array
    ]).isRequired,
	onChange: PropTypes.func.isRequired,
	url: PropTypes.string.isRequired
};


// export
export default FieldAttachment;
