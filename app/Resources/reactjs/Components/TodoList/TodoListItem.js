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
import {Panel, Row, Col, Badge} from 'react-bootstrap';
import {Link} from 'react-router-dom';
import FontAwesome from 'react-fontawesome';
import EditablePopover from '../EditablePopover';
import Toolbar from '../Toolbar';
import TodoListSubitemList from './TodoListSubitemList';


/**
 * Component for the todo list item component
 */
class TodoListItem extends Component {
	
	/**
	 * constructor
	 */
	constructor(props) {
		
		// parent constructor
		super(props);
		
		// set initial state
		this.state = {
			data: {},
			finished: false
		}
	}
	
	
	/**
	 * componentWillMount()
	 * executed directly before component will be mounted to DOM
	 */
	componentWillMount() {
		
		// get translation method
		this.t = this.context.t;
		
		// set subtitle
		this.props.handleSetSubtitle('TodoListItem.subtitle');
		
		// get data
		this.getAjaxData();
	}
	
	
	/**
	 * updates given parts of the state
	 * 
	 * @param state the state name to be updated
	 * @param value the value for state
	 */
	updateState(state, value) {
		
		var currentState = this.state;
		
		currentState[state] = value;
		this.setState(currentState);
	}
	
	
	/**
	 * componentWillReceiveProps(newProps)
	 * executed directly before component will receive new props
	 */
	componentWillReceiveProps(newProps) {
		
		// get data
		this.getAjaxData(newProps.match.params.id);
	}
	
	
	/**
	 * getToolbarConfig()
	 * returns the toolbar configuration
	 */
	getToolbarConfig() {
		
		return {
			bsSize: 'default',
			search: false,
			groups: [
			 	{
			 		buttons: [
			 			{
			 				type: 'link',
							pathname: '/todolist/listall',
							onClick: undefined,
			 				bsStyle: 'primary',
			 				icon: 'arrow-left',
			 				iconIsPrefix: true,
			 				text: this.t('TodoListItem.toolbar.backToList')
			 			},
			 			{
			 				type: 'link',
							pathname: '/todolist/new',
							onClick: undefined,
			 				bsStyle: 'success',
			 				icon: 'plus',
			 				iconIsPrefix: true,
			 				text: this.t('TodoListItem.toolbar.new')
			 			},
			 			{
			 				type: 'link',
							pathname: '/todolist/edit/'+ this.props.match.params.id,
							onClick: undefined,
			 				bsStyle: 'default',
			 				icon: 'edit',
			 				iconIsPrefix: true,
			 				text: this.t('TodoListItem.toolbar.edit'),
			 				disabled: !this.state.data.editable
			 			},
			 			{
			 				type: 'callback',
							pathname: '',
							onClick: this.handleFinish.bind(this),
			 				bsStyle: 'default',
			 				icon: (this.state.data.finished ? "square-o" : "check-square-o"),
			 				iconIsPrefix: true,
			 				text: (this.state.data.finished ? this.t('TodoListItem.toolbar.unfinish') : this.t('TodoListItem.toolbar.finish')),
			 				disabled: !this.state.data.finishable
			 			},
			 			{
			 				type: 'link',
							pathname: '/todolist/delete/'+ this.props.match.params.id,
							onClick: undefined,
			 				bsStyle: 'danger',
			 				icon: 'remove',
			 				iconIsPrefix: true,
			 				text: this.t('TodoListItem.toolbar.delete'),
			 				disabled: !this.state.data.deletable
			 			}
			 		]
				}
			]
		};
	}
	
	
	/**
	 * getAjaxData(id)
	 * retrieves the data from api
	 * 
	 * @param int id the id to get the data for
	 */
	getAjaxData(id = 0) {
		
		// check id
		if(id == 0) {
			id = this.props.match.params.id;
		}
		
		// TODO: AJAX call to get the data
		
		var listItems = require('../../mockTodolist.js');
		var item = {};
		// walk through list to the the current item
		itemBlock: {
			for(var key in listItems) {
				if(listItems[key].id == id){
					item = listItems[key];
					break;
				} else {
					if(listItems[key].subitems != undefined) {
						for(var i in listItems[key].subitems) {
							if(listItems[key].subitems[i].id == id) {
								item = listItems[key].subitems[i];
								break itemBlock;
							}
						}
					}
				}
			}
		}
		
		// update state
		this.updateState('data', item);
	}
	
	
	/**
	 * handleFinish(e)
	 * eventhandler to handle finish an item preventing the default event
	 * 
	 * @param object e the event object
	 */
	handleFinish(e) {
		
		// prevent jump to link target
		e.preventDefault();
		console.log('finish: '+ this.props.match.params.id);
	}
	
	
	/**
	 * handleEditComment(id, content)
	 * eventhandler to handle edit comment
	 * 
	 * @param int id the id of the edited comment
	 * @param string content the new content of the edited comment
	 */
	handleEditComment(id, content) {
		
		console.log(id +': '+ content);
	}
	
	
	/**
	 * method to render the component
	 */
	render() {
		
		// simplify data
		var data = this.state.data;
		
		// prepare comment button
		var comment = (
			<Link to=""><FontAwesome name="comment" title={this.t('TodoListItem.comment')} /></Link>
		);
		
		// prepare header
		var header = (
			<Row>
				<Col md={2} xs={4}>
					<h4>
						<EditablePopover
							id={data.id}
							trigger={comment}
							content={data.comment || ''}
							emptyTitle={this.t('TodoListItem.doubleclickToAddComment')}
							title={this.t('TodoListItem.doubleclickToEdit')}
							emptyContent={this.t('TodoListItem.noComment')}
							handleSave={this.handleEditComment.bind(this)}
						/>
						{' '}
						<Badge title={data.subitems == undefined ? 0 : data.subitems.length +' '+ this.t('TodoListItem.subitems')}>{data.subitems == undefined ? 0 : data.subitems.length}</Badge>
					</h4>
				</Col>
				<Col md={10} xs={8}>
					<h4>{data.title}</h4>
				</Col>
			</Row>
		);
		
		// prepare footer
		var footer = (
			<div>
				<Row>
					<Col md={6} xs={12} className="small">{this.t('TodoListItem.created') +': '+ data.created}</Col>
					<Col md={6} xs={12} className="small">{this.t('TodoListItem.owner') +': '+ data.owner.name +' ('+ data.owner.username +')'}</Col>
				</Row>
				<Row>
					<Col md={6} xs={12} className="small">{this.t('TodoListItem.due') +': '+ data.due}</Col>
					<Col md={6} xs={12} className="small">{this.t('TodoListItem.assignedTo') +': '+ data.assignedTo.name +' ('+ data.assignedTo.username +')'}</Col>
				</Row>
			</div>
		);
		
		return (
			<div>
				<Toolbar config={this.getToolbarConfig()} />
				<p></p>
				<Panel header={header} footer={footer}>
					<Row>
						<Col md={6} xs={12}>{data.text}</Col>
						<Col md={6} xs={12}>
							<h4>SubItems</h4>
							<TodoListSubitemList data={data.subitems || []} />
						</Col>
					</Row>
				</Panel>
			</div>
		);
	}
}


//set context types
TodoListItem.contextTypes = {
	t: React.PropTypes.func.isRequired
};


// export
export default TodoListItem;
