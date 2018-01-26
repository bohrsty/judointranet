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
import PropTypes from 'prop-types';
import moment from 'moment';
import AppInit from './AppInit';
import IndexPage from './IndexPage';
import TodoList from './TodoList';
import Faq from './Faq';
import Notification from './Notification';
import LoadingModal from './LoadingModal';
import Calendar from './Calendar';
import UserProfile from './UserProfile';


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

		// prepare menu
		this.menu = {};
		
		// set initial state
		this.state = {
			currentLocale: 'de_DE',
            locale: {
			    data:{
			        de_DE: {}
                },
                locales: []
            },
            config: {},
			alerts: [],
			loading: [],
			user: {
				id: 0,
				username: '',
				name: '',
                loggedIn: false
            },
            reloadInit: false
		}
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {

        // mock locale
        this.setState({locale: require('../mockLocale')});
		
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
		return {
		    addNotification: this.addNotification.bind(this),
		    startLoading: this.startLoading.bind(this),
		    stopLoading: this.stopLoading.bind(this),
            user: this.state.user,
            reloadInit: this.reloadInit.bind(this)
	    };
	}
	
	
	/**
	 * addNotification(params)
	 * given to context to add notifications
	 * 
	 * @param params the parameter object for the notification
	 */
	addNotification(params) {
		
		// get current alerts
		let alerts = this.state.alerts;
		
		// add new alert
		alerts.unshift({
			id: (new Date()).getTime(),
			type: params.type,
			headline: params.headline,
			message: params.message,
			dismissTitle: params.dismissTitle
		});
		
		// update state
		this.setState({alerts: alerts});
	}


    /**
     * setUser(user)
     * sets the given user
     *
     * @param user the user to set
     */
    setUser(user) {

        // set state
        this.setState({user: user});
    }


    /**
     * setConfig(config)
     * sets the given config
     *
     * @param config the config to set
     */
    setConfig(config) {

        // set state
        this.setState({config: config});
    }


    /**
     * setLocale(locale)
     * sets the given config
     *
     * @param locale the locale to set
     */
    setLocale(locale) {

        // set state
        this.setState({locale: locale});
    }
	
	
	/**
	 * handleLocaleChange(locale)
	 * event handler to handle the locale change
	 * 
	 * @param locale the new locale to change to
	 */
	handleLocaleChange(locale) {

		this.setState({locale: locale});
	}
    
    
    /**
     * startLoading(loader)
     * event handler to start loading state
     * 
     * @param loader the loader to remember
     */
	startLoading(loader) {
        
	    // get current state
	    let loading = this.state.loading;
	    
	    // add loader
	    loading.push(loader);
	    
	    // update state
		this.setState({loading: loading});
    }
    
    
    /**
     * stopLoading(loader)
     * event handler to start loading state
     * 
     * @param loader the loader to remove
     */
    stopLoading(loader) {
        
        // get current state
        let loading = this.state.loading;
        
        // check loader
        let index = loading.indexOf(loader);
        if(index > -1) {
            loading.splice(index, 1);
        }
        
        // update state
        this.setState({loading: loading});
    }


    /**
     * reloadInit()
     * event handler to start loading state
     */
    reloadInit(bool) {

        // update state
        this.setState({reloadInit: bool});
    }
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// set title
		document.title = 'JudoIntranet';
		
		// set locale for date picker (moment)
		moment.locale(this.state.currentLocale);

		return (
			<LocaleProvider data={this.state.locale.data} locale={this.state.currentLocale}>
				<div>
                    <AppInit
                        setConfig={this.setConfig.bind(this)}
                        setUser={this.setUser.bind(this)}
                        reload={this.state.reloadInit}
                    />
					<MainMenu
						navitems={this.menu}
						locales={this.state.locale.locales}
						handleLocaleChange={this.handleLocaleChange.bind(this)}
					/>
					<Switch>
						<Route exact path="/" children={() => <IndexPage pageContent="homePage" />} />
                        <Route path="/profile" component={UserProfile} />
                        <Route path="/todolist" component={TodoList} />
						<Route path="/faq" component={Faq} />
						<Route path="/calendar" component={Calendar} />
					</Switch>
					<Notification alerts={this.state.alerts} />
				    {this.state.loading.length !== 0 ? <LoadingModal show={true} /> : ''}
				</div>
			</LocaleProvider>
		);
	}
}


// set child context types
App.childContextTypes = {
	addNotification: PropTypes.func.isRequired,
	startLoading: PropTypes.func.isRequired,
	stopLoading: PropTypes.func.isRequired,
    user: PropTypes.object.isRequired,
    reloadInit: PropTypes.func.isRequired
};


//export
export default App;
