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
 * Component for the calendar listall component
 */
@provideTranslations
@provideContext
class CalendarList extends Component {
    
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
        this.getCalendarItems();
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
     * getCalendarItems()
     * retrieves the list items
     */
    getCalendarItems() {
        
        // TODO: AJAX calls to get the data
        
        // show loading modal
        this.props.startLoading('CalendarList.getTodoListItems');
        
        // get search query
        var query = this.state.searchQuery;
        
        // get all items, page number and size
        var mockItems = require('../../mockCalendar');
        var allItems = [];
        if(query == '' || query.length < 3) {
            allItems = mockItems;
        } else {
            // search
            for(var i in mockItems) {
                if(mockItems[i].event.indexOf(query) != -1 || mockItems[i].location.indexOf(query) != -1) {
                    allItems.push(mockItems[i]);
                }
            }
        }
        
        // simulate ajax call and remove loading modal
        setTimeout(() => {
            // set subtitle
            this.props.handleSetSubtitle('CalendarList.subtitle');
            // update list
            this.updateState('list', allItems);
            // hide loading modal
            this.props.stopLoading('CalendarList.getTodoListItems')
        }, 1000);
        
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
     * handleRowOnClick(row)
     * eventhandler to handle click on row
     * 
     * @param mixed row the value of the key row
     */
    handleRowOnClick(row) {
        
        if(row.isExternal === false) {
            this.props.history.push('/calendar/view/' + row.id);
        }
    }
    
    
    /**
     * isRowClickable(row)
     * eventhandler to determine if a row is clickable
     * 
     * @param mixed row the value of the key row
     */
    isRowClickable(row) {
        
        return row.isExternal === false;
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
            this.getCalendarItems();
        }
    }
    
    
    /**
     * infoCol(row)
     * creates the content for the "info" column
     */
    infoCol(row) {
        return (
            <span>
                {row.isExternal ? <FontAwesome name="external-link" title={this.t('CalendarList.isExternal')} /> : ''}
            </span>
        );
    }
                
                
    /**
     * viewCol(row)
     * creates the content for the "view" column
     */
    viewCol(row) {
        
        var _return = '';
        
        if(row.isExternal === false) {
            _return = (
                <span>
                    <Link to={"/calendar/view/"+ row.id}><FontAwesome name="search-plus" title={this.t('CalendarList.showAnnouncement')} /></Link>
                    {' '}
                    <a href={row.announcementUrl}><FontAwesome name="file-pdf-o" title={this.t('CalendarList.showAnnouncementPdf')} /></a>
                    {' '}
                    {row.webserviceConnected ? <Link to={"/calendar/connected/"+ row.id}><FontAwesome name="handshake-o" title={this.t('CalendarList.webserviceConnected')} /></Link> : ''}
                </span>
            );
        }
        
        return _return;
    }
                
                
    /**
     * actionCol(row)
     * creates the content for the "action" column
     */
    actionCol(row) {
        return (
            <span>
                {row.editable ? <Link to={"/calendar/edit/"+ row.id}><FontAwesome name="pencil" title={this.t('CalendarList.edit')} /></Link> : ''}
                {' '}
                {row.deletable ? <span className="clickable clickable-blue"><FontAwesome name="remove" title={this.t('CalendarList.delete')} onClick={this.handleDelete.bind(this, row.id)} /></span> : ''}
            </span>
        );
    }
    
    
    /**
     * method to render the component
     */
    render() {
        
        // prepare list toolbar
        var toolbar = {
            bsSize: 'default',
            search: this.handleSearch.bind(this),
            groups: [
                {
                    buttons: [
                        {
                            type: 'callback',
                            pathname: '',
                            onClick: this.getCalendarItems.bind(this),
                            bsStyle: 'default',
                            icon: 'refresh',
                            iconIsPrefix: true,
                            text: this.t('CalendarList.toolbar.refresh')
                        }
                    ]
                }
            ]
        };
        
        // prepare table
        var cols = {
            begin: {
                title: this.t('CalendarList.tableCols.begin'),
                isClickable: this.isRowClickable.bind(this),
                onClick: this.handleRowOnClick.bind(this),
                onClickTitle: this.t('CalendarList.clickToView')
            },
            end: {
                title: this.t('CalendarList.tableCols.end'),
                isClickable: this.isRowClickable.bind(this),
                onClick: this.handleRowOnClick.bind(this),
                onClickTitle: this.t('CalendarList.clickToView')
            },
            event: {
                title: this.t('CalendarList.tableCols.event'),
                isClickable: this.isRowClickable.bind(this),
                onClick: this.handleRowOnClick.bind(this),
                onClickTitle: this.t('CalendarList.clickToView')
            },
            location: {
                title: this.t('CalendarList.tableCols.location'),
                isClickable: this.isRowClickable.bind(this),
                onClick: this.handleRowOnClick.bind(this),
                onClickTitle: this.t('CalendarList.clickToView')
            }
        };
        
        return (
            <FullTable
                cols={cols}
                rows={this.state.list}
                reloadRows={this.getCalendarItems.bind(this)}
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
export default CalendarList;
