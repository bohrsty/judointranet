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
import {provideTranslations} from 'react-translate-maker';
import PropTypes from 'prop-types';
import provideContext from '../provideContext';


/**
 * the main Component to initialize the app
 */
@provideTranslations
@provideContext
    class AppInit extends Component {

    /**
     * constructor
     */
    constructor(props) {

        // parent constructor
        super(props);

        // simplify translate
        this.t = this.props.t;
    }


    /**
     * componentWillMount()
     * executed directly before component will be mounted to DOM
     */
    componentWillMount() {

        // get user
        this.getUser();
    }


    /**
     * componentWillReceiveProps()
     * executed when component will receive new props
     */
    componentWillReceiveProps(newProps) {

        // check reload prop
        if(this.props.reload !== newProps.reload && newProps.reload === true) {
            this.reloadInit();
        }
    }


    /**
     * getUser()
     * gets the user information from api
     */
    getUser() {

        // start loading
        this.props.startLoading('AppInit.getUser');

        // fetch data
        let request = new Request(
            '/api/v2/user',
            {
                method: 'GET',
                mode: 'same-origin',
                cache: 'no-cache',
                credentials: 'same-origin'
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
                    this.props.setUser(json.data.values);
                } else {
                    // add notification
                    this.props.addNotification({
                        type: 'danger',
                        headline: this.t('API.error'),
                        message: this.t(json.data.message)
                    });
                }

                // stop loading
                this.props.stopLoading('AppInit.getUser');
            })
            .catch((error) => {

                // add notification
                this.props.addNotification({
                    type: 'danger',
                    headline: this.t('API.error'),
                    message: this.t(error.message)
                });

                // stop loading
                this.props.stopLoading('AppInit.getUser');
            });
    }


    /**
     * reloadInit()
     * reloads all init information
     */
    reloadInit() {

        // reset reload
        this.props.reloadInit(false);

        // load user
        this.getUser();
    }


    /**
     * method to render the component
     */
    render() {

        return (
            null
        );
    }
}


// set prop types
AppInit.propTypes = {
    setConfig: PropTypes.func.isRequired,
    setUser: PropTypes.func.isRequired,
    reload: PropTypes.bool.isRequired
};


//export
export default AppInit;
