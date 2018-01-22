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
	FormGroup,
	Col,
	ControlLabel,
	FormControl,
	Well,
	Row,
	Panel
} from 'react-bootstrap';
import FieldText from './Field/FieldText';
import FieldTextarea from './Field/FieldTextarea';
import FieldDatepicker from './Field/FieldDatepicker';
import FieldSelect from './Field/FieldSelect';
import FieldAttachment from './Field/FieldAttachment';
import FieldCheckbox from './Field/FieldCheckbox';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';

/**
 * Component for the field component
 */
@provideTranslations
class Field extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
        
        // set translation
        this.t = this.props.t;
        
        // prepare value
        if(this.props.data.formControl == 'FieldCheckbox') {
            var value = false;
        } else {
            var value = '';
        }
		
		// set initial state
		this.state = {
			valid: null,
			value: value,
			validationMessages: {
				required: '',
				date: ''
			}
		};
	}
	
	
	/**
     * componentWillReceiveProps(newProps)
     * executed directly before component receives new props
     * 
     * @param newProps the new props
     */
	componentWillReceiveProps(newProps) {
        
        // check value
	    if(this.props.data.value !== newProps.data.value) {
	        this.updateState('value', newProps.data.value);
	    }
    }
	
	
	/**
	 * updates given parts of the state
	 * 
	 * @param state the state name to be updated
	 * @param value the value for state
	 */
	updateState(state, value) {
		
		var currentState = this.state;
		
		// check if state exists
		currentState[state] = value;
		this.setState(currentState);
	}
	
	
	/**
	 * getValidationState(type)
	 * returns the validation state for the field
	 * 
	 * @param string type the type of the requested data (bsStyle|validationState|validationMessage)
	 */
	getValidationState(type = 'validationState') {
		if(this.state.valid === undefined || this.state.valid === null) {
			return undefined;
		}
		if(type == 'bsStyle') {
			return this.state.valid === false ? 'danger' : 'success';
		} else if(type == 'validationMessage') {
			return this.state.valid;
		}
		return this.state.valid === false ? 'error' : 'success';
	}
	
	
	/**
	 * getFormControl()
	 * checks the type of the field and returns the according form control
	 */
	getFormControl() {
		
		// simplify prop
		var data = this.props.data;
		
		// check type
		switch(data.formControl) {
			
			case 'FieldText':
				return (
					<FieldText
						placeholder={data.placeholder}
						value={this.state.value}
						onChange={this.handleValue.bind(this)}
					/>
				);
			
			case 'FieldTextarea':
				return (
					<FieldTextarea
						placeholder={data.placeholder}
						value={this.state.value}
						onChange={this.handleValue.bind(this)}
					/>
				);
			
			case 'FieldDatepicker':

				return (
					<FieldDatepicker
						value={this.state.value}
						onChange={this.handleDate.bind(this)}
					/>
				);
			
			case 'FieldSelect':
				return (
					<FieldSelect
						value={this.state.value}
						onChange={this.handleValue.bind(this)}
						options={data.options}
					/>
				);
            
            case 'FieldAttachment':
                return (
                    <FieldAttachment
                        value={this.state.value}
                        onChange={this.handleAttachment.bind(this)}
                        url={data.url}
                    />
                );
            
            case 'FieldCheckbox':

                return (
                    <FieldCheckbox
                        value={this.state.value}
                        onChange={this.handleCheckbox.bind(this)}
                    />
                );
		}
	}
	
	
	/**
	 * getValidationMessage()
	 * checks the validation message state and returns them
	 */
	getValidationMessage() {
		
		// get messages from state
		var messages = this.state.validationMessages;
		
		// prepare return
		var output = [];
		
		for(var key in messages) {
			if(messages[key] != '') {
				output.push(<p className="noMargin" key={key}>{messages[key]}</p>);
			}
		}
		
		return output;
	}
	
	
	/**
	 * getData()
	 * returns an object containing the validation state and the value of the field
	 */
	getData() {
		
		// validate data
		this.validate(null, true);
		
		return {
			valid: this.state.valid,
			value: this.state.value
		};
	}
	
	
	/**
	 * validate(value, onSubmit)
	 * validates the given value against the validation rules and updates the states
	 * 
	 * @param string value the value to validate
	 * @param bool onSubmit validates with this.state.value if true, with value if false
	 */
	validate(value, onSubmit = false) {
		
		// simplify validation rules
		var validate = this.props.data.validate
		
		// check onSubmit
		if(onSubmit === true) {
			
			// set value
			value = this.state.value;
		} else {
			
			// update value
			this.updateState('value', value);
		}
		
		// check no validation
		if(validate.length == 0) {
			this.updateState('valid', null);
		} else {
		
			// split and walk through rules
			var valid = true;
			var validationMessages = {
				required: '',
				date: ''
			};
			for(var rule of validate) {
				
				switch(rule) {
					
					case 'required':
						if(value == '' || value === false) {
							valid = valid && false;
							validationMessages.required = this.t('Field.validation.required');
						} else {
							validationMessages.required = '';
						}
						break;
					
					case 'date':
						if(Object.prototype.toString.call(value) != '[object Object]') {
							
						    // check empty value if optional
						    if(value == '' && validate.indexOf('required') == -1) {
						        validationMessages.date = '';
						    } else {
    						    valid = valid && false;
    							validationMessages.date = this.t('Field.validation.date');
						    }
						} else {
							validationMessages.date = '';
						}
						break;
				}
			}
			
			this.updateState('valid', valid);
			this.updateState('validationMessages', validationMessages);
		}
	}
	
	
	/**
	 * handleValue(e)
	 * event handler for value
	 * 
	 * @param object e the event object
	 */
	handleValue(e) {
		this.validate(e.target.value);
	}
	
	
	/**
	 * handleDate(date)
	 * event handler for date
	 * 
	 * @param object date momentjs date object
	 */
	handleDate(date) {
		this.validate(date);
	}
    
    
    /**
     * handleAttachment(value)
     * event handler for attachment
     * 
     * @param mixed value the value from attachment
     */
	handleAttachment(value) {
	    this.validate(value);
    }
    
    
    /**
     * handleCheckbox(e)
     * event handler for checked value
     * 
     * @param object e the event object
     */
    handleCheckbox(e) {
        this.validate(e.target.checked);
    }
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// simplify props
		var data = this.props.data;
		
		return (
			<Panel bsStyle={this.getValidationState('bsStyle')}>
				<FormGroup controlId={data.name} validationState={this.getValidationState('validationState')}>
					<Col componentClass={ControlLabel} md={data.labelMd || 2} xs={data.labelXs || 12}>
						{data.label}{data.validate.indexOf('required') == -1 ? '' : '*'}
					</Col>
					<Col md={data.controlMd || 10} xs={data.controlXs || 12}>
						{this.getFormControl()}
						<FormControl.Feedback />
					</Col>
				</FormGroup>
				{this.getValidationState('validationMessage') === true || this.getValidationState('validationMessage') === undefined ? '' : <Row><Col mdOffset={data.controlMdOffset || 2} md={data.controlMd || 10} xs={data.controlXs || 12}><Well bsSize="small" className="bg-error border-error text-error">{this.getValidationMessage()}</Well></Col></Row>}
			</Panel>
		);
	}
}


// set props types
Field.propTypes = {
	data: PropTypes.object.isRequired
};


// export
export default Field;
