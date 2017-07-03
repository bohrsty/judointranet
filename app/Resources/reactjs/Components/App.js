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
import {Route, Switch} from 'react-router-dom';
import MainMenu from './MainMenu';
import {LocaleProvider} from 'react-translate-maker';
import moment from 'moment';
import IndexPage from './IndexPage';
import TodoList from './TodoList';
import Notification from './Notification';


/**
 * the main Component to layout the app
 */
class App extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// prepare locale
		this.locale = {};
		// prepare menu
		this.menu = {};
		
		// set initial state
		this.state = {
			locale: '',
			alerts: []
		}
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
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get mock locale (to be replaced by AJAX call to api)
		this.locale = require('../mockLocale');
		this.updateState('locale', this.locale.defaultLocale);
		
		/*
		 * mock menu (old and new), to be replaced with AJAX call to api
		 * requires ../mockMenu.js to exists, returning an array with the following content
		 * 
			[
				{
					name: "Name",
					key: "key",
					icon: "icon", // optional font-awesome class
					subItems: [
						{
							name: "Sub name",
							url: "/routerlink or URL",
							router: false, // if false requires url to be URL, if true react-router path name
							icon: "icon", // optional font-awesome class
						},
						.
						.
						.
					]
				},
				.
				.
				.
			]
		 */
		this.menu = require('../mockMenu');
	}
	
	
	/**
	 * getChildContext()
	 * register the context
	 */
	getChildContext() {
		return {addNotification: this.addNotification.bind(this)};
	}
	
	
	/**
	 * addNotification(params)
	 * given to context to add notifications
	 * 
	 * @param object params the parameter object for the notification
	 */
	addNotification(params) {
		
		// get current alerts
		var alerts = this.state.alerts;
		
		// add new alert
		alerts.unshift({
			id: (new Date()).getTime(),
			type: params.type,
			headline: params.headline,
			message: params.message,
			dismissTitle: params.dismissTitle
		});
		
		// update state
		this.updateState('alerts', alerts);
	}
	
	
	/**
	 * handleLocaleChange(locale)
	 * eventhandler to handle the locale change
	 * 
	 * @param string locale the new locale to change to
	 */
	handleLocaleChange(locale) {
		
		this.updateState('locale', locale);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// set title
		document.title = 'JudoIntranet';
		
		// set locale for date picker (moment)
		moment.locale(this.state.locale);
				
		return (
			<LocaleProvider data={this.locale.data} locale={this.state.locale}>
				<div>
					{/* mocked loggedin user, TODO: get from session/cookie */}
					<MainMenu
						navitems={this.menu}
						user="Administrator"
						locales={this.locale.locales}
						handleLocaleChange={this.handleLocaleChange.bind(this)}
					/>
					<Switch>
						<Route exact path="/" children={() => <IndexPage pageContent="homePage" />} />
						<Route path="/todolist" component={TodoList} />
					</Switch>
					<Notification alerts={this.state.alerts} />
				</div>
			</LocaleProvider>
		);
	}
}


// set child context types
App.childContextTypes = {
	addNotification: React.PropTypes.func
};


//export
export default App;
