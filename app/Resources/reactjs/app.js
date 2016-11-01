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
import React from 'react';
import {render} from 'react-dom';
import {Router, Route, IndexRoute, IndexRedirect, hashHistory} from 'react-router';
// components
import App from './Components/App';
import IndexPage from './Components/IndexPage';
import TodoList from './Components/TodoList';
import TodoListList from './Components/TodoList/TodoListList';
import TodoListForm from './Components/TodoList/TodoListForm';
import TodoListItem from './Components/TodoList/TodoListItem';
// css files
require('bootstrap/dist/css/bootstrap.css');
require('font-awesome/css/font-awesome.css');
require('react-datetime/css/react-datetime.css');
require('./app.css');


// render app to content
render((
	<Router history={hashHistory}>
		<Route path="/" component={App}>
			<IndexRoute component={IndexPage} pageContent="homePage" />
			<Route path="todolist" component={TodoList}>
				<IndexRedirect to="listall" />
				<Route path="listall" component={TodoListList} />
				<Route path="new" component={TodoListForm} form="new" />
				<Route path="edit/:id" component={TodoListForm} form="edit" />
				<Route path="view/:id" component={TodoListItem} />
			</Route>
		</Route>
	</Router>
), document.getElementById('content'));
