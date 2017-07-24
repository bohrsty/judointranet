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
    Panel,
    Row,
    Col,
    Badge,
    Breadcrumb,
    OverlayTrigger,
    Popover,
    Thumbnail
} from 'react-bootstrap';
import {Link} from 'react-router-dom';
import FontAwesome from 'react-fontawesome';
import PropTypes from 'prop-types';
import {provideTranslations} from 'react-translate-maker';
import {LinkContainer} from 'react-router-bootstrap';


/**
 * Component for the faq file component
 */
@provideTranslations
class FaqFile extends Component {
    
    /**
     * constructor
     */
    constructor(props) {
        
        // parent constructor
        super(props);
        
        // set translation
        this.t = this.props.t;
    }
    
    
    /**
     * returns the icon name according to the mime type
     */
    getIconByMimetype() {
        
        // prepare icons
        var icons = {};
        // add mime types
        icons['application/pdf'] = 'file-pdf-o';
        icons['application\/x-pdf'] = 'file-pdf-o';
        icons['text/plain'] = 'file-text-o';
        icons['video/x-msvideo'] = 'file-video-o';
        icons['video/msvideo'] = 'file-video-o';
        icons['video/avi'] = 'file-video-o';
        icons['application/x-troff-msvideo'] = 'file-video-o';
        icons['application/msword'] = 'file-word-o';
        icons['application/vnd.ms-office'] = 'file-word-o';
        icons['application/vnd.openxmlformats-officedocument.wordprocessingml.document'] = 'file-word-o';
        icons['application/msword'] = 'file-word-o';
        icons['image/gif'] = 'file-image-o';
        icons['image/jpeg'] = 'file-image-o';
        icons['audio/mpeg'] = 'file-audio-o';
        icons['audio/mpg'] = 'file-audio-o';
        icons['audio/mpeg3'] = 'file-audio-o';
        icons['audio/mp3'] = 'file-audio-o';
        icons['video/mp4'] = 'file-video-o';
        icons['video/mpeg'] = 'file-video-o';
        icons['image/png'] = 'file-image-o';
        icons['image/x-png'] = 'file-image-o';
        icons['application/powerpoint'] = 'file-powerpoint-o';
        icons['application/vnd.ms-powerpoint'] = 'file-powerpoint-o';
        icons['application/vnd.openxmlformats-officedocument.presentationml.presentation'] = 'file-powerpoint-o';
        icons['audio/x-wav'] = 'file-audio-o';
        icons['audio/wave'] = 'file-audio-o';
        icons['audio/wav'] = 'file-audio-o';
        icons['application/vnd.ms-excel'] = 'file-excel-o';
        icons['application/msexcel'] = 'file-excel-o';
        icons['application/x-msexcel'] = 'file-excel-o';
        icons['application/x-ms-excel'] = 'file-excel-o';
        icons['application/x-excel'] = 'file-excel-o';
        icons['application/x-dos_ms_excel'] = 'file-excel-o';
        icons['application/xls'] = 'file-excel-o';
        icons['application/x-xls'] = 'file-excel-o';
        icons['application/excel'] = 'file-excel-o';
        icons['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'] = 'file-excel-o';
        icons['application/x-zip'] = 'file-zip-o';
        icons['application/zip'] = 'file-zip-o';
        icons['application/x-zip-compressed'] = 'file-zip-o';
        icons['application/s-compressed'] = 'file-zip-o';
        icons['multipart/x-zip'] = 'file-zip-o';
        icons['application/vnd.oasis.opendocument.text'] = 'file-word-o';
        icons['application/vnd.oasis.opendocument.spreadsheet'] = 'file-excel-o';
        icons['application/vnd.oasis.opendocument.presentation'] = 'file-powerpoint-o';
        
        // return icon by mime type if exists or common icon
        if(icons[this.props.data.mimetype] !== undefined) {
            return icons[this.props.data.mimetype];
        } else {
            return 'file-o';
        }
    }
    
    
    /**
     * handleDownload(e)
     * eventhandler to handle click on download button
     * 
     * @param object e the event object
     */
    handleDownload(e) {
        
        // interupt link
        e.preventDefault()
        
        // handle download
        console.log('download: ' + this.props.data.url);
    }
    
    
    /**
     * method to render the component
     */
    render() {
        
        // simplify data
        var data = this.props.data;
        
        // prepare icon
        var icon = <FontAwesome name={this.getIconByMimetype()} />
        
        // prepare thumbnail overlay
        var fileName = <span>{icon} <strong>{data.name}</strong></span>;
        var overlayTrigger = null;
        if(data.thumbnailUrl !== undefined && data.thumbnailUrl != '') {
            
            // prepare thumbnail popover
            var thumbnail = (
                <Popover id={data.id}>
                    <Thumbnail
                        src={data.thumbnailUrl}
                        alt={data.filename}
                    >
                        <br />
                        {data.filename}
                    </Thumbnail>
                </Popover>
            );
            // prepare trigger
            overlayTrigger = (
                    <OverlayTrigger
                    trigger={['hover', 'focus', 'click']}
                    placement='left'
                    rootClose
                    overlay={thumbnail}
                >
                    {fileName}
                </OverlayTrigger>
            );
        } else {
            overlayTrigger = fileName;
        }
        
        return (
            <Panel>
                {overlayTrigger}
                <br />
                <a href={data.url} onClick={this.handleDownload.bind(this)}><FontAwesome name='download' /></a> {data.filesize} - {data.filename}
            </Panel>
        );
    }
}


//set props types
FaqFile.propTypes = {
    data: PropTypes.object.isRequired
};


// export
export default FaqFile;
