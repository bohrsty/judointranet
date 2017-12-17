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
import {Panel} from 'react-bootstrap';
import moment from 'moment';
import HorizontalForm from '../HorizontalForm';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import provideContext from '../../provideContext';

/**
 * Component for the todo list form component
 */
@provideTranslations
@provideContext
class CalendarEdit extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
        
        // set translation
        this.t = this.props.t;
		
		// set initial state
		this.state = {
			data: {}
		}
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get data if edit
		if(this.props.match.params.id != 0) {
			this.getAjaxData();
		} else {
			this.updateState('data', {
				event: '',
				location: '',
				begin: '',
				end: '',
				isExternal: false
			});
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
		
		currentState[state] = value;
		this.setState(currentState);
	}
	
	
	/**
	 * getAjaxData()
	 * retrieves the data from api according to type
	 */
	getAjaxData() {
		
		// TODO: AJAX calls to get the data
        
        // show loading modal
        this.props.startLoading('CalendarEdit.getAjaxData');
				
		var listItems = require('../../mockCalendar.js');
		var item = {};
		// walk through list to the the current item
		for(var key in listItems) {
			if(listItems[key].id == this.props.match.params.id){
				item = listItems[key];
				break;
			}
		}
        
        // simulate ajax call and remove loading modal
        setTimeout(() => {
            // set subtitle
            this.props.handleSetSubtitle('CalendarEdit.subtitle', {subject: item.event});
            // update list
            this.updateState('data', item);
            // hide loading modal
            this.props.stopLoading('CalendarEdit.getAjaxData')
        }, 1000);
		
	}
	
	
	/**
	 * handleSubmit(form)
	 * eventhandler to save the changes and return to list
	 * 
	 * @param object form the form data
	 */
	handleSubmit(form) {
		
		// save todo
		console.dir(form);
		// return to list
		this.props.history.push('/calendar/view/'+ this.props.match.params.id);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// prepare fields
		var fields = [
			{
				name: 'event',
				formControl: 'FieldText',
				placeholder: this.t('CalendarEdit.event'),
				value: this.state.data.event,
				validate: ['required'],
				label: this.t('CalendarEdit.event'),
				labelMd: 2,
				labelXs: 12,
				controlMd: 10,
				controlXs: 12,
			},
			{
				name: 'location',
				formControl: 'FieldText',
				placeholder: this.t('CalendarEdit.location'),
				value: this.state.data.location,
				validate: [],
				label: this.t('CalendarEdit.location'),
				labelMd: 2,
				labelXs: 12,
				controlMd: 10,
				controlXs: 12,
			},
			{
				name: 'begin',
				formControl: 'FieldDatepicker',
				placeholder: this.t('CalendarEdit.begin'),
				value: this.state.data.begin,
				validate: ['required','date'],
				label: this.t('CalendarEdit.begin'),
				labelMd: 2,
				labelXs: 12,
				controlMd: 10,
				controlXs: 12,
			},
			{
				name: 'end',
				formControl: 'FieldDatepicker',
				placeholder: this.t('CalendarEdit.end'),
				value: this.state.data.end,
				validate: ['date'],
				label: this.t('CalendarEdit.end'),
				labelMd: 2,
				labelXs: 12,
				controlMd: 10,
				controlXs: 12,
			},
			{
				name: 'isExternal',
				formControl: 'FieldCheckbox',
				value: this.state.data.isExternal,
				validate: [],
				label: this.t('CalendarEdit.isExternal'),
				labelMd: 2,
				labelXs: 12,
				controlMd: 10,
				controlXs: 12
			},
            {
                name: 'template',
                formControl: 'AdditionalFieldSelection',
                value: this.state.data.template,
                label: this.t('CalendarEdit.announcementTemplate'),
                labelMd: 2,
                labelXs: 12,
                controlMd: 10,
                controlXs: 12
            }
		];
		
		return (
			<Panel header={this.t('CalendarEdit.appointment')}>
				<HorizontalForm
					onSubmit={this.handleSubmit.bind(this)}
					fields={fields}
					cancelButtonLabel={this.t('CalendarEdit.cancel')}
					saveButtonLabel={this.t('CalendarEdit.save')}
					buttonMdOffset={2}
					buttonMd={10}
					buttonXs={12}
					history={this.props.history}
				/>
			</Panel>
		);
	}
}


// export
export default CalendarEdit;
