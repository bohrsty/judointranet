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
import {Link} from 'react-router-dom';
import {provideTranslations} from 'react-translate-maker';
import provideContext from '../provideContext';


/**
 * Component for the main menu to navigate through the app
 */
@provideTranslations
@provideContext
export default class MainMenu extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
        
        // set translation
        this.t = this.props.t;
	}
	
	
	/**
	 * method to render the component
	 */
	render() {

		// get user information
        let username = this.t('MainMenu.login');
		if(this.props.user.loggedIn !== false) {
            username = this.props.user.name;
		}
		
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
                        <LinkContainer to={{pathname: "/profile"}}>
                            <NavItem title={this.t("MainMenu.user") + ": " + username}><FontAwesome name="user" /> {username}</NavItem>
                        </LinkContainer>
						<LinkContainer to={{pathname: "/settings"}}>
							<NavItem title={this.t("MainMenu.settings")}><FontAwesome name="cog" /></NavItem>
						</LinkContainer>
						{this.props.user.loggedIn === true
							? <NavItem title={this.t("MainMenu.logoff")} onClick={this.handleLogoff.bind(this)}><FontAwesome name="sign-out" /></NavItem>
							: null
						}
					</Nav>
				</Navbar.Collapse>
			</Navbar>
		);
	}
	
	
	/**
	 * generateMenu(navItems)
	 * generates the menu items
	 * 
	 * @param navItems object with the navigation structure
	 * @return jsx with the navigation main menu
	 */
	generateMenu(navItems) {
		
		// prepare menu dropdown icon
		let title = <span><FontAwesome name="bars" /> {this.t("MainMenu.menuDropdown")}</span>;
		
		// create menu from array
		return (
			<NavDropdown title={title} id="menu">
				{navItems.map((item) => <NavDropdown title={item.icon ? <span><FontAwesome name={item.icon} /> {this.t(item.name)}</span> : t(item.name)} key={item.key} id={item.name}>
					{
						item.subItems.map((subitem) => {
							let name = <span><FontAwesome name={subitem.icon ? subitem.icon : "circle-o"} style={{"fontSize": "0.7em"}} /> {this.t(subitem.name)}</span>;
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
	 * @param locales object with the locales
	 * @param callback function to handle the locale selection
	 * @return jsx the navigation dropdown structure
	 */
	generateLocaleSelection(locales, callback) {
		
		// prepare menu dropdown icon
		let title = <FontAwesome name="globe" />;
		
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
	 * @param eventKey the event key that was clicked on
	 */
	handleMenuItemSelect(eventKey) {
		
		// change location
		window.location.href = eventKey;
	}


    /**
     * handleLogoff(event)
     * handle click on logoff button
     *
     * @param  event the event object
     */
    handleLogoff(event) {

        // api call to logout route
        // start loading
        this.props.startLoading('MainMenu.login');

        // fetch data
        let request = new Request(
            '/api/v2/logout',
            {
                method: 'POST',
                mode: 'same-origin',
                cache: 'no-cache',
                credentials: 'same-origin'
            }
        );
        fetch(request)
            .then((response) => {
                if(response.ok !== true) {
                    throw new Error('API.fetch.serverResponseNotOk');
                } else {
                    return response.json();
                }
            })
            .then((json) => {
                if(json.result === 'OK') {
                    // set user
                    this.props.reloadInit(true);
                } else {
                    // add notification
                    this.props.addNotification({
                        type: 'danger',
                        headline: this.t('API.error'),
                        message: this.t(json.data.message)
                    });
                }

                // stop loading
                this.props.stopLoading('MainMenu.login');
            })
            .catch((error) => {
                // add notification
                this.props.addNotification({
                    type: 'danger',
                    headline: this.t('API.error'),
                    message: this.t(error.message)
                });

                // stop loading
                this.props.stopLoading('MainMenu.login');
            });
    }
}
