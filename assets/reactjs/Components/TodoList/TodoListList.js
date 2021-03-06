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
import {Link} from 'react-router-dom';
import {PanelGroup, ButtonGroup, Button, Badge} from 'react-bootstrap';
import {LinkContainer} from 'react-router-bootstrap';
import FontAwesome from 'react-fontawesome';
import FullTable from '../FullTable';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import provideContext from '../../provideContext';


/**
 * Component for the todo list listall component
 */
@provideTranslations
@provideContext
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
            searchQuery: ''
		};
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get list items
		this.getTodoListItems();
		
		// set subtitle
		this.props.handleSetSubtitle('TodoListList.subtitle');
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
	 * getTodoListItems()
	 * retrieves the list items
	 */
	getTodoListItems() {
        
        // TODO: AJAX calls to get the data
        
        // show loading modal
        this.props.startLoading('TodoListList.getTodoListItems');
        
        // get search query
        var query = this.state.searchQuery;
		
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
		
		// update list and page count
		this.updateState('list', allItems);
        
        // simulate ajax call and remove loading modal
        setTimeout(() => this.props.stopLoading('TodoListList.getTodoListItems'), 1000);
		
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
		
		this.props.history.push('/todolist/view/'+ column.id);
	}
    
    
    /**
     * isTitleClickable(row)
     * eventhandler to determine if a row is clickable
     * 
     * @param mixed row the value of the key row
     */
    isTitleClickable(row) {
        
        return true;
    }
    
    
    /**
     * handleSearch(query)
     * handles the update of the list according to the search term in query
     * 
     * @param string query the query string to search for
     */
    handleSearch(query) {
        
        // set query
        this.updateState('searchQuery', query);
        
        // reload data
        if(query == '' || query.length > 2) {
            this.getTodoListItems();
        }
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
        
        // prepare toolbar
        var toolbar = {
            bsSize: 'default',
            search: this.handleSearch.bind(this),
            groups: [
                {
                    buttons: [
                        {
                            type: 'callback',
                            pathname: '',
                            onClick: this.getTodoListItems.bind(this),
                            bsStyle: 'default',
                            icon: 'refresh',
                            iconIsPrefix: true,
                            text: this.t('TodoListList.toolbar.refresh')
                        }
                    ]
                }
            ]
        };
		
		// prepare table
		var cols = {
			title: {
				title: this.t('TodoListList.tableCols.title'),
                isClickable: this.isTitleClickable.bind(this),
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
			    rows={this.state.list}
			    reloadRows={this.getTodoListItems.bind(this)}
                toolbarConfig={toolbar}
                pageSize={5}
				infoContent={this.infoCol.bind(this)}
				viewContent={this.viewCol.bind(this)}
				actionContent={this.actionCol.bind(this)}
			/>
		);
	}
}


//export
export default TodoListList;
