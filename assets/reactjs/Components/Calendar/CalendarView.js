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
import {Panel, Table} from 'react-bootstrap';
import {Link} from 'react-router-dom';
import FontAwesome from 'react-fontawesome';
import Toolbar from '../Toolbar';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import provideContext from '../../provideContext';
import {LinkContainer} from 'react-router-bootstrap';


/**
 * Component for the calendar view component
 */
@provideTranslations
@provideContext
class CalendarView extends Component {
    
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
            data: {
            	appointment: [],
            	announcement: []
            }
        }
    }
    
    
    /**
     * componentWillMount()
     * executed directly before component will be mounted to DOM
     */
    componentWillMount() {
        
        // get data
        this.getAjaxData();
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
     * componentWillReceiveProps(newProps)
     * executed directly before component will receive new props
     */
    componentWillReceiveProps(newProps) {
        
        // get data
        if(newProps.match.params.id != this.props.match.params.id) {
            this.getAjaxData(newProps.match.params.id);
        }
    }
    
    
    /**
     * getToolbarConfig()
     * returns the toolbar configuration
     */
    getToolbarConfig() {
        
        return {
            bsSize: 'default',
            search: false,
            groups: [
                {
                    buttons: [
                        {
                            type: 'link',
                            pathname: '/calendar/listall',
                            onClick: undefined,
                            bsStyle: 'primary',
                            icon: 'arrow-left',
                            iconIsPrefix: true,
                            text: this.t('CalendarView.toolbar.backToList')
                        },
                        {
                            type: 'linkExternal',
                            pathname: this.state.data.announcementUrl,
                            onClick: undefined,
                            bsStyle: 'default',
                            icon: 'file-pdf-o',
                            iconIsPrefix: true,
                            text: this.t('CalendarView.toolbar.asPdf'),
                            disabled: this.state.data.announcementUrl == ''
                        },
                        {
                            type: 'link',
                            pathname: '/calendar/edit/'+ this.props.match.params.id,
                            onClick: undefined,
                            bsStyle: 'default',
                            icon: 'edit',
                            iconIsPrefix: true,
                            text: this.t('CalendarView.toolbar.edit'),
                            disabled: !this.state.data.editable
                        },
                        {
                            type: 'callback',
                            pathname: '',
                            onClick: this.handleDelete.bind(this),
                            bsStyle: 'danger',
                            icon: 'remove',
                            iconIsPrefix: true,
                            text: this.t('CalendarView.toolbar.delete'),
                            disabled: !this.state.data.deletable
                        }
                    ]
                }
            ]
        };
    }
    
    
    /**
     * getAjaxData(id)
     * retrieves the data from api
     * 
     * @param int id the id to get the data for
     */
    getAjaxData(id = 0) {
        
        // TODO: AJAX calls to get the data
        
        // show loading modal
        this.props.startLoading('CalendarView.getAjaxData');
        
        // check id
        if(id == 0) {
            id = this.props.match.params.id;
        }
        
        // TODO: AJAX call to get the data
        
        var detailsItems = require('../../mockCalendarDetails.js');
        var calendarItems = require('../../mockCalendar.js');
        var item = {};
        // walk through list to the the current item
        for(var key in calendarItems) {
            if(calendarItems[key].id == id) {
                Object.assign(item, calendarItems[key], detailsItems[id]);
                break;
            }
        }
        
        // simulate ajax call and remove loading modal
        setTimeout(() => {
            // set subtitle
            this.props.handleSetSubtitle('CalendarView.subtitle', {subject: item.title});
        	// update state
            this.updateState('data', item);
            // hide loading modal
        	this.props.stopLoading('CalendarView.getAjaxData');
        }, 1000);
    }
    
    
    /**
     * handleDelete()
     * eventhandler to handle delete
     * 
     */
    handleDelete() {
        
        console.log('delete: ' + this.props.match.params.id);
    }
    
    
    /**
     * method to render the component
     */
    render() {
        
        // simplify data
        var data = this.state.data;
        
        // prepare header
        var appointmentHeader = (<h3>{this.t('CalendarView.appointment')}:</h3>);
        var announcementHeader = (<h3>{this.t('CalendarView.announcement')}:</h3>);
        
        // prepare announcement
        var announcement = (
            data.announcement.length > 0 
            ? <Panel header={announcementHeader}>
                <Table
                    striped
                    condensed
                    fill
                >
                    <tbody>
                        {data.announcement.map((row, id) => <tr key={id}><td><strong>{row.title}</strong></td><td>{row.content}</td></tr>)}
                    </tbody>
                </Table>
            </Panel>
            : null
        );
        
        return (
            <div>
                <Toolbar config={this.getToolbarConfig()} />
                <p></p>
                <Panel header={appointmentHeader}>
                    <Table
                    	striped
                    	condensed
                    	fill
                    >
                    	<tbody>
                    	    {data.appointment.map((row, id) => <tr key={id}><td><strong>{row.title}</strong></td><td>{row.content}</td></tr>)}
                    	</tbody>
                    </Table>
                </Panel>
                {announcement}
            </div>
        );
    }
}


// export
export default CalendarView;
