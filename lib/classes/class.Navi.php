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
			list($name, $parent, $fileParam, $position, $show, $valid, $requiredPermission) = $result->fetch_array(MYSQL_NUM);
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error);
			$this->getError()->handle_error($errno);
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
			
			while(list($subId) = $result->fetch_array(MYSQL_NUM)) {
				$subItems[] = new Navi($subId);
			}
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error);
			$this->getError()->handle_error($errno);
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
	 * output() returns the navigation tree as HTML output
	 * 
	 * @param string $file filename of the URI
	 * @param string $param content of the URI parameter "id"
	 * @return string HTML output of the navigation tree
	 */
	public function output($file, $param) {
		
		// get smarty object
		$smartyTpl = new JudoIntranetSmarty();
		
		// get sorted tree
		$data = $this->allItems();
		
		// assign data
		$smartyTpl->assign('data', $data);
		$smartyTpl->assign('param', $param);
		$smartyTpl->assign('file', $file);
		
		
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
		if($this->getUser()->hasPermission('navi', $this->getId()) || $this->getFileParam() == 'index.php|') {
			
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
							$tempItems = $subItem->allItems();
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
				'name' => parent::lang($this->getName()),
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
	 * callbackSortNavi compares two Navi objects by position (for usort)
	 * 
	 * @param object $first first navi entry
	 * @param object $second second navi entry
	 * @return int -1 if $first<$second, 0 if equal, 1 if $first>$second
	 */
	private function callbackSortNavi($first,$second) {
	
		// compare dates
		if($first->getPosition() < $second->getPosition()) {
			return -1;
		}
		if($first->getPosition() == $second->getPosition()) {
			return 0;
		}
		if($first->getPosition() > $second->getPosition()) {
			return 1;
		}
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
			list($id) = $result->fetch_array(MYSQL_NUM);
		} else {
			$errno = $this->getError()->error_raised('MysqlError', $db->error);
			$this->getError()->handle_error($errno);
		}
		
		// execute statement
		$result = $db->query($sql);
		
		// close db
		$db->close();
		
		// return
		return $id;
	}
	
}
?>
