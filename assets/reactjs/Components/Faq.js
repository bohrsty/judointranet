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
import {Route, Switch, Redirect} from 'react-router-dom';
import {PageHeader} from 'react-bootstrap';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import FaqCategory from './Faq/FaqCategory';
import FaqEntry from './Faq/FaqEntry';
import FaqForm from './Faq/FaqForm';
import FaqCategoryForm from './Faq/FaqCategoryForm';


/**
 * Component for the faq index page
 */
@provideTranslations
export class Faq extends Component {
	
	// context
	static contextTypes = {
		t: PropTypes.func.isRequired
	};
	
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// get translation method
		this.t = this.props.t;
		
		// set initial state
		this.state = {
			pageHeader: 'Faq.pageCaption',
			pageHeaderSmall: ''
		};
	}
	
	
	/**
	 * updates given parts of the state
	 * 
	 * @param state the state name to be updated
	 * @param value the value for state
	 */
	updateState(state, value) {
		
		var currentState = this.state;
		
		// check if state exists
		if(this.state[state] != undefined) {
			currentState[state] = value;
			this.setState(currentState);
		}
	}
	
	
	/**
	 * handleSetSubtitle(subtitle, translationArgs)
	 * eventhandler to handle the change of the subtitle
	 * 
	 * @param string subtitle the new subtitle
	 * @param object translationArgs arguments for the translation engine
	 */
	handleSetSubtitle(subtitle, translationArgs = undefined) {
		
	    // prepare header
	    var pageHeaderSmall = {
            title: subtitle,
            args: translationArgs
	    };
	    
		// update the state with the new subtitle
		this.updateState('pageHeaderSmall', pageHeaderSmall);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// set title
		document.title = 'JudoIntranet - ' + this.t(this.state.pageHeader);
		
		return (
			<div className="container">
				<PageHeader>
					{this.t(this.state.pageHeader)}{' '}
					<small>
						{this.t(this.state.pageHeaderSmall.title, this.state.pageHeaderSmall.args)}
					</small>
				</PageHeader>
				<Switch>
					<Route exact path={this.props.match.url} render={() => <Redirect to={this.props.match.url + '/listall'} />} />
					<Route path={this.props.match.url + '/listall/:categoryId?'} children={({match, history}) =>
						<FaqCategory
							handleSetSubtitle={this.handleSetSubtitle.bind(this)}
							match={match}
							history={history}
						/>}
					/>
                    <Route path={this.props.match.url + '/category/new'} children={({match, history}) => 
                        <FaqCategoryForm 
                            form="new"
                            handleSetSubtitle={this.handleSetSubtitle.bind(this)}
                            match={match}
                            history={history}
                        />}
                    />
                    <Route path={this.props.match.url + '/category/edit/:id'} children={({match, history}) => 
                        <FaqCategoryForm 
                            form="edit"
                            handleSetSubtitle={this.handleSetSubtitle.bind(this)}
                            match={match}
                            history={history}
                        />}
                    />
					<Route path={this.props.match.url + '/new'} children={({match, history}) => 
						<FaqForm 
							form="new"
							handleSetSubtitle={this.handleSetSubtitle.bind(this)}
							match={match}
							history={history}
						/>}
					/>
					<Route path={this.props.match.url + '/edit/:id'} children={({match, history}) => 
						<FaqForm 
							form="edit"
							handleSetSubtitle={this.handleSetSubtitle.bind(this)}
							match={match}
							history={history}
						/>}
					/>
					<Route path={this.props.match.url + '/view/:id'} children={({match, history}) =>
						<FaqEntry
							handleSetSubtitle={this.handleSetSubtitle.bind(this)}
							match={match}
							history={history}
						/>}
					/>
				</Switch>
			</div>
		);
	}
}


//export
export default Faq;
