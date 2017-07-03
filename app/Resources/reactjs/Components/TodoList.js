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
import {Route, Switch, Redirect} from 'react-router-dom';
import {PageHeader} from 'react-bootstrap';
import {provideTranslations} from 'react-translate-maker';
import TodoListList from './TodoList/TodoListList';
import TodoListForm from './TodoList/TodoListForm';
import TodoListItem from './TodoList/TodoListItem';


/**
 * Component for the todo list page
 */
@provideTranslations
export default class TodoList extends Component {
	
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
			pageHeader: 'TodoList.pageCaption',
			pageHeaderSmall: '',
			listItems: {}
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
	 * handleSetSubtitle(subtitle)
	 * eventhandler to handle the change of the subtitle
	 * 
	 * @param string subtitle the new subtitle
	 */
	handleSetSubtitle(subtitle) {
		
		// update the state with the new subtitle
		this.updateState('pageHeaderSmall', subtitle);
	}
	
	
	/**
	 * handleListItem(listItems)
	 * eventhandler to handle the change of the list item open/close state
	 * 
	 * @param object listItems an object with the open/close state of the list items given by id
	 */
	handleListItems(listItems) {
		
		// update the state with the new state object
		this.updateState('listItems', listItems);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// set title
		document.title = 'JudoIntranet - ' + this.t(this.state.pageHeader);
		
		return (
			<div className="container">
				<PageHeader>
					{this.t(this.state.pageHeader)}{' '}
					<small>
						{this.t(this.state.pageHeaderSmall)}
					</small>
				</PageHeader>
				<Switch>
					<Route exact path={this.props.match.url} render={() => <Redirect to={this.props.match.url + '/listall'} />} />
					<Route path={this.props.match.url + '/listall'} children={({match, history}) =>
						<TodoListList
							handleSetSubtitle={this.handleSetSubtitle.bind(this)}
							handleListItems={this.handleListItems.bind(this)}
							listItemsState={this.state.listItems}
							match={match}
							history={history}
						/>}
					/>
					<Route path={this.props.match.url + '/new'} children={({match, history}) => 
						<TodoListForm 
							form="new"
							handleSetSubtitle={this.handleSetSubtitle.bind(this)}
							handleListItems={this.handleListItems.bind(this)}
							listItemsState={this.state.listItems}
							match={match}
							history={history}
						/>}
					/>
					<Route path={this.props.match.url + '/edit/:id'} children={({match, history}) => 
						<TodoListForm 
							form="edit"
							handleSetSubtitle={this.handleSetSubtitle.bind(this)}
							handleListItems={this.handleListItems.bind(this)}
							listItemsState={this.state.listItems}
							match={match}
							history={history}
						/>}
					/>
					<Route path={this.props.match.url + '/view/:id'} children={({match, history}) =>
						<TodoListItem
							handleSetSubtitle={this.handleSetSubtitle.bind(this)}
							handleListItems={this.handleListItems.bind(this)}
							listItemsState={this.state.listItems}
							match={match}
							history={history}
						/>}
					/>
				</Switch>
			</div>
		);
	}
}
