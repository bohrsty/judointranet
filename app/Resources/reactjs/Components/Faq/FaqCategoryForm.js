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
import moment from 'moment';
import HorizontalForm from '../HorizontalForm';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';

/**
 * Component for the faq category form component
 */
@provideTranslations
class FaqCategoryForm extends Component {
    
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
            data: {
                title: '',
                description: ''
            }
        }
    }
    
    
    /**
     * componentWillMount()
     * executed directly before component will be mounted to DOM
     */
    componentWillMount() {
        
        // set form (new|edit)
        this.form = this.props.form;
        
        // set subtitle
        this.props.handleSetSubtitle('FaqCategoryForm.subtitle.' + this.form);
        
        // get data if edit
        if(this.form == 'edit') {
            this.getAjaxData('data');
        }
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
     * getAjaxData(type)
     * retrieves the data from api according to type
     * 
     * @param string type the type of data to get from api
     */
    getAjaxData(type) {
        
        // TODO: AJAX calls to get the data
        
        // switch type
        switch(type) {
            
            case 'data':
                
                var listItems = require('../../mockFaqCategory.js');
                var item = {};
                // walk through list to the the current item
                for(var key in listItems) {
                    if(listItems[key].id == this.props.match.params.id){
                        item.title = listItems[key].title;
                        item.description = listItems[key].description;
                        break;
                    }
                }
                
                // set state
                this.updateState('data', item);
                break;
        }
        
    }
    
    
    /**
     * handleSubmit(form)
     * eventhandler to save the changes and return to list
     * 
     * @param object form the form data
     */
    handleSubmit(form) {
        
        // save todo
        console.dir(form);
        // return to list
        this.props.history.push('/faq/category/'+ this.props.match.params.id);
    }
    
    
    /**
     * method to render the component
     */
    render() {
        
        // prepare fields
        var fields = [
            {
                name: 'title',
                formControl: 'FieldText',
                placeholder: this.t('FaqCategoryForm.title'),
                value: this.state.data.title,
                validate: ['required'],
                label: this.t('FaqCategoryForm.title'),
                labelMd: 2,
                labelXs: 12,
                controlMd: 10,
                controlXs: 12,
            },
            {
                name: 'description',
                formControl: 'FieldTextarea',
                placeholder: this.t('FaqCategoryForm.description'),
                value: this.state.data.description,
                validate: ['required'],
                label: this.t('FaqCategoryForm.description'),
                labelMd: 2,
                labelXs: 12,
                controlMd: 10,
                controlXs: 12,
            }
        ];
        
        return (
            <HorizontalForm
                onSubmit={this.handleSubmit.bind(this)}
                fields={fields}
                cancelButtonLabel={this.t('FaqCategoryForm.cancel')}
                saveButtonLabel={this.t('FaqCategoryForm.save')}
                buttonMdOffset={2}
                buttonMd={10}
                buttonXs={12}
                history={this.props.history}
            />
        );
    }
}


// export
export default FaqCategoryForm;
