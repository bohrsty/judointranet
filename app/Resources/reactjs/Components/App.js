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
import MainMenu from './MainMenu';
import {provideTranslations, LocaleProvider} from 'react-translate-maker';


/**
 * the main Component to layout the app
 */
export default class App extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// get default locale
		var {defaultLocale} = this.getLocaleData();
		
		// set initial state
		this.state = {
			locale: defaultLocale
		}
	}
	
	
	/**
	 * getNavItems()
	 * retrieves the navigation items
	 * 
	 * @return object object with the navigation data
	 */
	getNavItems() { 
		
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
		return require("../mockMenu");
	}
	
	
	/**
	 * getLocaleData()
	 * retrieves the locale data, the usable locales and the default locale
	 * 
	 * @return object {data, locales, defaultLocale} the locale data, the usable locales and the default locale
	 */
	getLocaleData() {
		return {
			data: require("../mockLocale"),
			locales: [{
					label: 'localeName.de_DE',
					value: 'de_DE'
				},{
					label: 'localeName.en_US',
					value: 'en_US'
				}],
			defaultLocale: 'de_DE'
		};
	}
	
	
	/**
	 * handleLocaleChange(locale)
	 * handle the locale change (set state)
	 * 
	 * @param string locale the new locale to change to
	 */
	handleLocaleChange(locale) {
		
		this.setState({
			locale: locale
		});
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// set title
		document.title = 'JudoIntranet';
		
		// get locale
		var {data, locales} = this.getLocaleData();
		
		return (
			<LocaleProvider adapter={data} locale={this.state.locale}>
				<div>
					{/* mocked loggedin user, TODO: get from session/cookie */}
					<MainMenu
						navitems={this.getNavItems()}
						user="Administrator"
						locales={locales}
						handleLocaleChange={this.handleLocaleChange.bind(this)}
					/>
					{this.props.children}
				</div>
			</LocaleProvider>
		);
	}
}
