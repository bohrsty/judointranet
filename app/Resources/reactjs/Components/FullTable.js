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
import {Table} from 'react-bootstrap';
import FontAwesome from 'react-fontawesome';
import PaginationPagesize from './PaginationPagesize';
import Toolbar from './Toolbar';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import provideContext from '../provideContext';

/**
 * Component for the table
 */
@provideTranslations
@provideContext
class FullTable extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
        
        // set translation
        this.t = this.props.t;
		
		// prepare cols
		this.cols = Object.keys(this.props.cols);
		
		// set initial state
		this.state = {
			list: [],
			activePage: 1,
			pageSize: 5,
			pageCount: 1,
			loading: false
		};
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get list items
		this.getList();
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
	getList(query = '') {
		
		// TODO: get items per AJAX call
		
		// show loading modal
		this.props.startLoading('FullTable.getList');
		
		// get all items, page number and size
		var mockItems = require('../mockTodolist');		
		var allItems = [];
		if(query == '' || typeof query != 'string') {
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
		
		// simulate ajax call and remove loading modal
		setTimeout(() => this.props.stopLoading('FullTable.getList'), 1000);
		
	}
	
	
	/**
	 * getTableHead()
	 * gets the head row of the table
	 */
	getTableHead() {
		
		// prepare head rows
		var headRows = [];
		
		// add col for row number
		headRows.push(<th key={0}>#</th>);
		
		// walk through cols
		for(var i = 0; i < this.cols.length; i++) {
			
			// add row
			headRows.push(
				<th
					key={i + 1}
					style={this.props.cols[this.cols[i]].width != undefined ? {minWidth: this.props.cols[this.cols[i]].width} : undefined}
				>
					{this.props.cols[this.cols[i]].title}
				</th>
			);
		}
		
		// add info, view and action col
		if(typeof this.props.infoContent == 'function') {
			headRows.push(<th key={this.cols.length + 1}><FontAwesome name="info-circle" title={this.t('FullTable.info')} /></th>);
		}
		if(typeof this.props.viewContent == 'function') {
			headRows.push(<th key={this.cols.length + 2}><FontAwesome name="eye" title={this.t('FullTable.views')} /></th>);
		}
		if(typeof this.props.actionContent == 'function') {
			headRows.push(<th key={this.cols.length + 3}><FontAwesome name="tasks" title={this.t('FullTable.tasks')} /></th>);
		}
		
		// return head
		return (
			<thead>
				<tr>
					{headRows}
				</tr>
			</thead>
		);
	}
	
	
	/**
	 * getTableBody()
	 * gets and formats the body rows of the table 
	 */
	getTableBody() {
		
		// prepare body rows
		var bodyRows = [];
		
		// walk through data
		for(var i = 0; i < this.state.list.length; i++) {
			
			// prepare cols
			var cols = [];
			
			// calculate row numbers
			var rowNumber = 0;
			if(this.state.activePage == 1) {
				rowNumber = i + 1;
			} else if(this.state.activePage == 2){
				rowNumber = i + 1 + this.state.pageSize;
			} else {
				rowNumber = i + 1 + ((this.state.activePage -1) * this.state.pageSize);
			}
			
			// add number col
			cols.push(
				<td key={0}>{rowNumber}</td>
			);
			
			// walk through cols
			for(var j = 0; j < this.cols.length; j++) {
				
				// format if function "content" is set
				var content = this.state.list[i][this.cols[j]];
				if(this.props.cols[this.cols[j]].content != undefined) {
					content = this.props.cols[this.cols[j]].content(this.state.list[i][this.cols[j]]);
				}
				
				// prepare on click handler
				var onClick = undefined;
				var style = undefined;
				var title = undefined;
				if(this.props.cols[this.cols[j]].onClick != undefined) {
					onClick = this.props.cols[this.cols[j]].onClick.bind(this, this.state.list[i]);
					style = {cursor: "pointer"};
					title = this.props.cols[this.cols[j]].onClickTitle;
				}
				
				cols.push(
					<td
						key={j + 1}
						onClick={onClick}
						style={style}
						title={title}
					>
						{content}
					</td>
				);
			}
			
			// add view and settings cols
			if(typeof this.props.infoContent == 'function') {
				cols.push(<td key={this.cols.length + 1} style={{cursor: "default"}}>{this.props.infoContent(this.state.list[i])}</td>);
			}
			if(typeof this.props.viewContent == 'function') {
				cols.push(<td key={this.cols.length + 2}>{this.props.viewContent(this.state.list[i])}</td>);
			}
			if(typeof this.props.actionContent == 'function') {
				cols.push(<td key={this.cols.length + 3}>{this.props.actionContent(this.state.list[i])}</td>);
			}
			
			// add row
			bodyRows.push(
				<tr key={i}>
					{cols}
				</tr>
			);
		}
		
		// return body
		return (
			<tbody>
				{bodyRows}
			</tbody>
		);
	}
	
	
	/**
	 * getToolbar()
	 * sets the toolbar config and returns the toolbar componenet
	 */
	getToolbar() {
		
		// toolbar config
		var toolbar = {
			bsSize: 'default',
			search: this.handleSearch.bind(this),
			groups: [
			 	{
			 		buttons: [
			 			{
			 				type: 'callback',
							pathname: '',
							onClick: this.getList.bind(this),
			 				bsStyle: 'default',
			 				icon: 'refresh',
			 				iconIsPrefix: true,
			 				text: this.t('FullTable.toolbar.refresh')
			 			}
			 		]
				}
			]
		};
		
		// return component
		return (
			<Toolbar config={toolbar} />
		);
	}
	
	
	/**
	 * handlePagination(page, pageSize)
	 * eventhandler to handle the state change from pagination
	 * 
	 * @param int page the new/current page number
	 * @param int pageSize the new/current count of items per page
	 */
	handlePagination(page, pageSize) {
		
		// update states
		this.updateState('activePage', parseInt(page, 10));
		this.updateState('pageSize', parseInt(pageSize, 10));
		
		// get items and set to state
		this.getList();
	}
	
	
	/**
	 * handleSearch(query)
	 * handles the update of the list according to the search term in query
	 * 
	 * @param string query the query string to search for
	 */
	handleSearch(query) {
		
		// check length of query
		if(query.length > 2 || query == '') {
			this.getList(query);
		}
	}
	
	
	/**
	 * method to render the component
	 */
	/*
		cols: {
			id: {
				title: string,
				content: func(value),
				width: number,
				onClick: func(value, completeRow)
			},
			title: {
			
			}
		}
	 */
	render() {
		
		return (
	        <div>
				{this.getToolbar()}
				<p></p>
				<Table
					striped
					bordered
					condensed
					hover
					responsive
				>
					{this.getTableHead()}
					{this.getTableBody()}
				</Table>
				<PaginationPagesize
					activePage={this.state.activePage}
					pageSize={this.state.pageSize}
					pageCount={this.state.pageCount}
					onSelect={this.handlePagination.bind(this)}
				/>
			</div>
		);
	}
}


// set prop types
FullTable.propTypes = {
	cols: PropTypes.object.isRequired,
	infoContent: PropTypes.oneOfType([
		PropTypes.func,
		PropTypes.bool
	]).isRequired,
	viewContent: PropTypes.oneOfType([
		PropTypes.func,
		PropTypes.bool
	]).isRequired,
	actionContent: PropTypes.oneOfType([
		PropTypes.func,
		PropTypes.bool
	]).isRequired
};


// export
export default FullTable;
