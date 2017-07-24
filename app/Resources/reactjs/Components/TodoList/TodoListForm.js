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
import moment from 'moment';
import HorizontalForm from '../HorizontalForm';
import PropTypes from 'prop-types';

/**
 * Component for the todo list form component
 */
class TodoListForm extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// set initial state
		this.state = {
			users: [],
			data: {
				name: '',
				text: '',
				due: '',
				assignedTo: '0'
			}
		}
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get translation method
		this.t = this.context.t;
		
		// set form (new|edit)
		this.form = this.props.form;
		
		// set subtitle
		this.props.handleSetSubtitle('TodoListForm.subtitle.' + this.form);
		
		// get users
		this.getAjaxData('users');
		
		// get data if edit
		if(this.form == 'edit') {
			this.getAjaxData('data');
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
	 * getAjaxData(type)
	 * retrieves the data from api according to type
	 * 
	 * @param string type the type of data to get from api
	 */
	getAjaxData(type) {
		
		// TODO: AJAX calls to get the data
		
		// switch type
		switch(type) {
			
			case 'users':
				
				var users = require('../../mockUsers.js');
				var newUsers = [];
				for(var key in users) {
					newUsers.push({
						value: users[key].id,
						name: users[key].name,
						title: users[key].username
					});
				}
				newUsers.unshift({
					value: 0,
					name: this.t('TodoListForm.assignedToMySelf'),
					title: "user1"
				});
				this.updateState('users', newUsers);
				break;
			
			case 'data':
				
				var listItems = require('../../mockTodolist.js');
				var item = {};
				// walk through list to the the current item
				itemBlock: {
					for(var key in listItems) {
						if(listItems[key].id == this.props.match.params.id){
							item = listItems[key];
							break;
						} else {
							if(listItems[key].subitems != undefined) {
								for(var i in listItems[key].subitems) {
									if(listItems[key].subitems[i].id == this.props.match.params.id) {
										item = listItems[key].subitems[i];
										break itemBlock;
									}
								}
							}
						}
					}
				}
				
				// prepare date value (due)
				var due = new moment(item.due, 'DD.MM.YYYY');
				
				// set state
				this.updateState('data', {
					name: item.title,
					text: item.text,
					due: due,
					assignedTo: ''+item.assignedTo.id
				});
				break;
		}
		
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
		this.props.history.push('/todolist/view/'+ this.props.match.params.id);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// get users as options
		var options = this.state.users;
		
		// prepare fields
		var fields = [
			{
				name: 'name',
				formControl: 'FieldText',
				placeholder: this.t('TodoListForm.name'),
				value: this.state.data.name,
				validate: ['required'],
				label: this.t('TodoListForm.name'),
				labelMd: 2,
				labelXs: 12,
				controlMd: 10,
				controlXs: 12,
			},
			{
				name: 'text',
				formControl: 'FieldTextarea',
				placeholder: this.t('TodoListForm.text'),
				value: this.state.data.text,
				validate: ['required'],
				label: this.t('TodoListForm.text'),
				labelMd: 2,
				labelXs: 12,
				controlMd: 10,
				controlXs: 12,
			},
			{
				name: 'due',
				formControl: 'FieldDatepicker',
				placeholder: this.t('TodoListForm.due'),
				value: this.state.data.due,
				validate: ['required','date'],
				label: this.t('TodoListForm.due'),
				labelMd: 2,
				labelXs: 12,
				controlMd: 10,
				controlXs: 12,
			},
			{
				name: 'assignedTo',
				formControl: 'FieldSelect',
				placeholder: this.t('TodoListForm.assignedTo'),
				value: this.state.data.assignedTo,
				validate: [],
				label: this.t('TodoListForm.assignedTo'),
				labelMd: 2,
				labelXs: 12,
				controlMd: 10,
				controlXs: 12,
				options: options
			}
		];
		
		return (
			<HorizontalForm
				onSubmit={this.handleSubmit.bind(this)}
				fields={fields}
				cancelButtonLabel={this.t('TodoListForm.cancel')}
				saveButtonLabel={this.t('TodoListForm.save')}
				buttonMdOffset={2}
				buttonMd={10}
				buttonXs={12}
				history={this.props.history}
			/>
		);
	}
}


// set context types
TodoListForm.contextTypes = {
	t: PropTypes.func.isRequired
};


// export
export default TodoListForm;
