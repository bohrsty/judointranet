<?php

/*
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Migrate;

use Doctrine\DBAL\Connection;

/**
 * collection of legacy methods to migrate existing tables to new tables
 */
class DbMigrate {
	
	/**
	 * connection
	 * the current Doctrine\DBAL\Connection
	 */
	protected $connection;
	
	
	/**
	 * constructor
	 * 
	 * @param Doctrine\DBAL\Connection $connection
	 */
	public function __construct(Connection $connection) {
		
		$this->connection = $connection;
	}
}
