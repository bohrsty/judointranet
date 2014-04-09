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

class GroupTest extends PHPUnit_Framework_TestCase {
	
	// variables
	
	// setup
	public function setUp() {
		
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testGroupSetterGetter() {
		
		// create group
		$id = 1;
		$group = new Group($id);
		$this->assertEquals($id, $group->getId());
		$this->assertEquals('Admins', $group->getName());
		$this->assertEquals(-1, $group->getParent());
		$this->assertEquals(1, $group->getValid());
		$this->assertInternalType('array', $group->getSubGroups());
		
		// id
		$data = 1;
		
		$group->setId($data);
		$this->assertEquals($data, $group->getId());
		
		// name
		$data = 'groupName';
		
		$group->setName($data);
		$this->assertEquals($data, $group->getName());
		
		// subGroups
		$data = array(1,2,3);
		
		$group->setSubGroups($data);
		$this->assertEquals($data, $group->getSubGroups());
		
		// parent
		$data = -1;
		
		$group->setParent($data);
		$this->assertEquals($data, $group->getParent());
		
		// valid
		$data = 0;
		
		$group->setValid($data);
		$this->assertEquals($data, $group->getValid());
		
		// level
		$data = 0;
		
		$this->assertEquals(null, $group->getLevel());
		$group->setLevel($data);
		$this->assertEquals($data, $group->getLevel());
		
		// used
		$data = true;
		
		$group->setUsed($data);
		$this->assertEquals($data, $group->getUsed());
		
		// test existance
		$this->assertTrue(Group::exists(1));
		$this->assertFalse(Group::exists(-1));
		
		// test new group, update and deletion
		// empty group
		$group = new Group();
		$this->assertEquals(0, $group->getId());
		// update
		$data = array(
				'name' => 'Testgroup',
				'parent' => 0,
				'valid' => 1,
			);
		$group->update(
			$data
		);
		$this->assertEquals($data['name'], $group->getName());
		$this->assertEquals($data['parent'], $group->getParent());
		$this->assertEquals($data['valid'], $group->getValid());
		$id = $group->writeDb();
		$this->assertEquals($id, $group->getId());
		// delete
		$group->delete();
		$this->assertFalse(Group::exists($id));
	}
	
	
	public function testGroupGetOwnAndAllSubGroupInfos() {
		
		// create group
		$id = 1;
		$group = new Group($id);
		// get subgroup ids
		$subGroups = $group->allGroups();
		
		// result needs to contain the own id
		$this->assertArrayHasKey($id, $subGroups);
		$this->assertInstanceOf('Group', $subGroups[$id]);
		$this->assertEquals($group->getId(), $subGroups[$id]->getId());
		$this->assertEquals($group->getName(), $subGroups[$id]->getName());
		
		// all existing groups
		$allGroups = Group::allExistingGroups();
		$this->assertArrayHasKey(1, $allGroups);
		$this->assertEquals(1, $allGroups[1]->getId());
		$this->assertEquals('Admins', $allGroups[1]->getName());
		
		// is used
		$this->assertFalse(Group::isUsed(1));
	}
	
	
	public function testGroupOutput() {
		
		// create group
		$id = 1;
		$group = new Group($id);
		
		// intended groupname
		$data = array(
				0 => '',
				1 => '|--',
				'1+' => '|&nbsp;&nbsp;&nbsp;',
			);
		
		$group->setLevel(0);
		$this->assertEquals($group->getName(), $group->nameToTextIntended($data));
		$group->setLevel(1);
		$this->assertEquals($data[1].$group->getName(), $group->nameToTextIntended($data));
		$group->setLevel(2);
		$this->assertEquals($data['1+'].$data[1].$group->getName(), $group->nameToTextIntended($data));
		$group->setLevel(3);
		$this->assertEquals($data['1+'].$data['1+'].$data[1].$group->getName(), $group->nameToTextIntended($data));
	}

}

?>
