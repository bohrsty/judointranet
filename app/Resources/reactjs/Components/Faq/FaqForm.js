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
import provideContext from '../../provideContext';

/**
 * Component for the faq form component
 */
@provideTranslations
@provideContext
class FaqForm extends Component {
    
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
            categories: [],
            data: {
                title: '',
                content: '',
                category: 0,
                files: []
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
        
        // get categories
        this.getAjaxData('categories');
        
        // check form type
        if(this.form == 'edit') {
            // get data
            this.getAjaxData('data');
            // set subtitle
            this.props.handleSetSubtitle('FaqForm.subtitle.' + this.form, {subject: this.state.data.title});
        }else {
            this.props.handleSetSubtitle('FaqForm.subtitle.' + this.form);
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
        
        // show loading modal
        this.props.startLoading('FaqForm.getAjaxData');
        
        // switch type
        switch(type) {
            
            case 'categories':
                
                var categories = require('../../mockFaqCategory.js');
                var newCategories = [];
                for(var key in categories) {
                    newCategories.push({
                        value: categories[key].id,
                        title: categories[key].title,
                        name: categories[key].title
                    });
                }
                this.updateState('categories', newCategories);
                break;
            
            case 'data':
                
                var listItems = require('../../mockFaqEntries.js');
                var item = {};
                // walk through list to the the current item
                for(var key in listItems) {
                    if(listItems[key].id == this.props.match.params.id){
                        item = listItems[key];
                        item.files = item.files.map((file) => {
                            return {value: '' + file.id, label: file.name + ' (' + file.filename + ')'};
                        });
                        break;
                    }
                }
                
                // set state
                this.updateState('data', item);
                break;
        }
        
        // simulate ajax call and remove loading modal
        setTimeout(() => this.props.stopLoading('FaqForm.getAjaxData'), 1000);
        
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
        this.props.history.push('/faq/view/'+ this.props.match.params.id);
    }
    
    
    /**
     * method to render the component
     */
    render() {
        
        // get categories as options
        var categories = JSON.parse(JSON.stringify(this.state.categories));
        categories.unshift({
            value: 0,
            name: '- ' + this.t('FaqForm.select') + ' -',
            title: this.t('FaqForm.select')
        });
        var options = categories;
        
        // prepare fields
        var fields = [
            {
                name: 'title',
                formControl: 'FieldText',
                placeholder: this.t('FaqForm.title'),
                value: this.state.data.title,
                validate: ['required'],
                label: this.t('FaqForm.title'),
                labelMd: 2,
                labelXs: 12,
                controlMd: 10,
                controlXs: 12,
            },
            {
                name: 'content',
                formControl: 'FieldTextarea',
                placeholder: this.t('FaqForm.content'),
                value: this.state.data.content,
                validate: ['required'],
                label: this.t('FaqForm.content'),
                labelMd: 2,
                labelXs: 12,
                controlMd: 10,
                controlXs: 12,
            },
            {
                name: 'category',
                formControl: 'FieldSelect',
                placeholder: this.t('FaqForm.category'),
                value: this.state.data.categoryId,
                validate: ['required'],
                label: this.t('FaqForm.category'),
                labelMd: 2,
                labelXs: 12,
                controlMd: 10,
                controlXs: 12,
                options: options
            },
            {
                name: 'files',
                formControl: 'FieldAttachment',
                placeholder: this.t('FaqForm.files'),
                value: this.state.data.files,
                validate: [],
                label: this.t('FaqForm.files'),
                labelMd: 2,
                labelXs: 12,
                controlMd: 10,
                controlXs: 12,
                url: '/mockFiles.json'
            }
        ];
        
        return (
            <HorizontalForm
                onSubmit={this.handleSubmit.bind(this)}
                fields={fields}
                cancelButtonLabel={this.t('FaqForm.cancel')}
                saveButtonLabel={this.t('FaqForm.save')}
                buttonMdOffset={2}
                buttonMd={10}
                buttonXs={12}
                history={this.props.history}
            />
        );
    }
}


// export
export default FaqForm;
