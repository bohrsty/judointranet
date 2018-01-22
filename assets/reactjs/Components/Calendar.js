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
import CalendarList from './Calendar/CalendarList';
import CalendarView from './Calendar/CalendarView';
import CalendarConnected from './Calendar/CalendarConnected';
import CalendarEdit from './Calendar/CalendarEdit';


/**
 * Component for the calendar pages
 */
@provideTranslations
export default class Calendar extends Component {
    
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
            pageHeader: 'Calendar.pageCaption',
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
     * @param object|undefined args the arguments for the translation
     */
    handleSetSubtitle(subtitle, args) {
        
        // update the state with the new subtitle
        this.updateState('pageHeaderSmall', this.t(subtitle, args));
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
                        {this.state.pageHeaderSmall}
                    </small>
                </PageHeader>
                <Switch>
                    <Route exact path={this.props.match.url} render={() => <Redirect to={this.props.match.url + '/listall'} />} />
                    <Route path={this.props.match.url + '/listall'} children={({match, history}) =>
                        <CalendarList
                            handleSetSubtitle={this.handleSetSubtitle.bind(this)}
                            handleListItems={this.handleListItems.bind(this)}
                            listItemsState={this.state.listItems}
                            match={match}
                            history={history}
                        />}
                    />
                    <Route exact path={this.props.match.url + '/new'} render={() => <Redirect to={this.props.match.url + '/edit/0'} />} />
                    <Route path={this.props.match.url + '/edit/:id'} children={({match, history}) => 
                        <CalendarEdit
                            handleSetSubtitle={this.handleSetSubtitle.bind(this)}
                            handleListItems={this.handleListItems.bind(this)}
                            match={match}
                            history={history}
                        />}
                    />
                    <Route path={this.props.match.url + '/view/:id'} children={({match, history}) =>
                        <CalendarView
                            handleSetSubtitle={this.handleSetSubtitle.bind(this)}
                            listItemsState={this.state.listItems}
                            match={match}
                            history={history}
                        />}
                    />
                    <Route path={this.props.match.url + '/connected/:id'} children={({match, history}) =>
                        <CalendarConnected
                            handleSetSubtitle={this.handleSetSubtitle.bind(this)}
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
