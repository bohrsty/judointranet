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
import {Router, Route, IndexRoute, hashHistory} from 'react-router';
import App from './Components/App';
import IndexPage from './Components/IndexPage';
require('bootstrap/dist/css/bootstrap.css');
require('font-awesome/css/font-awesome.css');
require('./app.css');



// render app to content
render((
	<Router history={hashHistory}>
		<Route path="/" component={App}>
			<IndexRoute component={IndexPage} pageContent="homePage" />
		</Route>
	</Router>
), document.getElementById('content'));
