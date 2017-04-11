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
    * checks if the global software version is greater than 2.0.0, previous verions
    * had format "rXXX"
    *
    * @param Doctrine\DBAL\Connection $connection the database connection
    * @return boolean
    */
    public static function isMigrationUsable(Connection $connection) {

        // prepare statement
        $sql = '
            SELECT `value`
            FROM `config`
            WHERE `name`=\'global.version\'
        ';

        // execute query and fetch data
        $globalVersion = $connection->fetchAssoc($sql);
		// if "global.version" does not exist, > 2.1.0 
		if($globalVersion === false) {
			return true;
		}
		
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
