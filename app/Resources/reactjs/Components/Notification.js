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
import {AlertList} from 'react-bs-notifier';
import PropTypes from 'prop-types';


/**
 * Component for the notification component
 */
class Notification extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
				
		return (
			
			<AlertList
				position="top-right"
				alerts={this.props.alerts}
				dismissTitle={this.context.t('Notification.dismissTitle')}
			/>
		);
	}
}


//set context types
Notification.contextTypes = {
	t: PropTypes.func.isRequired
};


//export
export default Notification;
