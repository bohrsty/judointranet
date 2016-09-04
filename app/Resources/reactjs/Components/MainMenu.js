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
import {Navbar, Nav, NavItem, NavDropdown, MenuItem} from 'react-bootstrap';
import FontAwesome from 'react-fontawesome';
import {LinkContainer} from 'react-router-bootstrap';
import {Link} from 'react-router';
import {provideTranslations} from 'react-translate-maker';


/**
 * Component for the main menu to navigate through the app
 */
@provideTranslations
export default class MainMenu extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// get translation method
		this.t = this.props.t;
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		return (
			<Navbar>
				<Navbar.Header>
					<Navbar.Brand>
						<Link to="/">JudoIntranet</Link>
					</Navbar.Brand>
					<Navbar.Toggle />
				</Navbar.Header>
				<Navbar.Collapse>
					<Nav>
						{this.generateMenu(this.props.navitems)}
					</Nav>
					<Nav pullRight>
						{this.generateLocaleSelection(this.props.locales, this.props.handleLocaleChange)}
						<LinkContainer to={{pathname: "/user"}}>
							<NavItem title={this.t("MainMenu.user") + " " + this.props.user}><FontAwesome name="user" /></NavItem>
						</LinkContainer>
						<LinkContainer to={{pathname: "/settings"}}>
							<NavItem title={this.t("MainMenu.settings")}><FontAwesome name="cog" /></NavItem>
						</LinkContainer>
						<LinkContainer to={{pathname: "/logoff"}}>
							<NavItem title={this.t("MainMenu.logoff")}><FontAwesome name="sign-out" /></NavItem>
						</LinkContainer>
					</Nav>
				</Navbar.Collapse>
			</Navbar>
		);
	}
	
	
	/**
	 * generateMenu(navItems)
	 * generates the menu items
	 * 
	 * @param object navItems object with the navigation structure
	 * @return jsx with the navigation main menu
	 */
	generateMenu(navItems) {
		
		// prepare menu dropdown icon
		var title = <span><FontAwesome name="bars" /> {this.t("MainMenu.menuDropdown")}</span>;
		
		// create menu from array
		return (
			<NavDropdown title={title} id="menu">
				{navItems.map((item) => <NavDropdown title={item.icon ? <span><FontAwesome name={item.icon} /> {this.t(item.name)}</span> : t(item.name)} key={item.key} id={item.name}>
					{
						item.subItems.map((subitem) => {
							var name = <span><FontAwesome name={subitem.icon ? subitem.icon : "circle-o"} style={{"fontSize": "0.7em"}} /> {this.t(subitem.name)}</span>;
							if(subitem.router === true) {
								return (<LinkContainer to={{pathname: subitem.url}} key={subitem.url}><MenuItem eventKey={subitem.url}>{name}</MenuItem></LinkContainer>);
							} else {
								return (<MenuItem eventKey={subitem.url} key={subitem.url} onSelect={this.handleMenuItemSelect}>{name}</MenuItem>);
							}
						})
					}
				</NavDropdown>)}
			</NavDropdown>
		);
	}
	
	
	/**
	 * generateLocaleSelection(locales, callback)
	 * generates the locale dropdown
	 * 
	 * @param object locales object with the locales
	 * @param function callback function to handle the locale selection
	 * @return jsx the navigation dropdown structure
	 */
	generateLocaleSelection(locales, callback) {
		
		// prepare menu dropdown icon
		var title = <FontAwesome name="globe" />;
		
		return (
				<NavDropdown title={title} id="locale">
					{locales.map((locale) => <MenuItem eventKey={locale.value} key={locale.value} onSelect={callback.bind(this,locale.value)}>{this.t(locale.label)}</MenuItem>)}
				</NavDropdown>
		);
	}
	
	
	/**
	 * handleMenuItemSelect(eventKey)
	 * handle click on non react-router menu items
	 * 
	 * @param mixed eventKey the event key that was clicked on
	 */
	handleMenuItemSelect(eventKey) {
		
		// change location
		window.location.href = eventKey;
	}
}
