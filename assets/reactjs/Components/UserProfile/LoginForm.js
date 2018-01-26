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
import {
    Jumbotron,
    FormControl,
    Row,
    Col,
    Button,
    Well
} from 'react-bootstrap';
import {provideTranslations} from 'react-translate-maker';
import PropTypes from 'prop-types';
import provideContext from '../../provideContext';

/**
 * the main Component to show the login form
 */
@provideTranslations
@provideContext
class LoginForm extends Component {

    /**
     * reference for password field
     */
    refPassword = {};

    /**
     * constructor
     */
    constructor(props) {

        // parent constructor
        super(props);

        // set initial state
        this.state = {
            username: '',
            password: '',
            loginError: ''
        };

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
     * handleChangeUsername(e)
     * event handler for username
     *
     * @param e the event object
     */
    handleChangeUsername(e) {
        this.setState({username: e.target.value});
    }


    /**
     * handleChangePassword(e)
     * event handler for password
     *
     * @param e the event object
     */
    handleChangePassword(e) {
        this.setState({password: e.target.value});
    }


    /**
     * handlePasswordKeyPress(e)
     * event handler for <enter> key in password field
     *
     * @param e the event object
     */
    handlePasswordKeyPress(e) {
        // check key
        if(e.charCode === 13) {
            this.handleLogin();
        }
    }


    /**
     * handleUsernameKeyPress(e)
     * event handler for <enter> key in username field
     *
     * @param e the event object
     */
    handleUsernameKeyPress(e) {
        // check key
        if(e.charCode === 13) {
            this.refPassword.focus();
        }
    }


    /**
     * handleLogin(e)
     * event handler to login
     *
     * @param e the event object
     */
    handleLogin(e = {}) {

        // start loading
        this.props.startLoading('LoginForm.login');

        // prepare data
        let data = new FormData();
        data.append('_username', this.state.username);
        data.append('_password', this.state.password);

        // fetch data
        let request = new Request(
            '/api/v2/login',
            {
                method: 'POST',
                mode: 'same-origin',
                cache: 'no-cache',
                credentials: 'same-origin',
                body: data
            }
        );
        fetch(request)
            .then((response) => {
                if(response.ok !== true) {
                    throw new Error('API.fetch.serverResponseNotOk');
                } else {
                    return response.json();
                }
            })
            .then((json) => {
                if(json.result === 'OK') {
                    // set user
                    this.props.reloadInit(true);
                } else {
                    // add notification
                    this.setState({loginError: this.t(json.data.message)});
                }

                // stop loading
                this.props.stopLoading('LoginForm.login');
            })
            .catch((error) => {

                // add notification
                this.setState({loginError: this.t(error.message)});

                // stop loading
                this.props.stopLoading('LoginForm.login');
            });
    }


    /**
     * method to render the component
     */
    render() {

        // set title
        document.title = 'JudoIntranet - ' + this.t('LoginForm.login');

        return (
            <Jumbotron>
                {this.state.loginError !== ''
                    ? (<div>
                            <Row>
                                <Col lgOffset={3} lg={6} sm={12}>
                                    <Well className="bsStyle-danger">{this.state.loginError}</Well>
                                </Col>
                            </Row>
                            < Row className = "empty-row" />
                        </div>)
                    : null
                }
                <Row>
                    <Col lgOffset={4} lg={4} sm={12}>
                        <FormControl
                            type="text"
                            value={this.state.username}
                            placeholder={this.t('LoginForm.username')}
                            onChange={this.handleChangeUsername.bind(this)}
                            onKeyPress={this.handleUsernameKeyPress.bind(this)}
                            autoFocus={true}
                        />
                    </Col>
                </Row>
                <Row className="empty-row" />
                <Row>
                    <Col lgOffset={4} lg={4} sm={12}>
                        <FormControl
                            type="password"
                            value={this.state.password}
                            placeholder={this.t('LoginForm.password')}
                            onChange={this.handleChangePassword.bind(this)}
                            onKeyPress={this.handlePasswordKeyPress.bind(this)}
                            inputRef={(component) => this.refPassword = component}
                        />
                    </Col>
                </Row>
                <Row className="empty-row" />
                <Row>
                    <Col lgOffset={4} lg={4} sm={12}>
                        <Button
                            bsStyle="primary"
                            onClick={this.handleLogin.bind(this)}
                        >
                            {this.t('LoginForm.login')}
                        </Button>
                    </Col>
                </Row>
            </Jumbotron>
        );
    }
}


//export
export default LoginForm;
