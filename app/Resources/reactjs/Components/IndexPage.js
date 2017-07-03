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
import {Jumbotron, Image} from 'react-bootstrap';
import {provideTranslations} from 'react-translate-maker';


/**
 * Component for the index page of any route
 */
@provideTranslations
export default class IndexPage extends Component {
	
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
		
		switch(this.props.pageContent) {
			
			case 'homePage':
				return (
					<Jumbotron className="center">
						<h1>JudoIntranet</h1>
						<Image src={require('../assets/ji.png')} className="vh50" responsive />
					</Jumbotron>
				);
				break;
		}
	}
}
