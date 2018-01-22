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
import {Modal} from 'react-bootstrap';
import FontAwesome from 'react-fontawesome';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';


/**
 * Component for the modal loading indicator
 */
@provideTranslations
class LoadingModal extends Component {
	
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
			<Modal
				show={this.props.show}
				animation={false}
				container={this}
				backdrop="static"
			>
				<Modal.Body>
					<FontAwesome size="2x" name="spinner" spin />{' ...'}{this.t('LoadingModal.loadingData')}
				</Modal.Body>
			</Modal>
		);
	}
}


// set prop types
LoadingModal.propTypes = {
	show: PropTypes.bool.isRequired
};


// export
export default LoadingModal;
