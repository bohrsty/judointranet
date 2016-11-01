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
import {Link} from 'react-router';
import {PanelGroup, ButtonGroup, Button, Badge} from 'react-bootstrap';
import {LinkContainer} from 'react-router-bootstrap';
import FontAwesome from 'react-fontawesome';
import FullTable from '../FullTable';


/**
 * Component for the todo list listall component
 */
class TodoListList extends Component {
	
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
			list: [],
			activePage: 1,
			pageSize: 5,
			pageCount: 1
		};
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get translation method
		this.t = this.context.t;
		
		// get list items
		this.getTodoListItems();
		
		// set subtitle
		this.props.handleSetSubtitle(this.t('TodoListList.subtitle'));
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
	 * getTodoListItems(query)
	 * retrieves the list items
	 * 
	 * @param string query optional string to search for in list
	 */
	getTodoListItems(query = '') {
		
		// TODO: get items per AJAX call
		
		// get all items, page number and size
		var mockItems = require('../../mockTodolist');
		var allItems = [];
		if(query == '') {
			allItems = mockItems;
		} else {
			// search
			for(var i in mockItems) {
				if(mockItems[i].title.indexOf(query) != -1 || mockItems[i].text.indexOf(query) != -1) {
					allItems.push(mockItems[i]);
				}
			}
		}
		var page = this.state.activePage;
		var pageSize = this.state.pageSize;
		// calculate start/end
		var start = 0;
		if(page != 1) {
			start = pageSize * (page - 1);
		}
		var end = (start + pageSize > allItems.length ? allItems.length : start + pageSize);
		
		// get items
		var list = [];
		for(var i = start; i < end; i++) {
			list.push(allItems[i]);
		}
		
		// get page count
		var pageCount = (allItems.length % pageSize != 0 ? Math.floor(allItems.length / pageSize) + 1 : Math.floor(allItems.length / pageSize));
		
		// update list and page count
		this.updateState('list', list);
		this.updateState('pageCount', pageCount);
		
	}
	
	
	/**
	 * handleFinish(id, e)
	 * eventhandler to handle finish an item
	 * 
	 * @param int id the id of the finished item
	 * @param object e the event object
	 */
	handleFinish(id, e) {
		
		e.preventDefault();
		console.log('finish: '+id);
	}
	
	
	/**
	 * handleDelete(id, e)
	 * eventhandler to handle delete an item
	 * 
	 * @param int id the id of the deleted item
	 * @param object e the event object
	 */
	handleDelete(id, e) {
		
		e.preventDefault();
		console.log('delete: '+id);
	}
	
	
	/**
	 * handleTitleOnClick(column)
	 * eventhandler to handle click on title column
	 * 
	 * @param mixed column the value of the key row
	 */
	handleTitleOnClick(column) {
		
		this.context.router.push('/todolist/view/'+ column.id);
	}
	
	
	/**
	 * textCol(content)
	 * creates the content for the "text" column
	 */
	textCol(content) {
		
		var textlength = 50;
		
		return content.length > textlength ? content.substring(0, 50 - 3) +'...' : content;
	}
	
	
	/**
	 * assignedToCol(content)
	 * creates the content for the "assignedTo" column
	 */
	assignedToCol(content) {
		
		return (
			<span title={content.username +' ['+ content.id +']'}>{content.name}</span>
		);
	}
	
	
	/**
	 * infoCol(row)
	 * creates the content for the "info" column
	 */
	infoCol(row) {
		return (
			<span>
				<FontAwesome name={row.finished ? "check-square-o" : "square-o"} title={row.finished ? this.t('TodoListList.finished') : this.t('TodoListList.unfinished')} />
				{' '}
				<Badge title="Unteraufgaben">{row.subitems == undefined ? 0 : row.subitems.length}</Badge>
				{' '}
				{row.comment != '' ? <FontAwesome name="comment" title="Kommentar vorhanden" /> : ''}
			</span>
		);
	}
				
				
	/**
	 * viewCol(row)
	 * creates the content for the "view" column
	 */
	viewCol(row) {
		return (
			<span>
				<Link to={"/todolist/view/"+ row.id}><FontAwesome name="search-plus" title={this.t('TodoListList.details')} /></Link>
			</span>
		);
	}
				
				
	/**
	 * actionCol(row)
	 * creates the content for the "action" column
	 */
	actionCol(row) {
		return (
			<span>
				{row.editable ? <Link to={"/todolist/edit/"+ row.id}><FontAwesome name="pencil" title={this.t('TodoListList.edit')} /></Link> : ''}
				{' '}
				{row.deletable ? <Link to=""><FontAwesome name="remove" title={this.t('TodoListList.delete')} onClick={this.handleDelete.bind(this, row.id)} /></Link> : ''}
				{' '}
				{row.finishable ? <Link to=""><FontAwesome name={row.finished ? "square-o" : "check-square-o"} title={row.finished ? this.t('TodoListList.unfinish') : this.t('TodoListList.finish')} onClick={this.handleFinish.bind(this, row.id)} /></Link> : ''}
			</span>
		);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// prepare table
		var cols = {
			title: {
				title: this.t('TodoListList.tableCols.title'),
				onClick: this.handleTitleOnClick.bind(this),
				onClickTitle: this.t('TodoListList.clickToView')
			},
			text: {
				title: this.t('TodoListList.tableCols.text'),
				content: this.textCol.bind(this)
			},
			assignedTo: {
				title: this.t('TodoListList.tableCols.assignedTo'),
				content: this.assignedToCol.bind(this)
			},
			due: {
				title: this.t('TodoListList.tableCols.due'),
			}
		};
		
		return (
			<FullTable
				cols={cols}
				infoContent={this.infoCol.bind(this)}
				viewContent={this.viewCol.bind(this)}
				actionContent={this.actionCol.bind(this)}
			/>
		);
	}
}


//set context types
TodoListList.contextTypes = {
	router: React.PropTypes.object.isRequired,
	t: React.PropTypes.func.isRequired
};


//export
export default TodoListList;
