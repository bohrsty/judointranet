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
import {HashRouter as Router, Route} from 'react-router-dom';
// app
import App from './Components/App';
// css files
require('bootstrap/dist/css/bootstrap.css');
require('font-awesome/css/font-awesome.css');
require('react-datetime/css/react-datetime.css');
require('react-select/dist/react-select.css');
require('./app.css');


// render app to content
render((
	<Router>
		<Route path="/" component={App} />
	</Router>
), document.getElementById('content'));
