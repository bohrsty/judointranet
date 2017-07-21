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
 * collection of legacy methods to migrate users, groups etc. to fos-user-bundle and sonatra-security-bundle
 */
class DbMigrateSecurity extends DbMigrate {
	
	/**
	 * constructor
	 * 
	 * @param Doctrine\DBAL\Connection $connection
	 */
	public function __construct(Connection $connection) {
		
		// parent constructor
		parent::__construct($connection);
	}
	
	
	/**
	 * migrate groups
	 *
	 * @return array
	 */
	public function migrateGroupsUp() {
		
		// get connection and migration
		$connection = $this->connection;
		
		// prepare statement
		$sql = '
            SELECT id, name, parent, valid, last_modified
            FROM `groups`
        ';
		
		// execute query and fetch data
		$groups = $connection->fetchAll($sql);
		if(!$groups) {
			return array('retval' => false, 'result' => $connection->errorInfo());
		}
		
		// prepare statements
		$groupStatement = 'INSERT INTO fos_group (id, name, roles, valid, last_modified) VALUES ';
		$groupGroupsStatement = 'INSERT INTO fos_group_groups (parent_id, child_id) VALUES ';
		
		// walk through groups
		foreach($groups as $group) {
			
			// insert group
			$groupStatement .= '('.$group['id'].', \''.$group['name'].'\', \'a:0:{}\', '.$group['valid'].', \''.$group['last_modified'].'\'),';
			
			// insert parent
			if($group['parent'] > 0) {
				$groupGroupsStatement .= '('.$group['parent'].', '.$group['id'].'),';
			}
		}
		
		// clear , and add to return
		$return = array();
		if($groupStatement != 'INSERT INTO fos_group (id, name, roles, valid, last_modified) VALUES ') {
			$groupStatement = substr($groupStatement, 0, -1);
			$return[] = $groupStatement;
		}
		if($groupGroupsStatement != 'INSERT INTO fos_group_groups (parent_id, child_id) VALUES ') {
			$groupGroupsStatement = substr($groupGroupsStatement, 0, -1);
			$return[] = $groupGroupsStatement;
		}
		
		return array('retval' => true, 'result' => $return);
	}
	
	public function migrateGroupsDown() {
		
		// prepare statement
		$sql = '
			INSERT INTO `groups`
            SELECT fg.id, fg.name, IFNULL(fgg.parent_id, -1) AS parent, fg.valid, 0 AS modified_by, fg.last_modified
            FROM fos_group AS fg
			LEFT JOIN fos_group_groups AS fgg ON fg.id = fgg.child_id
        ';
		
		// add to return
		$return = array();
		$return[] = $sql;
		
		return array('retval' => true, 'result' => $return);
	}
	
	
	/**
	 * migrate users
	 *
	 * @return array
	 */
	public function migrateUsersUp() {
		
		// get connection and migration
		$connection = $this->connection;
		
		// prepare statement
		$sql = '
            SELECT
				id,
				username,
				username AS username_canonical,
				email,
				email AS email_canonical,
				active AS enabled,
				NULL AS salt,
				password,
				NULL AS last_login,
				NULL AS confirmation_token,
				NULL AS password_requested_at,
				\'a:0:{}\' AS roles,
				name,
				last_modified
            FROM `user`
        ';
		
		// execute query and fetch data
		$users = $connection->fetchAll($sql);
		if(!$users) {
			return array('retval' => false, 'result' => $connection->errorInfo());
		}
		
		// prepare statements
		$userStatement = 'INSERT INTO fos_user (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, name, last_modified) VALUES ';
		
		// walk through users
		foreach($users as $user) {
			
			// modify password
			$user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
			
			// modify email if empty string
			if($user['email_canonical'] == '') {
				$email = strtolower(str_replace(' ', '_', $user['name'])).'@dummy.local';
				$user['email'] = $email;
				$user['email_canonical'] = $email;
			}
			
			// insert user
			$userStatement .= '('.
					$user['id'].'
				, \''.$user['username'].'\'
				, \''.$user['username_canonical'].'\'
				, \''.$user['email'].'\'
				, \''.$user['email_canonical'].'\'
				, '.$user['enabled'].'
				, '.(is_null($user['salt']) ? 'NULL' : $user['salt']).'
				, \''.$user['password'].'\'
				, '.(is_null($user['last_login']) ? 'NULL' : $user['last_login']).'
				, '.(is_null($user['confirmation_token']) ? 'NULL' : $user['confirmation_token']).'
				, '.(is_null($user['password_requested_at']) ? 'NULL' : $user['password_requested_at']).'
				, \''.$user['roles'].'\'
				, \''.$user['name'].'\'
				, \''.$user['last_modified'].'\'),';
		}
		
		// clear , and add to return
		$return = array();
		if($userStatement != 'INSERT INTO fos_user (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, name, last_modified) VALUES ') {
			$userStatement = substr($userStatement, 0, -1);
			$return[] = $userStatement;
		}
		
		// migrate user2groups
		$sql = '
			INSERT INTO fos_user_groups
			SELECT user_id, group_id
			FROM user2groups
		';
		
		// add to return
		$return[] = $sql;
		
		return array('retval' => true, 'result' => $return);
	}
	
	public function migrateUsersDown() {
		
		// prepare statement user
		$sql = '
			INSERT INTO `user`
            SELECT id, username_canonical AS username, \'\' AS password, name, IF(RIGHT(email_canonical, 11) = \'dummy.local\', \'\', email_canonical) AS email, enabled AS active, last_modified
            FROM fos_user
        ';
		
		// add to return
		$return = array();
		$return[] = $sql;
		
		// prepare statement user2groups
		$sql = '
			INSERT INTO user2groups
            SELECT user_id, group_id, CURRENT_TIMESTAMP AS last_modified
            FROM fos_user_groups
        ';
		
		// add to return
		$return[] = $sql;
		
		return array('retval' => true, 'result' => $return);
	}
}
