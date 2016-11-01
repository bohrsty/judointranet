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
import {OverlayTrigger, Popover, ButtonGroup, Button, FormControl, FormGroup} from 'react-bootstrap';
import {provideTranslations} from 'react-translate-maker';
import FontAwesome from 'react-fontawesome';


/**
 * Component for an editable popover (editable on double click)
 */
@provideTranslations
export default class EditablePopover extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// get translation method
		this.t = this.props.t;
		
		// set initial state
		this.state = {
			edit: false,
			content: this.props.content,
			open: false
	    };
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
		if(this.state[state] != undefined) {
			currentState[state] = value;
			this.setState(currentState);
		}
	}
	
	
	/**
	 * handleEdit()
	 * eventhandler to handle double click for edit mode
	 */
	handleEdit() {
		
		this.updateState('edit', true);
	}
	
	
	/**
	 * handleSave()
	 * eventhandler to handle save the edited text
	 */
	handleSave() {
		
		this.updateState('edit', false);
		
		this.props.handleSave(this.props.id, this.state.content);
	}
	
	
	/**
	 * eventhandler for cancelling edit
	 */
	/**
	 * handleCancel()
	 * eventhandler to cancel the edit mode
	 */
	handleCancel() {
		
		this.updateState('edit', false);
		this.updateState('content', this.props.content);
	}
	
	
	/**
	 * handleClickTrigger(e)
	 * eventhandler to handle the click on trigger component and prevent default action
	 * 
	 * @param object e event object
	 */
	handleClickTrigger(e) {
		
		e.preventDefault();
	}
	
	
	/**
	 * handleChangeText(e)
	 * eventhandler to handle the text change in edit mode 
	 * 
	 * @param object e event object
	 */
	handleChangeText(e) {
		
		this.updateState('content', e.target.value);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// clone and add handler (click)
		var newTrigger = React.cloneElement(this.props.trigger, {onClick: this.handleClickTrigger.bind(this)});
		
		// prepare editor
		var editor = (
			<FormGroup>
				<FormControl componentClass="textarea" defaultValue={this.props.content} onChange={this.handleChangeText.bind(this)} />
				<ButtonGroup className="pull-right"><Button onClick={this.handleCancel.bind(this)} bsStyle="default">{this.t('EditablePopover.cancel')}</Button><Button className="pull-right" onClick={this.handleSave.bind(this)} bsStyle="primary">{this.t('EditablePopover.save')}</Button></ButtonGroup>
			</FormGroup>
		);
		
		// prepare content
		var commentContent = (
			<span title={this.state.content == '' ? this.props.emptyTitle : this.props.title}>
				{this.state.content != '' ? this.state.content : this.props.emptyContent}
			</span>
		);
		
		// prepare popover
		var popover = (
			<Popover id={this.props.id} className="notSelectableText" onDoubleClick={this.handleEdit.bind(this)}>
				{this.state.edit ? editor : commentContent}
			</Popover>
		);
		
		return (
			<OverlayTrigger trigger="click" rootClose placement="right" overlay={popover}>
				{newTrigger}
			</OverlayTrigger>
		);
	}


}