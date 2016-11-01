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
import {ListGroup, ListGroupItem, ButtonGroup, Button, Popover, OverlayTrigger} from 'react-bootstrap';
import {Link} from 'react-router';
import {LinkContainer} from 'react-router-bootstrap';
import FontAwesome from 'react-fontawesome';


/**
 * Component for the todo list item component
 */
class TodoListSubitemList extends Component {
	
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
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get translation method
		this.t = this.context.t;
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
	 * method to render the component
	 */
	render() {
		
		// get data
		var data = this.props.data;
		
		// check data
		if(data.length > 0) {
			
			// prepare list
			var list = [];
			
			for(var i in data) {
				
				// prepare popover
				var popover = (
					<Popover id={data[i].id} title={data[i].title}>
						{data[i].text}
					</Popover>
				);
				
				list.push(
					<OverlayTrigger trigger={['hover', 'focus']} placement="left" overlay={popover} key={data[i].id}>
						<LinkContainer to={{pathname: '/todolist/view/'+ data[i].id}}>
							<Button style={{textAlign:"left"}}><FontAwesome name="search-plus" /> {data[i].title}</Button>
						</LinkContainer>
					</OverlayTrigger>
				);
			}
		} else {
			var list = (
				<Button disabled style={{textAlign:"left"}}>no subitems</Button>
			);
		}
		
		return (
			<ButtonGroup
				bsSize="xsmall"
				vertical
				block
			>
				{list}
			</ButtonGroup>
		);
	}
}


//set context types
TodoListSubitemList.contextTypes = {
	t: React.PropTypes.func.isRequired
};


//export
export default TodoListSubitemList;
