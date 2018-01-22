<?php

/*
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Helper;

class ApiV2ResponseHelper {
    
    /**
     * API version
     * @staticvar VERSION
     */
    const VERSION = 'v2';
    
    
    /**
     * getApiResponse($data, $isError)
     * embeds the api data into api response
     *
     * @param mixed $data the data to embed (array or string)
     * @param string $uri the current uri of the request
     * @param bool $isError if true error response, result otherwise
     * @return array the response for JSON response
     */
    public static function getApiResponse($data, $uri, $isError = false) {
        
        // check error
        if($isError === true) {
            
            // prepare error response
            $result = 'ERROR';
            $message = $data;
            $values = array();
        } else {
            
            // prepare data response
            $result = 'OK';
            $message = '';
            $values = $data;
        }
        
        // return
        return array(
            'result' => $result,
            'version' => self::VERSION,
            'uri' => $uri,
            'data' => array(
                'message' => $message,
                'values' => $values,
            ),
        );
    }
}