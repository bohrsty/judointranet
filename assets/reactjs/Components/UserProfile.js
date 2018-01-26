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
import {Route, Switch} from 'react-router-dom';
import {provideTranslations} from 'react-translate-maker';
import PropTypes from 'prop-types';
import provideContext from '../provideContext';
import LoginForm from './UserProfile/LoginForm';


/**
 * the main Component to layout the user profile
 */
@provideTranslations
@provideContext
class UserProfile extends Component {

    /**
     * constructor
     */
    constructor(props) {

        // parent constructor
        super(props);

        // set initial state
        this.state = {};

        // get translation method
        this.t = this.props.t;
    }


    /**
     * componentWillMount()
     * executed directly before component will be mounted to DOM
     */
    componentWillMount() {

    }


    /**
     * method to render the component
     */
    render() {

        // set title
        document.title = 'JudoIntranet - ' + this.t('UserProfile.title');

        return (
            this.props.user.loggedIn === true
                ? null
                : <LoginForm />
        );
    }
}


//export
export default UserProfile;
