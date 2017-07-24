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
    ButtonToolbar,
    ButtonGroup,
    Button,
    InputGroup,
    FormControl,
    SplitButton,
    MenuItem
} from 'react-bootstrap';
import {LinkContainer} from 'react-router-bootstrap';
import FontAwesome from 'react-fontawesome';
import PropTypes from 'prop-types';


/**
 * Component for the toolbar component
 */
class Toolbar extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// set initial state
		this.state = {};
	}
	
	
	/**
	 * updateState(state, value, subState)
	 * updates given parts of the state
	 * 
	 * @param state the state name to be updated
	 * @param value the value for state
	 * @param subState the second level name to be updated
	 */
	updateState(state, value, subState) {
		
		var currentState = this.state;

		// check if state exists
		currentState[state][subState] = value;
		this.setState(currentState);
	}
	
	
	/**
	 * getSearch()
	 * checks if search is enabled, configures it and returns the jsx
	 */
	getSearch() {
		
		// check if enabled
		if(this.props.config.search == undefined || this.props.config.search === false) {
			return '';
		} else {
			
			// check config object
			if(Object.prototype.toString.call(this.props.config.search) == '[object Function]') {
				
				return (
					<ToolbarSearch
						onChange={this.props.config.search.bind(this)}
						bsSize={this.props.config.bsSize}
					/>
				);
			} else {
				return '';
			}
		}
	}
	
	
	/**
	 * method to render the component
	 */
	/*
	 * config: {
	 * 		bsSize: 'small',
	 * 		groups: [
	 * 			{
	 * 				buttons: [
	 * 					{
	 * 						type: 'link|callback',
	 * 						pathname: '/...',
	 * 						onClick: func,
	 * 						bsStyle: 'default',
	 * 						icon: 'plus',
	 * 						iconIsPrefix: true,
	 * 						text: 'button text'
	 * 					}
	 * 				]
	 * 			}
	 * 		]
	 * }
	 */
	render() {
	
		return (
			<ButtonToolbar>
				{this.props.config.groups.map((group, groupId) =>
					<ToolbarButtonGroup
						key={groupId}
						bsSize={this.props.config.bsSize}
						buttons={group.buttons}
					/>
				)}
				{this.getSearch()}
			</ButtonToolbar>
		);
	}
}


// export
export default Toolbar;




/**
 * Component for the toolbar button group component
 */
class ToolbarButtonGroup extends Component {
	
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
			
			<ButtonGroup bsSize={this.props.bsSize.length ? this.props.bsSize : 'default'}>
				{this.props.buttons.map((buttonObject, buttonId) => 
					<ToolbarButton
						key={buttonId}
					    id={buttonId}
						bsStyle={buttonObject.bsStyle}
						type={buttonObject.type}
						callback={buttonObject.type == 'callback' ? buttonObject.onClick.bind(this) : undefined}
						icon={buttonObject.icon}
						iconIsPrefix={buttonObject.iconIsPrefix}
						text={buttonObject.text}
						pathname={buttonObject.pathname}
						disabled={buttonObject.disabled}
					    dropdown={buttonObject.dropdown}
					/>
				)}
			</ButtonGroup>
		);
	}
}




/**
 * Component for the toolbar button component
 */
class ToolbarButton extends Component {
	
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
		
		// check icon
		if(this.props.icon.length && this.props.icon != '') {
			if(this.props.iconIsPrefix) {
				var buttonText = (
					<span><FontAwesome name={this.props.icon} />{' '}{this.props.text}</span>
				);
			} else {
				var buttonText = (
					<span>{this.props.text}{' '}<FontAwesome name={this.props.icon} /></span>
				);
			}
		}
		
		// check if dropdown
		var completeButton = null;
		if(this.props.type == 'dropdown' && this.props.dropdown !== undefined && this.props.dropdown.length > 0) {
		    
		    completeButton = (
	            <SplitButton
	                id={this.props.id}
	                bsStyle={this.props.bsStyle}
	                title={buttonText}
	            >
	                {this.props.dropdown.map((item, id) => {
	                    
	                    // prepare menu item
	                    var button = (
	                        <MenuItem
	                            key={id}
	                            onClick={item.type == 'callback' ? item.callback.bind(this) : undefined}
	                            disabled={item.disabled}
	                        >
	                            {item.text}
	                        </MenuItem>
	                    );
	                    
	                    // prepare link
	                    var link = (
	                        <LinkContainer key={id} to={{pathname: item.pathname}}>
	                            {button}
	                        </LinkContainer>
	                    );
	                    
	                    // return
	                    return item.type == 'link' ? link : button;
	                })}
	            </SplitButton>
		    );
		} else {
		
    		// prepare button
    		var button = (
    			<Button
    				bsStyle={this.props.bsStyle}
    				onClick={this.props.type == 'callback' ? this.props.callback.bind(this) : undefined}
    				disabled={this.props.disabled}
    			>
    				{buttonText}
    			</Button>
    		);
    		
    		// prepare link
    		var link = (
				<LinkContainer to={{pathname: this.props.pathname}}>
					{button}
				</LinkContainer>
			);
    		
    		// prepare return
    		completeButton = this.props.type == 'link' ? link : button;
		}
		
		return (
			
			completeButton
		);
	}
}


/**
 * Component for the toolbar search component
 */
class ToolbarSearch extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// set initial state
		this.state = {
			value: ''
		};
	}
	
	
	/**
	 * updateState(state, value)
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
	 * handleOnChange(e)
	 * eventhandler for the change event
	 * 
	 * @param object e the event object
	 */
	handleOnChange(e) {
		
		// get value
		var value = e.target.value;
		
		// update state
		this.updateState('value', value);
		
		// handle search
		this.props.onChange(value);
	}
	
	
	/**
	 * handleOnClear()
	 * eventhandler for the clear button click event
	 */
	handleOnClear() {
		
		// update state
		this.updateState('value', '');
		
		// handle search
		this.props.onChange('');
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
	
		return (
			<ButtonGroup bsSize={this.props.bsSize.length ? this.props.bsSize : 'default'}>
				<InputGroup>
					<InputGroup.Addon>
						<FontAwesome name="search" />
					</InputGroup.Addon>
					<FormControl
						type="text"
						placeholder={this.context.t('ToolbarSearch.find') +"..."}
						value={this.state.value}
						onChange={this.handleOnChange.bind(this)}
					/>
					<InputGroup.Button>
						<Button
							onClick={this.handleOnClear.bind(this)}
							title={this.context.t('ToolbarSearch.clearSearch')}
						>
							<FontAwesome name="close" /> 
						</Button>
					</InputGroup.Button>
				</InputGroup>
			</ButtonGroup>
		);
	}
}


//set prop types
ToolbarSearch.propTypes = {
	onChange: PropTypes.func.isRequired
};


//set context types
ToolbarSearch.contextTypes = {
	t: PropTypes.func.isRequired
};
