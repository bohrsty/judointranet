<?php

/*
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet;

use Doctrine\DBAL\Connection;

/**
 * collection of legacy methods to work with the old code during migration
 */
class Legacy {

    /**
    * checks if the global software version is greater than 2.0.0, previous versions
    * had format "rXXX"
    *
    * @param Doctrine\DBAL\Connection $connection the database connection
    * @return boolean
    */
    public static function isMigrationUsable(Connection $connection) {
		
    	// get schema manager
    	$schemaManager = $connection->getSchemaManager();
    	
    	// check if table config exists
    	if(!$schemaManager->tablesExist(array('config'))) {
    		return true;
    	}
    	
        // prepare statement
        $sql = '
            SELECT `value`
            FROM `config`
            WHERE `name`=\'global.version\'
        ';

        // execute query and fetch data
        $globalVersion = $connection->fetchAssoc($sql);
		
        // check version format
        $versionMatches = array();
        $hasMatch = \preg_match('/(\d)+\.(\d)+\.(\d)+/', $globalVersion['value'], $versionMatches);

        if($hasMatch === 1) {

            // check version >= 2.0.0
            if((int)$versionMatches[1] >= 2) {
                return true;
            }
        }
        
        return false;
    }
}
