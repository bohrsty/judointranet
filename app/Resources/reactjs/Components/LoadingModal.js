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


/**
 * Component for the modal loading indicator
 */
class LoadingModal extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get translation method
		this.t = this.context.t;
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		return (
			<div className="modal-container">
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
				{this.props.children}
			</div>
		);
	}
}


// set prop types
LoadingModal.propTypes = {
	show: PropTypes.bool.isRequired
};


//set context types
LoadingModal.contextTypes = {
	t: PropTypes.func.isRequired
};


// export
export default LoadingModal;
