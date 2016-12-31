<?php
/* ********************************************************************************************
 * Copyright (c) 2011 Nils Bohrs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 * 
 * Thirdparty licenses see LICENSE
 * 
 * ********************************************************************************************/


// secure against direct execution
if(!defined("JUDOINTRANET")) {die("Cannot be executed directly! Please use index.php.");}

/**
 * class Navi implements the properties of a navigation subtree
 */
class Navi extends PageView {
	
	/*
	 * class-variables
	 */
	private $id;
	private $name;
	private $parent;
	private $fileParam;
	private $position;
	private $show;
	private $valid;
	private $requiredPermission;
	private $subItems;
	
	/*
	 * getter/setter
	 */
	public function getId(){
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getName(){
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
	}
	public function getParent(){
		return $this->parent;
	}
	public function setParent($parent) {
		$this->parent = $parent;
	}
	public function getFileParam(){
		return $this->fileParam;
	}
	public function setFileParam($fileParam) {
		$this->fileParam = $fileParam;
	}
	public function getPosition(){
		return $this->position;
	}
	public function setPosition($position) {
		$this->position = $position;
	}
	public function getShow(){
		return $this->show;
	}
	public function setShow($show) {
		$this->show = $show;
	}
	public function getValid(){
		return $this->valid;
	}
	public function setValid($valid) {
		$this->valid = $valid;
	}
	public function getRequiredPermission(){
		return $this->requiredPermission;
	}
	public function setRequiredPermission($requiredPermission) {
		$this->requiredPermission = $requiredPermission;
	}
	public function getSubItems(){
		return $this->subItems;
	}
	public function setSubItems($subItems) {
		$this->subItems = $subItems;
	}
	
	
	
	/*
	 * constructor/destructor
	 */
	public function __construct($id) {
		
		// parent constructor
		parent::__construct();
		
		// set class variables
		$this->setId($id);
		
		// get data from db
		$this->dbLoadNavi();
	}
	
	
	/*
	 * methods
	 */
	/**
	 * dbLoadNavi() loads the details of the navigation subtree from database
	 * 
	 * @return void
	 */
	private function dbLoadNavi() {
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get group details
		$sql = 'SELECT `name`,`parent`,`file_param`,`position`,`show`,`valid`,`required_permission`
				FROM navi
				WHERE id=\''.$db->real_escape_string($this->getId()).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if($result) {
			list($name, $parent, $fileParam, $position, $show, $valid, $requiredPermission) = $result->fetch_array(MYSQLI_NUM);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// prepare sql statement to get subgroups
		$sql = 'SELECT id
				FROM navi
				WHERE parent=\''.$db->real_escape_string($this->getId()).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// close db
		$db->close();
		
		// get data
		$subItems = array();
		if($result) {
			
			while(list($subId) = $result->fetch_array(MYSQLI_NUM)) {
				$subItems[] = new Navi($subId);
			}
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// set values
		$this->setName($name);
		$this->setParent($parent);
		$this->setFileParam($fileParam);
		$this->setPosition($position);
		$this->setShow($show);
		$this->setValid($valid);
		$this->setRequiredPermission($requiredPermission);
		$this->setSubItems($subItems);
	}
	
	
	/**
	 * output($file, $param) returns the navigation tree as HTML output depending on the
	 * settings in globalConfig->navi.style
	 * 
	 * @param string $file filename of the URI
	 * @param string $param content of the URI parameter "id"
	 * @return string HTML output of the navigation tree
	 */
	public function output($file, $param) {
		
		// get smarty object
		$smartyTpl = new JudoIntranetSmarty();
		
		// get second level ids
		$secondConfig = json_decode($this->getGc()->get_config('navi.secondJs'), true);
		if(is_null($secondConfig)) {
			$secondConfig = array();
		}
		$keys = array_keys($secondConfig);
		$secondIds = implode(',', $keys);
		$secondJs = false;
		if(count($secondIds) > 0) {
			$secondJs = true;
		}
		
		// get sorted tree
		$data = $this->allItems();
		
		// assign data
		$smartyTpl->assign('naviStyle', $this->getGc()->get_config('navi.style'));
		$smartyTpl->assign('data', $data);
		$smartyTpl->assign('param', $param);
		$smartyTpl->assign('file', $file);
		$this->getTpl()->assign('naviSecondJs', $secondJs);
		$this->getTpl()->assign('naviSecondIds', $secondIds);
		$this->getTpl()->assign('naviSecondCloseText', _l('close'));
		
		
		// return smarty template
		return $smartyTpl->fetch('smarty.navi.tpl');
	}
	
	
	/**
	 * allItems() returns an array containing the complete navigation subtree in the needed
	 * order
	 * 
	 * @param int $level level information for recursion
	 * @return array array containing the complete navigation subtree in the needed order
	 */
	private function allItems($level=0) {
		
		// prepare return
		$allItems = array();
		
		// check permissions or main page
		if(	$this->getUser()->hasPermission('navi', $this->getId())
			|| $this->subItemsPermitted()
			|| $this->getFileParam() == 'index.php|') {
			
			// walk through subitems recursively
			if(count($this->getSubItems()) == 0) {
				$allItems[] = $this->asArray($level);
			} else {
				
				// add own object
				$allItems[] = $this->asArray($level);
				
				// get subitems
				$subItems = $this->getSubItems();
				// sort by position
				usort($subItems, array($this, 'callbackSortNavi'));
				
				// increment level
				$level++;
				
				// check login/logout
				if($this->getFileParam() != 'index.php|') {
				
					foreach($subItems as $subItem) {
						
						// check permission
						if(!$this->getUser()->hasPermission('navi', $subItem->getId(), $subItem->getRequiredPermission())){
							continue;
						}
						
						// check show
						if($subItem->getShow() == 0){
							continue;
						}
						
						// check if has subitems
						if(count($subItem->getSubItems()) == 0) {
							$allItems[] = $subItem->asArray($level);
						} else {
							
							// get ids
							$tempItems = $subItem->allItems($level, true);
							$allItems = array_merge($allItems, $tempItems);
						}
					}
				} else {
					
					// check if user is loggedin
					if($this->getUser()->get_loggedin() === true) {
						
						// add logout
						$allItems[] = $subItems[1]->asArray($level);
					} else {
						
						// add logout
						$allItems[] = $subItems[0]->asArray($level);
					}
					
					// add the following items
					if(count($subItems) >=3) {
						for($i=2; $i<count($subItems); $i++) {
							
							// check permission
							if(!$this->getUser()->hasPermission('navi', $subItems[$i]->getId(), $subItem->getRequiredPermission())){
								continue;
							}
							
							// check show
							if($subItems[$i]->getShow() == 0){
								continue;
							}
						
							$allItems[] = $subItem[$i]->asArray($level);
						}
					}
				}
			}
		}
		
		// return
		return $allItems;
	}
	
	
	/**
	 * asArray() returns all data of this Navi object as an array
	 * 
	 * @param int $level level information to have the position in navigation subtree
	 * @return array all data of this Navi object as an array
	 */
	private function asArray($level) {
		
		// split file and param
		list($file, $param) = explode('|', $this->getFileParam(), 2);
		
		// add redirect
		$r = '';
		if($param == 'login' && $this->get('id') != 'logout') {
			$r = '&amp;r='.base64_encode($_SERVER['REQUEST_URI']);
		}
		
		// create href
		$href = $file.($param != '' ? '?id='.$param.$r : '');
		
		// return data as array
		return array(
				'id' => $this->getId(),
				'name' => _l($this->getName()),
				'parent' => $this->getParent(),
				'file' => $file,
				'param' => $param,
				'position' => $this->getPosition(),
				'show' => $this->getShow(),
				'valid' => $this->getValid(),
				'level' => $level,
				'href' => $href,
			);
	}
	
	
	/**
	 * idFromFileParam($file, $param) returns the navigation id for given $file and $param
	 * 
	 * @param string $file script filename that has called the method
	 * @param string $param id parameter of the URI
	 * @return int id of the corresponding navigation item
	 */
	public static function idFromFileParam($file, $param) {
		
		// put $file and $param together for database
		$fileParam = $file.'|'.$param;
		
		// get db object
		$db = Db::newDb();
		
		// prepare sql statement to get id
		$sql = 'SELECT id
				FROM navi
				WHERE file_param=\''.$db->real_escape_string($fileParam).'\'';
		
		// execute statement
		$result = $db->query($sql);
		
		// get data
		if($result) {
			list($id) = $result->fetch_array(MYSQLI_NUM);
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
		
		// execute statement
		$result = $db->query($sql);
		
		// close db
		$db->close();
		
		// return
		return $id;
	}
	
	
	/**
	 * exists($nid) checks if the given $nid exists in database
	 * 
	 * @param int $gid the navi id to be checked for existance
	 * @return bool true if $nid exists, false otherwise
	 */
	public static function exists($nid) {
		
		// prepare sql
		$sql = '
				SELECT COUNT(*)
				FROM `navi`
				WHERE `id`=#?
				';
		
		// get data
		$data = Db::singleValue($sql, array($nid));
		
		if(!is_null($data)) {
			return $data > 0;
		} else {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * dbDeletePermissions() removes all permissions that are directly given to $this object
	 * from database
	 * 
	 * @return void
	 */
	public function dbDeletePermission() {
		
		// prepare sql statement to delete permissions
		$sql = 'DELETE FROM permissions
				WHERE item_table=\'navi\'
					AND item_id=#?';
		
		// execute statement
		$result = Db::executeQuery($sql, array($this->getId()));
		
		// get data
		$items = array();
		if(!$result) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * dbWritePermissions($permissions) writes the permissions given in the $permissions array
	 * to database
	 * 
	 * @param array $permissions array containing group objects and the given permission value
	 * 		that should be granted to the corresponding group
	 * @return void 
	 */
	public function dbWritePermission($permissions) {
		
		// create values
		$values = '';
		foreach($permissions as $groupId => $permission) {
			
			// set groups w/o admin
			if($groupId != 1) {
				if($permission['value'] != '0') {
					$values .= '(
								\'navi\',
								'.Db::realEscapeString($this->getId()).',
								-1,
								'.Db::realEscapeString($groupId).',
								\''.Db::realEscapeString($permission['value']).'\',
								CURRENT_TIMESTAMP,
								'.Db::realEscapeString($this->getUser()->get_id()).'
								),';
				}
			}
		}
		
		// if values to insert
		if(strlen($values) > 0) {
			
			// remove last ","
			$values = substr($values, 0, -1);
			
			// prepare sql statement to get group details
			$sql = 'INSERT IGNORE INTO permissions
						(`item_table`, `item_id`, `user_id`, `group_id`, `mode`, `last_modified`, `modified_by`)
					VALUES
						'.$values;
			
			// execute statement
			$result = Db::executeQuery($sql);
			
			// get data
			$items = array();
			if(!$result) {
				$n = null;
				throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
			}
		}
	}
	
	
	/**
	 * subItemsPermitted() checks if the actual user has permissions von the subitems
	 * 
	 * @return bool true if the actual user has permissions on subitems, false otherwise
	 */
	public function subItemsPermitted() {
		
		// walk through subitems
		foreach($this->getSubItems() as $subItem) {
			if($this->getUser()->hasPermission('navi', $subItem->getId()) === true) {
				return true;
			}
		}
		
		// return
		return false;
	}
	
}
?>
