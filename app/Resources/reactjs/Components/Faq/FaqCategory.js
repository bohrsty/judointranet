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
import {
    Panel,
    PanelGroup,
    ButtonGroup,
    Button,
    Badge,
    ListGroup,
    ListGroupItem,
    Row,
    Col
} from 'react-bootstrap';
import {LinkContainer} from 'react-router-bootstrap';
import FontAwesome from 'react-fontawesome';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import Toolbar from '../Toolbar';


/**
 * Component for the faq category component
 */
@provideTranslations
class FaqCategory extends Component {
	
	
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
			list: []
		};
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get list items
		this.getFaqCategoryList();
		
		// set subtitle
		this.props.handleSetSubtitle('FaqCategory.subtitle');
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
	 * getFaqCategoryList(query)
	 * retrieves the cartegory items
	 * 
	 * @param string query optional string to search for in list
	 */
	getFaqCategoryList(query = '') {
		
		// TODO: get items per AJAX call
		
		// get all items, page number and size
		var allItems = [];
		if(query == '') {
			allItems = require('../../mockFaqCategory');
		} else {
		    
		    var mockItems = require('../../mockFaqSearch');
		    var entries = [];
			// search
			for(var i in mockItems) {
				if(mockItems[i].title.indexOf(query) != -1 || mockItems[i].content.indexOf(query) != -1) {
					entries.push(mockItems[i]);
				}
			}
	        
	        allItems = [{
	            title: 'Search results',
	            entries: entries,
	            id: 0,
	            description: '',
	            open: true
	        }];
		}
		
		// get items
		var list = [];
		for(var i = 0; i < allItems.length; i++) {
		    
		    // check if categoryId is set and ajust open state
		    if(this.props.match.params.categoryId !== undefined) {
    		    if(allItems[i].id == this.props.match.params.categoryId){
    		        allItems[i].open = true;
    		    }
		    }
			list.push(allItems[i]);
		}
		
		// update list
		this.updateState('list', list);
		
	}
    
    
    /**
     * getToolbarConfig()
     * returns the toolbar config
     */
    getToolbarConfig() {
        
        // toolbar config
        return {
            bsSize: 'default',
            search: this.handleSearch.bind(this),
            groups: [
                {
                    buttons: [
                        {
                            type: 'dropdown',
                            bsStyle: 'success',
                            icon: 'plus',
                            iconIsPrefix: true,
                            text: this.t('FaqCategory.toolbar.new'),
                            dropdown: [
                                {
                                    type: 'link',
                                    pathname: '/faq/new',
                                    onClick: undefined,
                                    text: this.t('FaqCategory.toolbar.newFaq')
                                },
                                {
                                    type: 'link',
                                    pathname: '/faq/category/new',
                                    onClick: undefined,
                                    text: this.t('FaqCategory.toolbar.newCategory')
                                }
                            ]
                        },
                        {
                            type: 'callback',
                            pathname: '',
                            onClick: this.getFaqCategoryList.bind(this, ''),
                            bsStyle: 'default',
                            icon: 'refresh',
                            iconIsPrefix: true,
                            text: this.t('FaqCategory.toolbar.refresh')
                        }
                    ]
                }
            ]
        };
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
            this.getFaqCategoryList(query);
        }
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
	 * method to render the component
	 */
	render() {
		
		return (
			<div>
		        <Toolbar config={this.getToolbarConfig()} />
	            <p></p>
		        <PanelGroup>
    				{this.state.list.map((category, id) => {
    					
    				    // prepare header
    				    var header = (
				            <Row key={id}>
				                <Col
				                    md={11}
				                    xs={10}
				                >
    			            <span
    			                className="clickable"
    		                    onClick={() => {
    		                        var newList = this.state.list;
    		                        newList[id].open = !newList[id].open;
    		                        this.updateState('list', newList);
    		                    }}
    			            >
    			                {category.title}
    			            </span>
    			                </Col>
                                <Col
                                    md={1}
                                    xs={2}
                                >
                                    <Badge title={this.t('FaqCategory.entries', {count: category.entries.length})}>{category.entries.length}</Badge>
                                </Col>
			                </Row>
    		            );
    				    
    				    // prepare entries
    				    if(category.entries.length > 0){
    				        var entries = category.entries.map((entry, eId) => 
    				            <ListGroupItem
    				                key={eId}
    				                className="clickable"
    				                onClick={() => this.props.history.push('/faq/view/' + entry.id)}
    				            >
    				                {entry.title}
    			                </ListGroupItem>
    			            );
    				    } else {
    				        var entries = <ListGroupItem>{this.t('FaqCategory.noItemsExist')}</ListGroupItem>;
    				    }
    				    
    				    
    				    return (<Panel
    						key={id}
    						eventKey={category.id}
    						header={header}
    						collapsible
    						expanded={category.open}
    					>
    						<Row key={id}>
    						    <Col
    						        md={11}
    						        xs={10}
    						    >
    						        {category.description}
						        </Col>
                                <Col
                                    md={1}
                                    xs={2}
                                >
                                    {category.editable ? <Link to={"/faq/category/edit/" + category.id}><FontAwesome name="pencil" title={this.t('FaqCategory.edit')} /></Link> : ''}
                                    {' '}
                                    {category.deletable ? <Link to=""><FontAwesome name="remove" title={this.t('FaqCategory.delete')} onClick={this.handleDelete.bind(this, category.id)} /></Link> : ''}
                                </Col>
                            </Row>
    						<ListGroup fill>
    						    {entries}
    						</ListGroup>
    					</Panel>);
    				})}
    			</PanelGroup>
			</div>
		);
	}
}


//export
export default FaqCategory;
