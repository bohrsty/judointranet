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
import {provideTranslations} from 'react-translate-maker';


/**
 * Component for the notification component
 */
@provideTranslations
class Notification extends Component {
	
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
				
		return (
			
			<AlertList
				position="top-right"
				alerts={this.props.alerts}
				dismissTitle={this.t('Notification.dismissTitle')}
			/>
		);
	}
}


//export
export default Notification;
