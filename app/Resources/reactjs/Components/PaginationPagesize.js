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
import {Pagination, FormControl, Row, Col} from 'react-bootstrap';
import PropTypes from 'prop-types';


/**
 * Component for the pagination and page size component
 */
class PaginationPagesize extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
	}
	
	
	/**
	 * handleChange(type, value)
	 * eventhandler for changing page or page size
	 * 
	 * @param int value the new value
	 * @param string type page|size
	 */
	handleChange(type, value) {
		
		// check type
		if(type == 'page') {
			this.props.onSelect(value, this.props.pageSize);
		} else {
			this.props.onSelect(1, value.target.value);
		}
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// prepare entries per page
		var entriesPerPage = [5, 10, 25, 50, 100];
		
		return (
			<Row>
				<Col md={1} xs={1}>
					<FormControl
						componentClass="select"
						value={this.props.pageSize}
						onChange={this.handleChange.bind(this, 'pageSize')}
						title={this.context.t('PaginationPagesize.entriesPerPage') +' ('+ this.props.pageSize +') '}
					>
						{entriesPerPage.map((count) => <option key={count} value={count}>{count}</option>)}
					</FormControl>
				</Col>
				<Col md={11} xs={11}>
					<Pagination
						prev
						next
						first
						last
						ellipsis
						boundaryLinks
						items={this.props.pageCount}
						maxButtons={5}
						activePage={this.props.activePage}
						onSelect={this.handleChange.bind(this, 'page')}
						style={{"margin": "0"}}
					/>
				</Col>
			</Row>
		);
	}
}


// set prop types
PaginationPagesize.propTypes = {
	activePage: PropTypes.number.isRequired,
	pageSize: PropTypes.number.isRequired,
	pageCount: PropTypes.number.isRequired,
	onSelect: PropTypes.func.isRequired
};


//set context types
PaginationPagesize.contextTypes = {
	t: PropTypes.func.isRequired
};


// export
export default PaginationPagesize;
