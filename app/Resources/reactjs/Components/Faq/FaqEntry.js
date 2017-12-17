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
import {Panel, Row, Col, Badge, Breadcrumb} from 'react-bootstrap';
import {Link} from 'react-router-dom';
import FontAwesome from 'react-fontawesome';
import Toolbar from '../Toolbar';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import provideContext from '../../provideContext';
import {LinkContainer} from 'react-router-bootstrap';
import FaqFile from './FaqFile';


/**
 * Component for the faq entry component
 */
@provideTranslations
@provideContext
class FaqEntry extends Component {
    
    /**
     * constructor
     */
    constructor(props) {
        
        // parent constructor
        super(props);
        
        // set translation
        this.t = this.props.t;
        
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
        
        // get data
        this.getAjaxData();
        
        // set subtitle
        this.props.handleSetSubtitle('FaqEntry.subtitle', {subject: this.state.data.title});
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
        if(newProps.match.params.id != this.props.match.params.id) {
            this.getAjaxData(newProps.match.params.id);
        }
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
                            pathname: '/faq/listall',
                            onClick: undefined,
                            bsStyle: 'primary',
                            icon: 'arrow-left',
                            iconIsPrefix: true,
                            text: this.t('FaqEntry.toolbar.backToList')
                        },
                        {
                            type: 'link',
                            pathname: '/faq/edit/'+ this.props.match.params.id,
                            onClick: undefined,
                            bsStyle: 'default',
                            icon: 'edit',
                            iconIsPrefix: true,
                            text: this.t('FaqEntry.toolbar.edit'),
                            disabled: !this.state.data.editable
                        },
                        {
                            type: 'callback',
                            pathname: '',
                            onClick: this.handleDelete.bind(this),
                            bsStyle: 'danger',
                            icon: 'remove',
                            iconIsPrefix: true,
                            text: this.t('FaqEntry.toolbar.delete'),
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
        
        // TODO: AJAX calls to get the data
        
        // show loading modal
        this.props.startLoading('FaqEntry.getAjaxData');
        
        // check id
        if(id == 0) {
            id = this.props.match.params.id;
        }
        
        // TODO: AJAX call to get the data
        
        var listItems = require('../../mockFaqEntries.js');
        var item = {};
        // walk through list to the the current item
        for(var key in listItems) {
            if(listItems[key].id == id){
                item = listItems[key];
                break;
            }
        }
        
        // update state
        this.updateState('data', item);
        
        // simulate ajax call and remove loading modal
        setTimeout(() => this.props.stopLoading('FaqEntry.getAjaxData'), 1000);
    }
    
    
    /**
     * handleDelete()
     * eventhandler to handle delete
     * 
     */
    handleDelete() {
        
        console.log('delete: ' + this.props.match.params.id);
    }
    
    
    /**
     * method to render the component
     */
    render() {
        
        // simplify data
        var data = this.state.data;
        
        // prepare header
        var header = (<strong>{data.title}</strong>)
        
        // prepare files
        var files = data.files.map((file, fileId) => <FaqFile key={fileId} data={file} />);
        
        return (
            <div>
                <Toolbar config={this.getToolbarConfig()} />
                <p></p>
                <Breadcrumb>
                    <LinkContainer to={{pathname: "/faq"}}>
                        <Breadcrumb.Item>
                            {this.t('Faq.pageCaption')}
                        </Breadcrumb.Item>
                    </LinkContainer>
                    <LinkContainer to={{pathname: "/faq/listall/" + data.categoryId}}>
                        <Breadcrumb.Item>
                            {data.categoryName}
                        </Breadcrumb.Item>
                    </LinkContainer>
                    <Breadcrumb.Item active>
                        {data.title}
                    </Breadcrumb.Item>
                </Breadcrumb>
                <Row>
                    <Col md={8} xs={12}>
                        <Panel header={header}>
                            {data.content}
                        </Panel>
                    </Col>
                    <Col md={4} xs={12}>
                        <Panel>
                            <h4>{this.t('FaqEntry.files')}</h4>
                            {data.files.length > 0 ? files : '- ' + this.t('FaqEntry.noFiles') + ' -'}
                        </Panel>
                    </Col>
                </Row>
            </div>
        );
    }
}


// export
export default FaqEntry;
