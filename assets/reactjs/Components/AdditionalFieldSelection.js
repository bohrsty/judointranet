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
import {
    FormGroup,
    Col,
    ControlLabel,
    FormControl,
    Well,
    Row,
    Panel
} from 'react-bootstrap';
import Field from './Field';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import provideContext from '../provideContext';

/**
 * Component for the additional field selection component
 */
@provideTranslations
@provideContext
class AdditionalFieldSelection extends Component {
    
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
            fields: [],
            selection: '',
            selections: [],
            showFields: false
        };
        
        // prepare field objects
        this.fields = {};
    }
    
    
    /**
     * componentWillMount()
     * executed directly before component will be mounted to DOM
     */
    componentWillMount() {
        
        // get data if edit
        this.getAjaxData();
    }
    
    
    /**
     * componentWillReceiveProps(newProps)
     * executed if the component receive new props
     * 
     * @param newProps the new set of props
     */
    componentWillReceiveProps(newProps) {
        
        // check value
        if(this.props.data.value != newProps.data.value) {
            
            // set selection
            this.updateState('selection', newProps.data.value);
            // update fields
            this.getAjaxData();
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
        
        // check if state exists
        currentState[state] = value;
        this.setState(currentState);
    }
    
    
    /**
     * addField(field)
     * adds the field reference to this.fields
     * 
     * @param object field the field reference
     * @param string fieldName the name of the field
     */
    addField(field, fieldName) {
        this.fields[fieldName] = field;
    }
    
    
    /**
     * getData()
     * returns an object containing the validation state and the value of the field
     */
    getData() {
        
        // prepare return data
        var valid = true;
        var value = {};
        
        // walk through fields
        for(var key in this.fields) {
            
            // get data from field
            var fieldData = this.field[key].getData();
            
            // add to return
            valid &= fieldData.valid;
            value[key] = fieldData.value;
        }
        
        // return
        return {
            valid: valid,
            value: value
        };
    }
    
    
    /**
     * getAjaxData()
     * retrieves the fields from api according to selection
     */
    getAjaxData() {
        
        // TODO: AJAX calls to get the data
        
        // show loading modal
        this.props.startLoading('AdditionalFieldSelection.getAjaxData');
        
        var listItems = require('../mockAdditionalFields.js');
        var item = {};
        var selections = [];
        // walk through list to the the current item
        for(var key in listItems) {
            if(listItems[key].id == this.state.selection){
                item = listItems[key];
            }
            selections.push(
                {
                    value: listItems[key].id,
                    label: listItems[key].label
                }
            );
        }
        
        // add sizes from this component props
        var fields = [];
        for(var key in item.fields) {
            var field = item.fields[key];
            field.labelMd = this.props.data.labelMd || 2;
            field.labelXs = this.props.data.labelXs || 12;
            field.controlMd = this.props.data.controlMd || 10;
            field.controlXs = this.props.data.controlXs || 12;
            fields.push(field);
        }
        
        // simulate ajax call and remove loading modal
        setTimeout(() => {
            // update selections and list
            this.updateState('selections', selections);
            this.updateState('fields', fields);
            // enable fields
            this.updateState('showFields', true);
            // hide loading modal
            this.props.stopLoading('AdditionalFieldSelection.getAjaxData')
        }, 1000);
        
    }
    
    
    /**
     * handleSelect(e)
     * event handler for selecting the preselection
     * 
     * @param object e the event object
     */
    handleSelect(e) {
        
        // disable fields
        this.updateState('showFields', false);
        
        // set selection and get fields
        this.updateState('selection', e.target.value);
        this.getAjaxData();
    }
    
    
    /**
     * method to render the component
     */
    render() {
        
        // simplify props
        var data = this.props.data;
        
        // prepare fields
        var fields = this.state.fields.map((field, fieldId) => <Field data={field} key={fieldId} ref={(ref) => this.addField(ref, field.name)} />);
        
        return (
            <Panel>
                <FormGroup controlId={data.name} >
                    <Col componentClass={ControlLabel} md={data.labelMd || 2} xs={data.labelXs || 12}>
                        {data.label}
                    </Col>
                    <Col md={data.controlMd || 10} xs={data.controlXs || 12}>
                        <FormControl
                            componentClass="select"
                            value={this.state.selection}
                            onChange={this.handleSelect.bind(this)}
                        >
                            <option key={0} value="">- {this.t('AdditionalFieldSelection.pleaseSelect')} -</option>
                            {this.state.selections.map((option, optionId) => <option key={optionId} value={option.value}>{option.label}</option>)}
                        </FormControl>
                    </Col>
                </FormGroup>
                {this.state.showFields === true ? fields : null}
            </Panel>
        );
    }
}


// set props types
AdditionalFieldSelection.propTypes = {
    data: PropTypes.object.isRequired
};


// export
export default AdditionalFieldSelection;
