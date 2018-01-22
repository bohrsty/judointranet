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
 * Component for the calendar connected component
 */
@provideTranslations
@provideContext
class CalendarConnected extends Component {
    
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
                webservices: []
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
                            text: this.t('CalendarConnected.toolbar.backToList')
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
        this.props.startLoading('CalendarConnected.getAjaxData');
        
        // check id
        if(id == 0) {
            id = this.props.match.params.id;
        }
        
        // TODO: AJAX call to get the data
        
        var connectedItems = require('../../mockCalendarConnected.js');
        var item = {};
        // walk through list to the the current item
        for(var key in connectedItems) {
            if(connectedItems[key].id == id) {
                item = connectedItems[key];
                break;
            }
        }
        
        // simulate ajax call and remove loading modal
        setTimeout(() => {
            // set subtitle
            this.props.handleSetSubtitle('CalendarConnected.subtitle', {subject: item.title});
            // update state
            this.updateState('data', item);
            // hide loading modal
            this.props.stopLoading('CalendarConnected.getAjaxData')
        }, 1000);
    }
    
    
    /**
     * method to render the component
     */
    render() {
        
        // simplify data
        var data = this.state.data;
        
        // prepare webservices
        var webservices = data.webservices.map((webservice, wsId) => {
            
            // prepare header
            var header = (<span><strong>{webservice.name}</strong> (<a href={webservice.url}>{webservice.url}</a>)</span>);
            return (
                <Panel
                    key={wsId}
                    header={header}
                >
                    <Table
                        striped
                        condensed
                        fill
                    >
                        <tbody>
                            {webservice.links.map((row, id) => <tr key={id}><td><strong>{row.title}</strong></td><td><a href={row.url}>{row.url}</a></td></tr>)}
                        </tbody>
                    </Table>
                </Panel>
            );
        });
        
        return (
            <div>
                <Toolbar config={this.getToolbarConfig()} />
                <p></p>
                {webservices}
            </div>
        );
    }
}


// export
export default CalendarConnected;
