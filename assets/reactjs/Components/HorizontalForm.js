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
import {
	Form,
	FormGroup,
	Col,
	Button
} from 'react-bootstrap';
import Field from './Field';
import AdditionalFieldSelection from './AdditionalFieldSelection';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import provideContext from '../provideContext';


/**
 * Component for the horizontal form component
 */
@provideTranslations
@provideContext
class HorizontalForm extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
        
        // set translation
        this.t = this.props.t;
		
		// prepare field objects
		this.fields = {};
	}
	
	
	/**
	 * addField(field)
	 * adds the field reference to this.fields
	 * 
	 * @param object field the field reference
	 * @param string fieldName the name of the field
	 */
	addField(field, fieldName) {
		this.fields[fieldName] = field;
	}
	
	
	/**
	 * onSubmit(e)
	 * event handler for submitting the form
	 * 
	 * @param object e the event object
	 */
	onSubmit(e) {
		
		// prevent default submission
		e.preventDefault();
		
		// prepare form validation and return data
		var formValid = true;
		var formData = {};
		
		// walk through fields
		for(var key in this.fields) {
			
			// get data
			var fieldData = this.fields[key].getData();
			
			// add field valid state to form validation
			if(fieldData.valid !== null) {
				formValid = formValid && fieldData.valid;
			}
			
			// add to form data
			formData[key] = fieldData.value;
		}
		
		// check if form is valid and call back
		if(formValid === true) {
			this.props.onSubmit(formData);
		} else {
			
			// add error notification
			this.props.addNotification({
				type: 'danger',
				headline: this.t('HorizontalForm.formNotValid.heading'),
				message: this.t('HorizontalForm.formNotValid.message')
			});
		}
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
	    // prepare fields
	    var fields = this.props.fields.map((field, fieldId) => {
	        
	        // check formControl
	        if(field.formControl == 'AdditionalFieldSelection') {
	            return (<AdditionalFieldSelection data={field} key={fieldId} ref={(ref) => this.addField(ref, field.name)} />);
	        } else {
	            return (<Field data={field} key={fieldId} ref={(ref) => this.addField(ref, field.name)} />);
	        }
        });
	        
		return (
			<Form horizontal onSubmit={this.onSubmit.bind(this)}>
				{fields}
				<FormGroup controlId="buttons">
					<Col mdOffset={this.props.buttonMdOffset || 2} md={this.props.buttonMd || 10} xs={this.props.buttonXs || 12}>
						<Button onClick={() => this.props.history.goBack()}>{this.props.cancelButtonLabel}</Button>
						{' '}
						<Button type="submit" bsStyle="primary">{this.props.saveButtonLabel}</Button>
					</Col>
				</FormGroup>
			</Form>
		);
	}
}


// set prop types
HorizontalForm.propTypes = {
	onSubmit: PropTypes.func.isRequired,
	fields: PropTypes.array.isRequired,
	cancelButtonLabel: PropTypes.string.isRequired,
	saveButtonLabel: PropTypes.string.isRequired,
	history: PropTypes.object.isRequired
};


// export
export default HorizontalForm;
